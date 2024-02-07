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

    //Conexión a la base de datos.
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    //Verificar conexión.
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }


    function validarDatos($datos) {
        $datos = trim($datos);
        $datos = stripslashes($datos);
        $datos = htmlspecialchars($datos);
        return $datos;
    }

    function validarAnoLanzamiento($ano_lanzamiento) {
        $ano_lanzamiento = intval($ano_lanzamiento);
        if ($ano_lanzamiento > 1900 && $ano_lanzamiento < 2024) {
            return $ano_lanzamiento;
        } else {
            throw new Exception("Error: Año de lanzamiento no válido. Solo se permiten valores positivos mayores de 1900 y menores a 2024");
        }
    }

    function validarPalabra($palabra) {
        // Verifica si la cadena solo contiene letras y espacios
        if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚ\s]+$/", $palabra)) {
            return $palabra;
        } else {
            throw new Exception("Error: Solo se permiten palabras para nombre, país y clasificación.");
        }
    }

    try {
        if (isset($_POST["agregar_pelicula"])) {
            $id_categoria = intval(validarDatos($_POST["id_categoria"]));
            $id_director = intval(validarDatos($_POST["id_director"]));
            $id_actor1 = intval(validarDatos($_POST["id_actor1"]));
            $id_actor2 = intval(validarDatos($_POST["id_actor2"]));
            $id_usuario = intval(validarDatos($_POST["id_usuario"]));
            $nombre = validarPalabra($_POST["nombre"]);
            $pais = validarPalabra($_POST["país"]);
            $sinopsis = validarDatos($_POST["sinopsis"]);
            $ano_lanzamiento = validarAnoLanzamiento($_POST["año_lanzamiento"]);
            $clasificacion = validarPalabra($_POST["clasificacion"]);

            $imagen = file_get_contents($_FILES["imagen"]["tmp_name"]);
            if (!$imagen) {
                throw new Exception("Error: No se pudo cargar la imagen.");
            }
            $imagen = base64_encode($imagen);

            $sql = "INSERT INTO tblpelicula (id_categoria, id_director, id_actor1, id_actor2, id_usuario, nombre, país, sinopsis, año_lanzamiento, clasificacion, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiiiisssiss", $id_categoria, $id_director, $id_actor1, $id_actor2, $id_usuario, $nombre, $pais, $sinopsis, $ano_lanzamiento, $clasificacion, $imagen);

            if ($stmt->execute()) {
                echo "Película agregada correctamente";
            } else {
                echo "Error al agregar película: " . $stmt->error;
            }

            $stmt->close();
        }

        if (isset($_POST["borrar_pelicula"])) {
            $id_pelicula = intval(validarDatos($_POST["id_pelicula"]));

            $sql = "DELETE FROM tblpelicula WHERE id_pelicula = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_pelicula);

            if ($stmt->execute()) {
                echo "Película borrada correctamente";
            } else {
                echo "Error al borrar película: " . $stmt->error;
            }

            $stmt->close();
        }

        if (isset($_POST["modificar_pelicula"])) {
            $id_pelicula_modificar = intval(validarDatos($_POST["id_pelicula_modificar"]));
            $id_categoria_modificar = intval(validarDatos($_POST["id_categoria"]));
            $id_director_modificar = intval(validarDatos($_POST["id_director"]));
            $id_actor1_modificar = intval(validarDatos($_POST["id_actor1"]));
            $id_actor2_modificar = intval(validarDatos($_POST["id_actor2"]));
            $id_usuario_modificar = intval(validarDatos($_POST["id_usuario"]));
            $nombre_modificar = validarPalabra($_POST["nombre"]);
            $pais_modificar = validarPalabra($_POST["país"]);
            $sinopsis_modificar = validarDatos($_POST["sinopsis"]);
            $ano_lanzamiento_modificar = validarAnoLanzamiento($_POST["año_lanzamiento"]);
            $clasificacion_modificar = validarPalabra($_POST["clasificacion"]);

            $imagen_modificar = file_get_contents($_FILES["imagen"]["tmp_name"]);
            if (!$imagen_modificar) {
                throw new Exception("Error: No se pudo cargar la imagen para modificar.");
            }
            $imagen_modificar = base64_encode($imagen_modificar);

            $sql = "UPDATE tblpelicula SET id_categoria = ?, id_director = ?, id_actor1 = ?, id_actor2 = ?, id_usuario = ?, nombre = ?, país = ?, sinopsis = ?, año_lanzamiento = ?, clasificacion = ?, imagen = ? WHERE id_pelicula = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiiiisssissi", $id_categoria_modificar, $id_director_modificar, $id_actor1_modificar, $id_actor2_modificar, $id_usuario_modificar, $nombre_modificar, $pais_modificar, $sinopsis_modificar, $ano_lanzamiento_modificar, $clasificacion_modificar, $imagen_modificar, $id_pelicula_modificar);

            if ($stmt->execute()) {
                echo "Película modificada correctamente";
            } else {
                echo "Error al modificar película: " . $stmt->error;
            }

            $stmt->close();
        }

        $sql_select_peliculas = "SELECT id_pelicula, nombre FROM tblpelicula";
        $result_peliculas = $conn->query($sql_select_peliculas);

        if ($result_peliculas->num_rows > 0) {
            echo "<h2>Películas Existentes</h2>";
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>ID de película</th>";
            echo "<th>Nombre</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            while ($row = $result_peliculas->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_pelicula"] . "</td>";
                echo "<td>" . $row["nombre"] . "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No hay películas existentes.</p>";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}
?>
