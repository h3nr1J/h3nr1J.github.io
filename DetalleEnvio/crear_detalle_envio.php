<?php
include("../db.php");

// Obtener lista de pedidos desde la base de datos
$result_pedidos = $conn->query("SELECT id_pedido FROM pedidos");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pedido = $_POST["id_pedido"];
    $nombre_cliente = $_POST["nombre_cliente"];
    $direccion_envio = $_POST["direccion_envio"];
    $ciudad_envio = $_POST["ciudad_envio"];
    $pais_envio = $_POST["pais_envio"];
    $codigo_postal = $_POST["codigo_postal"];

    // Llamar al procedimiento almacenado
    $query = "CALL sp_CrearDetalleEnvio($id_pedido, '$nombre_cliente', '$direccion_envio', '$ciudad_envio', '$pais_envio', '$codigo_postal')";

    if ($conn->query($query) === TRUE) {
        header("Location: indexEnvio.php"); // Redireccionar después de agregar
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Agregar Detalle de Envío</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

    <h1>Agregar Detalle de Envío</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

        <div class="mb-3">
            <label for="id_pedido" class="form-label">ID de Pedido:</label>
            <select name="id_pedido" id="id_pedido" class="form-select">
                <?php
                while ($row = $result_pedidos->fetch_assoc()) {
                    echo "<option value=\"" . $row['id_pedido'] . "\">" . $row['id_pedido'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="nombre_cliente" class="form-label">Nombre del Cliente:</label>
            <input type="text" name="nombre_cliente" class="form-control">
        </div>

        <div class="mb-3">
            <label for="direccion_envio" class="form-label">Dirección de Envío:</label>
            <input type="text" name="direccion_envio" class="form-control">
        </div>

        <div class="mb-3">
            <label for="ciudad_envio" class="form-label">Ciudad de Envío:</label>
            <input type="text" name="ciudad_envio" class="form-control">
        </div>

        <div class="mb-3">
            <label for="pais_envio" class="form-label">País de Envío:</label>
            <input type="text" name="pais_envio" class="form-control">
        </div>

        <div class="mb-3">
            <label for="codigo_postal" class="form-label">Código Postal:</label>
            <input type="text" name="codigo_postal" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="javascript:history.go(-1);" class=" btn btn-secondary">Volver </a>

    </form>

    <!-- Bootstrap JS and Popper.js, required for Bootstrap components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>