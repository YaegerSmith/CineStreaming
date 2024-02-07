<?php
// Verificar si el formulario fue enviado para agregar, modificar o borrar una categoría
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establece la conexión a la base de datos
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "cinestreaming";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    function validarDatos($datos)
    {
        $datos = trim($datos);
        $datos = stripslashes($datos);
        $datos = htmlspecialchars($datos);
        return $datos;
    }

    function validarPalabra($palabra)
    {
        // Verifica si la cadena solo contiene letras y espacios
        if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚ\s]+$/", $palabra)) {
            return true;
        } else {
            return false;
        }
    }

    function validarIDActor($id_actor)
    {
        // Verifica si el ID del actor es un número entero
        if (!is_numeric($id_actor) || floor($id_actor) != $id_actor) {
            die("Error: ID de actor no válido. Debe ser un número entero.");
        }
        return (int) $id_actor;
    }
    //Función para validar que un actor no pueda ser eliminado si esta registrado en una película.
    function puedeEliminarActor($conn, $id_actor)
    {
        $sql = "SELECT COUNT(*) as num_peliculas FROM tblpelicula WHERE id_actor1 = ? OR id_actor2 = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_actor, $id_actor);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row["num_peliculas"] == 0;
    }

    if (isset($_POST["borrar_actor"])) {
        $id_actor = validarIDActor($_POST["id_actor"]);

        // Verificar si el actor puede ser eliminado
        if (puedeEliminarActor($conn, $id_actor)) {
            $sql = "DELETE FROM tblactor WHERE id_actor = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_actor);

            if ($stmt->execute()) {
                echo "Actor borrado correctamente";
            } else {
                echo "Error al borrar actor: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error: El actor no puede ser eliminado porque está asociado a una película.";
        }
    }

    // Obtener y mostrar actores
    $sql_select_actores = "SELECT id_actor, nombre, ap_paterno, ap_materno, nacionalidad, fecha_nacimiento FROM tblactor";
    $result_actores = $conn->query($sql_select_actores);

    if ($result_actores->num_rows > 0) {
        echo "<h2>Actores Existentes</h2>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID de actor</th>";
        echo "<th>Nombre</th>";
        echo "<th>Apellido Paterno</th>";
        echo "<th>Apellido Materno</th>";
        echo "<th>Nacionalidad</th>";
        echo "<th>Fecha de Nacimiento</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($row = $result_actores->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id_actor"] . "</td>";
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
        echo "<p>No hay actores existentes.</p>";
    }

    $conn->close();
}
?>
