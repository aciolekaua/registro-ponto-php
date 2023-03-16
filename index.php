<?php 
    session_start();
    date_default_timezone_set('America/Sao_Paulo') 

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Regitro de Ponto</title>
</head>
<body>
    <h2>Registro Ponto</h2>
    <?php 
        if(isset($_SESSION['msg'])){
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
    ?>
    <p id="horario"><?php echo date("d/m/Y H:i:s"); ?></p>

    <a href="registrar_ponto.php">Registrar</a>







 <script>
    var apHorario = document.getElementById("horario");

    function atualizarHorario(){
        var data = new Date().toLocaleString('pt-BR', { timeZoneName: 'longOffset', timeZone: 'America/Sao_Paulo' });
        apHorario.innerHTML = data;
    }

    console.log(atualizarHorario())
    setInterval(atualizarHorario, 1000);
 </script>
</body>
</html>