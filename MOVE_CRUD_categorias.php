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

    // Función para limpiar y validar datos del formulario
    function validarDatos($datos) {
        $datos = trim($datos);
        $datos = stripslashes($datos);
        $datos = htmlspecialchars($datos);
        return $datos;
    }

    // Función para validar que solo se permitan palabras en el campo de categoría
    function validarCategoria($categoria) {
        $categoria = validarDatos($categoria);

        // Verificar si solo contiene letras y espacios
        if (!preg_match("/^[a-zA-Z ]+$/", $categoria)) {
            die("Error: Categoría no válida. Solo se permiten letras y espacios.");
        }

        return $categoria;
    }

    // Función para validar que el ID de categoría sea un número entero
    function validarIDCategoria($id_categoria) {
        if (!is_numeric($id_categoria) || $id_categoria != intval($id_categoria)) {
            die("Error: ID de categoría no válido. Debe ser un número entero.");
        }
        return intval($id_categoria);
    }

    // Validar ID en toda la lógica del script
    if (isset($_POST["id_categoria"])) {
        $id_categoria = validarIDCategoria($_POST["id_categoria"]);
    }

    if (isset($_POST["id_categoria_modificar"])) {
        $id_categoria_modificar = validarIDCategoria($_POST["id_categoria_modificar"]);
    }

    // Si se hace clic en "Agregar Categoría"
    if (isset($_POST["agregar_categoria"])) {
        // Valida y obtiene datos del formulario
        $categoria = validarCategoria($_POST["categoria"]);

        // Prepara y ejecuta la consulta SQL para agregar una nueva categoría
        $sql = "INSERT INTO tblcategoria (categoria) VALUES (?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("s", $categoria);

        if ($stmt->execute()) {
            echo "Categoría agregada correctamente";
        } else {
            echo "Error al agregar categoría: " . $stmt->error;
        }

        // Cierra la conexión a la base de datos
        $stmt->close();
    }

    // Si se hace clic en "Borrar Categoría"
    if (isset($_POST["borrar_categoria"])) {
        // Valida y obtiene datos del formulario
        $id_categoria = validarIDCategoria($_POST["id_categoria"]);

        // Prepara y ejecuta la consulta SQL para borrar una categoría por ID
        $sql = "DELETE FROM tblcategoria WHERE id_categoria = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("i", $id_categoria);

        if ($stmt->execute()) {
            echo "Categoría borrada correctamente";
        } else {
            echo "Error al borrar categoría: " . $stmt->error;
        }

        // Cierra la conexión a la base de datos
        $stmt->close();
    }

    // Si se hace clic en "Modificar Categoría"
    if (isset($_POST["modificar_categoria"])) {
        // Valida y obtiene datos del formulario
        $categoria_modificar = validarCategoria($_POST["categoria"]);

        // Prepara y ejecuta la consulta SQL para modificar una categoría por ID
        $sql = "UPDATE tblcategoria SET categoria = ? WHERE id_categoria = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("si", $categoria_modificar, $id_categoria_modificar);

        if ($stmt->execute()) {
            echo "Categoría modificada correctamente";
        } else {
            echo "Error al modificar categoría: " . $stmt->error;
        }

        // Cierra la conexión a la base de datos
        $stmt->close();
    }

    // Consulta SQL para obtener todas las categorías
    $sql_select_categorias = "SELECT id_categoria, categoria FROM tblcategoria";
    $result_categorias = $conn->query($sql_select_categorias);

    // Verifica si la consulta fue exitosa
    if ($result_categorias->num_rows > 0) {
        // Descomenta y ajusta la sección de HTML para mostrar las categorías existentes
        echo "<h2>Categorías Existentes</h2>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID de categoría</th>";
        echo "<th>Categoría</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // Muestra cada fila de categorías en la tabla
        while ($row = $result_categorias->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id_categoria"] . "</td>";
            echo "<td>" . $row["categoria"] . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        // Si no hay categorías, muestra un mensaje indicando que no hay datos
        echo "<p>No hay categorías existentes.</p>";
    }

    // Cierra la conexión a la base de datos
    $conn->close();
}
?>
