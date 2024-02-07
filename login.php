<?php
//Configuración de la base de datos
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "cinestreaming";

//Se Creao la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

//Se verifico la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

//Obtener datos del formulario,
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

//Consultar la base de datos para el usuario y la contraseña.
$sql = "SELECT * FROM tblusuario WHERE usuario = '$usuario' AND contraseña = '$contrasena'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // El usuario y la contraseña son válidos.
    $row = $result->fetch_assoc();
    
    //Verificar el rol del usuario y redirigir a la página adecuada.
    if ($row['rol'] == 'administrador') {
        header("Location: MOVEDashBoardAdministrador.html");
    } elseif ($row['rol'] == 'suscriptor') {
        header("Location: MOVEPerfilesSuscriptor.html");
    } else {
        //Rol no reconocido, manejar según sea necesario.
        echo "Rol no reconocido para el usuario.";
    }
} else {
    //Usuario o contraseña incorrectos, manejar según sea necesario.
    echo "Usuario o contraseña incorrectos.";
}

// Cerrar la conexión
$conn->close();
?>
