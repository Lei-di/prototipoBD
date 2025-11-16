<?php
// config/db.php

function getDbConnection() {
    $host = 'localhost';
    $port = '5432';
    $dbname = 'bd_seguranca'; // O nome do seu banco
    $user = 'postgres';       // Seu usuário do pgAdmin
    $pass = '1234';   // Sua senha

    $conn_string = "host=$host port=$port dbname=$dbname user=$user password=$pass";
    
    $db_conn = pg_connect($conn_string);

    if (!$db_conn) {
        die("Erro ao conectar ao banco de dados.");
    }

    return $db_conn;
}
?>