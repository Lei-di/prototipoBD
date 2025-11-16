<?php
$conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=SUA_SENHA");

if (!$conn) {
    echo pg_last_error();
} else {
    echo "Conectou!";
}
?>
