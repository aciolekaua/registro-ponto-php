<?php

session_start(); // Iniciar a sessao

// Limpar o buffer
ob_start();

// Definir um fuso horario padrao
date_default_timezone_set('America/Sao_Paulo');

// Gerar com PHP o horario atual
$horario_atual = date("H:i:s");
//var_dump($horario_atual);

// Gerar a data com PHP no formato que deve ser salvo no BD
$data_entrada = date('Y/m/d');

// Incluir a conexao com o banco de dados
include_once "conexao.php";

// ID do usuario fixo para testar
$id_usuario = 1;

// Recuperar o ultimo ponto do usuario
$query_ponto = "SELECT id AS id_ponto, saida_intervalo, retorno_intervalo, saida 
                FROM nome_banco
                WHERE usuario_id =:usuario_id
                ORDER BY id DESC
                LIMIT 1";

// Preparar a QUERY
$result_ponto = $conn->prepare($query_ponto);

// Substituir o link da QUERY pelo valor
$result_ponto->bindParam(':usuario_id', $id_usuario);

// Executar a QUERY
$result_ponto->execute();

// Verificar se encontrou algum registro no banco de dados
if (($result_ponto) and ($result_ponto->rowCount() != 0)) {
    // Realizar a leitura do registro
    $row_ponto = $result_ponto->fetch(PDO::FETCH_ASSOC);
    //var_dump($row_ponto);

    // Extrair para imprimir atraves do nome da chave no array
    extract($row_ponto);

    // Verificar se o usuario bateu o ponto de saida para o intervalo
    if (($saida_intervalo == "") or ($saida_intervalo == null)) {
        // Coluna que deve receber o valor
        $col_tipo_registro = "saida_intervalo";

        // Tipo de registro
        $tipo_registro = "editar";

        // Texto parcial que deve ser apresentado para o usuario
        $text_tipo_registro = "saída intervalo";
    } elseif (($retorno_intervalo == "") or ($retorno_intervalo == null)) { // Verificar se o usuario bateu o ponto de retorno do intervalo
        // Coluna que deve receber o valor
        $col_tipo_registro = "retorno_intervalo";

        // Tipo de registro
        $tipo_registro = "editar";

        // Texto parcial que deve ser apresentado para o usuario
        $text_tipo_registro = "retorno do intervalo";
    } elseif (($saida == "") or ($saida == null)) { // Verificar se o usuario bateu o ponto de saida
        // Coluna que deve receber o valor
        $col_tipo_registro = "saida";

        // Tipo de registro
        $tipo_registro = "editar";

        // Texto parcial que deve ser apresentado para o usuario
        $text_tipo_registro = "saída";
    } else { // Criar novo registro no BD com o horrario de entrada
        // Tipo de registro
        $tipo_registro = "entrada";

        // Texto parcial que deve ser apresentado para o usuario
        $text_tipo_registro = "entrada";
    }
} else {
    // Tipo de registro
    $tipo_registro = "entrada";

    // Texto parcial que deve ser apresentado para o usuario
    $text_tipo_registro = "entrada";
}

// Verificar o tipo de registro, novo ponto ou editar registro existe
switch ($tipo_registro) {
        // Acessa o case quando deve editar o registro
    case "editar":
        // Query para editar no banco de dados
        $query_horario = "UPDATE nome_banco SET $col_tipo_registro =:horario_atual
                    WHERE id=:id
                    LIMIT 1";

        // Preparar a QUERY
        $cad_horario = $conn->prepare($query_horario);

        // Substituir o link da QUERY pelo valor
        $cad_horario->bindParam(':horario_atual', $horario_atual);
        $cad_horario->bindParam(':id', $id_ponto);
        break;
    default:
        // Query para cadastrar no banco de dados
        $query_horario = "INSERT INTO nome_banco (data_entrada, entrada, usuario_id) VALUES (:data_entrada, :entrada, :usuario_id )";

        // Preparar a QUERY
        $cad_horario = $conn->prepare($query_horario);

        // Substituir o link da QUERY pelo valor
        $cad_horario->bindParam(':data_entrada', $data_entrada);
        $cad_horario->bindParam(':entrada', $horario_atual);
        $cad_horario->bindParam(':usuario_id', $id_usuario);
        break;
}

// Executar a QUERY
$cad_horario->execute();

// Acessa o IF quando cadastrar com sucesso
if ($cad_horario->rowCount()) {
    $_SESSION['msg'] = "<p style='color: green;'>Horário de $text_tipo_registro cadastrado com sucesso!</p>";
    header("Location: index.php");
} else {
    $_SESSION['msg'] = "<p style='color: #f00;'>Horário de $text_tipo_registro não cadastrado com sucesso!</p>";
    header("Location: index.php");
}