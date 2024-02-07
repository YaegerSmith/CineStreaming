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

    function validarDatos($datos) {
        $datos = trim($datos);
        $datos = stripslashes($datos);
        $datos = htmlspecialchars($datos);
        return $datos;
    }

    function validarNumero($numero) {
        // Verifica si la cadena solo contiene números
        if (is_numeric($numero)) {
            return true;
        } else {
            die("Error: Solo se permiten números para el campo " . $numero);
        }
    }

    function validarFecha($fecha) {
        // Verifica si la cadena tiene un formato de fecha válido
        $date = date_create_from_format('Y-m-d', $fecha);
        
        if ($date === false || !$date) {
            die("Error: Formato de fecha no válido para el campo " . $fecha);
        }

        return $fecha;
    }

    if (isset($_POST["agregar_suscripcion"])) {
        $id_cliente = validarNumero($_POST["id_cliente"]);
        $tipo_suscripcion = validarDatos($_POST["tipo_suscripcion"]);
        $numero_tarjeta = validarDatos($_POST["numero_tarjeta"]);
        $banco_tarjeta = validarDatos($_POST["banco_tarjeta"]);
        $precio_pagar = validarNumero($_POST["precio_pagar"]);
        $fecha_tarjeta = validarDatos($_POST["fecha_tarjeta"]);
        $cvc = validarDatos($_POST["cvc"]);
        $fecha_registro = validarFecha($_POST["fecha_registro"]);

        $sql = "INSERT INTO tblsuscripcion (id_cliente, tipo_suscripcion, numero_tarjeta, banco_tarjeta, precio_pagar, fecha_tarjeta, cvc, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssdsss", $id_cliente, $tipo_suscripcion, $numero_tarjeta, $banco_tarjeta, $precio_pagar, $fecha_tarjeta, $cvc, $fecha_registro);

        if ($stmt->execute()) {
            echo "Suscripción agregada correctamente";
        } else {
            echo "Error al agregar suscripción: " . $stmt->error;
        }

        $stmt->close();
    }

    if (isset($_POST["borrar_suscripcion"])) {
        $id_suscripcion = validarNumero($_POST["id_suscripcion"]);

        $sql = "DELETE FROM tblsuscripcion WHERE id_suscripcion = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_suscripcion);

        if ($stmt->execute()) {
            echo "Suscripción borrada correctamente";
        } else {
            echo "Error al borrar suscripción: " . $stmt->error;
        }

        $stmt->close();
    }

    if (isset($_POST["modificar_suscripcion"])) {
        $id_suscripcion_modificar = validarNumero($_POST["id_suscripcion_modificar"]);
        $id_cliente_modificar = validarNumero($_POST["id_cliente"]);
        $tipo_suscripcion_modificar = validarDatos($_POST["tipo_suscripcion"]);
        $numero_tarjeta_modificar = validarDatos($_POST["numero_tarjeta"]);
        $banco_tarjeta_modificar = validarDatos($_POST["banco_tarjeta"]);
        $precio_pagar_modificar = validarNumero($_POST["precio_pagar"]);
        $fecha_tarjeta_modificar = validarDatos($_POST["fecha_tarjeta"]);
        $cvc_modificar = validarDatos($_POST["cvc"]);
        $fecha_registro_modificar = validarFecha($_POST["fecha_registro"]);

        $sql = "UPDATE tblsuscripcion SET id_cliente = ?, tipo_suscripcion = ?, numero_tarjeta = ?, banco_tarjeta = ?, precio_pagar = ?, fecha_tarjeta = ?, cvc = ?, fecha_registro = ? WHERE id_suscripcion = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssdsssi", $id_cliente_modificar, $tipo_suscripcion_modificar, $numero_tarjeta_modificar, $banco_tarjeta_modificar, $precio_pagar_modificar, $fecha_tarjeta_modificar, $cvc_modificar, $fecha_registro_modificar, $id_suscripcion_modificar);

        if ($stmt->execute()) {
            echo "Suscripción modificada correctamente";
        } else {
            echo "Error al modificar suscripción: " . $stmt->error;
        }

        $stmt->close();
    }

    $sql_select_suscripciones = "SELECT id_suscripcion, id_cliente, tipo_suscripcion, numero_tarjeta, banco_tarjeta, precio_pagar, fecha_tarjeta, cvc, fecha_registro FROM tblsuscripcion";
    $result_suscripciones = $conn->query($sql_select_suscripciones);

    if ($result_suscripciones->num_rows > 0) {
        echo "<h2>Suscripciones Existentes</h2>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID de suscripción</th>";
        echo "<th>ID de cliente</th>";
        echo "<th>Tipo de suscripción</th>";
        echo "<th>Número de Tarjeta</th>";
        echo "<th>Banco de Tarjeta</th>";
        echo "<th>Precio a Pagar</th>";
        echo "<th>Fecha de Tarjeta</th>";
        echo "<th>CVC</th>";
        echo "<th>Fecha de Registro</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($row = $result_suscripciones->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id_suscripcion"] . "</td>";
            echo "<td>" . $row["id_cliente"] . "</td>";
            echo "<td>" . $row["tipo_suscripcion"] . "</td>";
            echo "<td>" . $row["numero_tarjeta"] . "</td>";
            echo "<td>" . $row["banco_tarjeta"] . "</td>";
            echo "<td>" . $row["precio_pagar"] . "</td>";
            echo "<td>" . $row["fecha_tarjeta"] . "</td>";
            echo "<td>" . $row["cvc"] . "</td>";
            echo "<td>" . $row["fecha_registro"] . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No hay suscripciones existentes.</p>";
    }

    $conn->close();
}
?>
