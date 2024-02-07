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

    function validarPalabra($dato, $campo) {
        $dato = trim($dato);
        $dato = stripslashes($dato);
        $dato = htmlspecialchars($dato);

        // Verificar si solo contiene letras y espacios
        if (!preg_match("/^[a-zA-Z ]+$/", $dato)) {
            die("Error: $campo no válido. Solo se permiten letras y espacios.");
        }

        return $dato;
    }

    function validarRFC($rfc) {
        // Verificar que tenga una longitud exacta de 13 caracteres alfanuméricos
        if (strlen($rfc) !== 13 || !ctype_alnum($rfc)) {
            die("Error: RFC no válido. Debe tener una longitud exacta de 13 caracteres alfanuméricos.");
        }

        return $rfc;
    }

    function validarCURP($curp) {
        // Verificar que tenga una longitud exacta de 18 caracteres alfanuméricos
        if (strlen($curp) !== 18 || !ctype_alnum($curp)) {
            die("Error: CURP no válido. Debe tener una longitud exacta de 18 caracteres alfanuméricos.");
        }

        return $curp;
    }

    if (isset($_POST["agregar_cliente"])) {
        $nombre = validarPalabra($_POST["nombre"], "Nombre");
        $ap_paterno = validarPalabra($_POST["ap_paterno"], "Apellido Paterno");
        $ap_materno = validarPalabra($_POST["ap_materno"], "Apellido Materno");
        $RFC = validarRFC($_POST["RFC"]);
        $CURP = validarCURP($_POST["CURP"]);
        $tipo_membresia = validarPalabra($_POST["tipo_membresia"], "Tipo de Membresía");
        $fecha_inicio_membresia = validarDatos($_POST["fecha_inicio_membresia"]);
        $fecha_termino_membresia = validarDatos($_POST["fecha_termino_membresia"]);

        $sql = "INSERT INTO tblcliente (nombre, ap_paterno, ap_materno, RFC, CURP, tipo_membresia, fecha_inicio_membresia, fecha_termino_membresia) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $nombre, $ap_paterno, $ap_materno, $RFC, $CURP, $tipo_membresia, $fecha_inicio_membresia, $fecha_termino_membresia);

        if ($stmt->execute()) {
            echo "Cliente agregado correctamente";
        } else {
            echo "Error al agregar cliente: " . $stmt->error;
        }

        $stmt->close();
    }

    if (isset($_POST["borrar_cliente"])) {
        $id_cliente = intval(validarDatos($_POST["id_cliente"]));

        $sql = "DELETE FROM tblcliente WHERE id_cliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_cliente);

        if ($stmt->execute()) {
            echo "Cliente borrado correctamente";
        } else {
            echo "Error al borrar cliente: " . $stmt->error;
        }

        $stmt->close();
    }

    if (isset($_POST["modificar_cliente"])) {
        $id_cliente_modificar = intval(validarDatos($_POST["id_cliente_modificar"]));
        $nombre_modificar = validarPalabra($_POST["nombre_modificar"], "Nombre");
        $ap_paterno_modificar = validarPalabra($_POST["ap_paterno_modificar"], "Apellido Paterno");
        $ap_materno_modificar = validarPalabra($_POST["ap_materno_modificar"], "Apellido Materno");
        $RFC_modificar = validarRFC($_POST["RFC_modificar"]);
        $CURP_modificar = validarCURP($_POST["CURP_modificar"]);
        $tipo_membresia_modificar = validarPalabra($_POST["tipo_membresia_modificar"], "Tipo de Membresía");
        $fecha_inicio_membresia_modificar = validarDatos($_POST["fecha_inicio_membresia_modificar"]);
        $fecha_termino_membresia_modificar = validarDatos($_POST["fecha_termino_membresia_modificar"]);

        $sql = "UPDATE tblcliente SET nombre = ?, ap_paterno = ?, ap_materno = ?, RFC = ?, CURP = ?, tipo_membresia = ?, fecha_inicio_membresia = ?, fecha_termino_membresia = ? WHERE id_cliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $nombre_modificar, $ap_paterno_modificar, $ap_materno_modificar, $RFC_modificar, $CURP_modificar, $tipo_membresia_modificar, $fecha_inicio_membresia_modificar, $fecha_termino_membresia_modificar, $id_cliente_modificar);

        if ($stmt->execute()) {
            echo "Cliente modificado correctamente";
        } else {
            echo "Error al modificar cliente: " . $stmt->error;
        }

        $stmt->close();
    }

    $sql_select_clientes = "SELECT id_cliente, nombre, ap_paterno, ap_materno, RFC, CURP, tipo_membresia, fecha_inicio_membresia, fecha_termino_membresia FROM tblcliente";
    $result_clientes = $conn->query($sql_select_clientes);

    if ($result_clientes->num_rows > 0) {
        echo "<h2>Clientes Existentes</h2>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID de cliente</th>";
        echo "<th>Nombre</th>";
        echo "<th>Apellido Paterno</th>";
        echo "<th>Apellido Materno</th>";
        echo "<th>RFC</th>";
        echo "<th>CURP</th>";
        echo "<th>Tipo de Membresía</th>";
        echo "<th>Fecha de Inicio de Membresía</th>";
        echo "<th>Fecha de Término de Membresía</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($row = $result_clientes->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id_cliente"] . "</td>";
            echo "<td>" . $row["nombre"] . "</td>";
            echo "<td>" . $row["ap_paterno"] . "</td>";
            echo "<td>" . $row["ap_materno"] . "</td>";
            echo "<td>" . $row["RFC"] . "</td>";
            echo "<td>" . $row["CURP"] . "</td>";
            echo "<td>" . $row["tipo_membresia"] . "</td>";
            echo "<td>" . $row["fecha_inicio_membresia"] . "</td>";
            echo "<td>" . $row["fecha_termino_membresia"] . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No hay clientes existentes.</p>";
    }

    $conn->close();
}
?>
