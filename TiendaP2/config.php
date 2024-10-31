<?php
session_start(); // Iniciar la sesión

$servidor = "localhost";
$usuario = "root";
$pwd = "";
$bd = "tienda";

// Conexión a la base de datos
$con = mysqli_connect($servidor, $usuario, $pwd, $bd);

// Verificar la conexión
if (!$con) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>
