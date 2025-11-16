<?php
// config/db.php

function getDbConnection() {
    $host = 'localhost';
    $port = '5432';
    $dbname = 'bd_seguranca'; // O nome do seu banco
    $user = 'postgres';       // Seu usuário do pgAdmin
    $pass = '1234';   // Sua senha

    $conn_string = "host=localhost port=5432 dbname=bd_seguranca user=postgres password=1234";
    
    $db_conn = pg_connect($conn_string);

    if (!$db_conn) {
        die("Erro ao conectar ao banco de dados.");
    }

    return $db_conn;
}
?>