<?php
include("../db.php");

// Obtener lista de pedidos y productos desde la base de datos
$result_pedidos = $conn->query("SELECT id_pedido FROM pedidos");

$result_productos = $conn->query("SELECT id_producto, nombre FROM productos");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pedido = $_POST["id_pedido"];
    $id_producto = $_POST["id_producto"];
    $cantidad = $_POST["cantidad"];
    $precio_unitario = $_POST["precio_unitario"];

    // Llamar al procedimiento almacenado
    $query = "CALL sp_CrearDetallePedido($id_pedido, $id_producto, $cantidad, $precio_unitario)";

    if ($conn->query($query) === TRUE) {
        header("Location: indexPedido.php"); // Redireccionar después de agregar
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Detalle de Pedido</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="container mt-5">

    <h1>Agregar Detalle de Pedido</h1>

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
            <label for="id_producto" class="form-label">Nombre del Producto:</label>
            <select name="id_producto" id="id_producto" class="form-select">
                <?php
                while ($row = $result_productos->fetch_assoc()) {
                    echo "<option value=\"" . $row['id_producto'] . "\">" . $row['nombre'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad:</label>
            <input type="text" name="cantidad" class="form-control">
        </div>

        <div class="mb-3">
            <label for="precio_unitario" class="form-label">Precio Unitario:</label>
            <input type="text" name="precio_unitario" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
    <a href="javascript:history.go(-1);" class="btn btn-secondary mt-3">Volver </a>

</body>

</html>