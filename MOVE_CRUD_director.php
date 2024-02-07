<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Host.
    $servername = "localhost";
    //Usuario.
    $username = "id21545569_root";
    //Contraseña.
    $password = "Gato_1743286";
    //Nombre de la base de datos.
    $dbname = "id21545569_cinestreaming";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    function validarDatos($datos) {
        $datos = trim($datos);
        $datos = stripslashes($datos);
        $datos = htmlspecialchars($datos);
        return $datos;
    }

    function validarPalabra($dato, $campo) {
        $dato = trim($dato);
        $dato = stripslashes($dato);
        $dato = htmlspecialchars($dato);

        if (!preg_match("/^[a-zA-Z ]+$/", $dato)) {
            die("Error: $campo no válido. Solo se permiten letras y espacios.");
        }

        return $dato;
    }

    if (isset($_POST["agregar_director"])) {
        $nombre = validarPalabra($_POST["nombre"], "Nombre");
        $ap_paterno = validarPalabra($_POST["ap_paterno"], "Apellido Paterno");
        $ap_materno = validarPalabra($_POST["ap_materno"], "Apellido Materno");
        $nacionalidad = validarPalabra($_POST["nacionalidad"], "Nacionalidad");
        $fecha_nacimiento = validarDatos($_POST["fecha_nacimiento"]);

        $sql = "INSERT INTO tbldirector (nombre, ap_paterno, ap_materno, nacionalidad, fecha_nacimiento) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $ap_paterno, $ap_materno, $nacionalidad, $fecha_nacimiento);

        if ($stmt->execute()) {
            echo "Director agregado correctamente";
        } else {
            echo "Error al agregar director: " . $stmt->error;
        }

        $stmt->close();
    }

    if (isset($_POST["borrar_director"])) {
        $id_director = intval(validarDatos($_POST["id_director"]));

        $sql = "DELETE FROM tbldirector WHERE id_director = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_director);

        if ($stmt->execute()) {
            echo "Director borrado correctamente";
        } else {
            echo "Error al borrar director: " . $stmt->error;
        }

        $stmt->close();
    }

    if (isset($_POST["modificar_director"])) {
        $id_director_modificar = intval(validarDatos($_POST["id_director_modificar"]));
        $nombre_modificar = validarPalabra($_POST["nombre_modificar"], "Nombre");
        $ap_paterno_modificar = validarPalabra($_POST["ap_paterno_modificar"], "Apellido Paterno");
        $ap_materno_modificar = validarPalabra($_POST["ap_materno_modificar"], "Apellido Materno");
        $nacionalidad_modificar = validarPalabra($_POST["nacionalidad_modificar"], "Nacionalidad");
        $fecha_nacimiento_modificar = validarDatos($_POST["fecha_nacimiento_modificar"]);

        $sql = "UPDATE tbldirector SET nombre = ?, ap_paterno = ?, ap_materno = ?, nacionalidad = ?, fecha_nacimiento = ? WHERE id_director = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nombre_modificar, $ap_paterno_modificar, $ap_materno_modificar, $nacionalidad_modificar, $fecha_nacimiento_modificar, $id_director_modificar);

        if ($stmt->execute()) {
            echo "Director modificado correctamente";
        } else {
            echo "Error al modificar director: " . $stmt->error;
        }

        $stmt->close();
    }

    $sql_select_directores = "SELECT id_director, nombre, ap_paterno, ap_materno, nacionalidad, fecha_nacimiento FROM tbldirector";
    $result_directores = $conn->query($sql_select_directores);

    if ($result_directores->num_rows > 0) {
        echo "<h2>Directores Existentes</h2>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID Director</th>";
        echo "<th>Nombre</th>";
        echo "<th>Apellido Paterno</th>";
        echo "<th>Apellido Materno</th>";
        echo "<th>Nacionalidad</th>";
        echo "<th>Fecha de Nacimiento</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($row = $result_directores->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id_director"] . "</td>";
            echo "<td>" . $row["nombre"] . "</td>";
            echo "<td>" . $row["ap_paterno"] . "</td>";
            echo "<td>" . $row["ap_materno"] . "</td>";
            echo "<td>" . $row["nacionalidad"] . "</td>";
            echo "<td>" . $row["fecha_nacimiento"] . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No hay directores existentes.</p>";
    }

    $conn->close();
}
?>
