<?php
//Información de la base de datos.
$servidor = "localhost";
$usuario = "id21545569_root";
$contrasena = "Gato_1743286"; 
$base_de_datos = "id21545569_cinestreaming";

//Crear conexión.
$conexion = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);

//Verificar la conexión.
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

echo "Conexión exitosa a la base de datos";
?>