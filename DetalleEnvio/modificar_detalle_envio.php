<?php
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $envio_id = $_POST["envio_id"];
    $id_pedido = $_POST["id_pedido"];
    $nombre_cliente = $_POST["nombre_cliente"];
    $direccion_envio = $_POST["direccion_envio"];
    $ciudad_envio = $_POST["ciudad_envio"];
    $pais_envio = $_POST["pais_envio"];
    $codigo_postal = $_POST["codigo_postal"];

    // Llamar al procedimiento almacenado para modificar el detalle de envío
    $query = "CALL sp_ModificarDetalleEnvio($envio_id, $id_pedido, '$nombre_cliente', '$direccion_envio', '$ciudad_envio', '$pais_envio', '$codigo_postal')";

    if ($conn->query($query) === TRUE) {
        header("Location: indexEnvio.php"); // Redireccionar después de modificar
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $conn->close();
} elseif (isset($_GET["id"])) {
    $envio_id = $_GET["id"];
    $query = "SELECT * FROM detalles_envio WHERE id_envio = $envio_id";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $id_pedido = $row["id_pedido"];
        $nombre_cliente = $row["nombre_cliente"];
        $direccion_envio = $row["direccion_envio"];
        $ciudad_envio = $row["ciudad_envio"];
        $pais_envio = $row["pais_envio"];
        $codigo_postal = $row["codigo_postal"];
    } else {
        echo "No se encontró un detalle de envío con el ID proporcionado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Modificar Detalles de Envío</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

    <h1>Modificar Detalles de Envío</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="envio_id" value="<?php echo $envio_id; ?>">

        <div class="mb-3">
            <label for="id_pedido" class="form-label">ID de Pedido:</label>
            <input type="text" class="form-control" name="id_pedido" value="<?php echo $id_pedido; ?>">
        </div>

        <div class="mb-3">
            <label for="nombre_cliente" class="form-label">Nombre del Cliente:</label>
            <input type="text" class="form-control" name="nombre_cliente" value="<?php echo $nombre_cliente; ?>">
        </div>

        <div class="mb-3">
            <label for="direccion_envio" class="form-label">Dirección de Envío:</label>
            <input type="text" class="form-control" name="direccion_envio" value="<?php echo $direccion_envio; ?>">
        </div>

        <div class="mb-3">
            <label for="ciudad_envio" class="form-label">Ciudad de Envío:</label>
            <input type="text" class="form-control" name="ciudad_envio" value="<?php echo $ciudad_envio; ?>">
        </div>

        <div class="mb-3">
            <label for="pais_envio" class="form-label">País de Envío:</label>
            <input type="text" class="form-control" name="pais_envio" value="<?php echo $pais_envio; ?>">
        </div>

        <div class="mb-3">
            <label for="codigo_postal" class="form-label">Código Postal:</label>
            <input type="text" class="form-control" name="codigo_postal" value="<?php echo $codigo_postal; ?>">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="javascript:history.go(-1);" class=" btn btn-secondary">Volver </a>

    </form>

    <!-- Bootstrap JS and Popper.js, required for Bootstrap components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>