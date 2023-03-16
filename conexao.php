<?php 
    define('HOST','');
    define('DATABASE','');
    define('USER','');
    define('PASSWORD','');

    try {
        $conn =  new PDO('mysql:host='.HOST.';dbname='.DATABASE,USER,PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     } catch(Exception $e) {
         echo 'erro ao conectar'.$e->getMessage();
     }

?>