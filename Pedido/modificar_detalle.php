<?php
include("../db.php");

// Obtener lista de detalles de pedido desde la base de datos
$result_detalles = $conn->query("SELECT id_detalle, id_pedido, id_producto, cantidad, precio_unitario FROM detalles_pedido");

$id_pedido = $id_producto = $cantidad = $precio_unitario = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_detalle = $_POST["id_detalle"];
    $id_pedido = $_POST["id_pedido"];
    $id_producto = $_POST["id_producto"];
    $cantidad = $_POST["cantidad"];
    $precio_unitario = $_POST["precio_unitario"];

    // Llamar al procedimiento almacenado de modificación
    $query = "CALL sp_ModificarDetallePedido($id_detalle, $id_pedido, $id_producto, $cantidad, $precio_unitario)";

    if ($conn->query($query) === TRUE) {
        header("Location: indexDetalle.php?id_pedido=" . $id_pedido);
        exit();

    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    // Si no se ha enviado el formulario, obtener los datos del detalle seleccionado
    if (isset($_GET['id_detalle'])) {
        $id_detalle = $_GET['id_detalle'];
        $query = "SELECT id_detalle, id_pedido, id_producto, cantidad, precio_unitario FROM detalles_pedido WHERE id_detalle = $id_detalle";
        $result_detalle = $conn->query($query);

        if ($result_detalle && $result_detalle->num_rows > 0) {
            $row = $result_detalle->fetch_assoc();
            $id_pedido = $row["id_pedido"];
            $id_producto = $row["id_producto"];
            $cantidad = $row["cantidad"];
            $precio_unitario = $row["precio_unitario"];
        } else {
            echo "Error al obtener los datos del detalle seleccionado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Detalle de Pedido</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="container mt-5">

    <h1>Modificar Detalle de Pedido</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="mb-3">
            <label for="id_detalle" class="form-label">Seleccione el Detalle de Pedido:</label>
            <select name="id_detalle" id="id_detalle" class="form-select">
                <?php
                while ($row = $result_detalles->fetch_assoc()) {
                    echo "<option value=\"" . $row['id_detalle'] . "\">" . $row['id_detalle'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_pedido" class="form-label">ID de Pedido:</label>
            <select name="id_pedido" id="id_pedido" class="form-select">
                <?php
                $result_pedidos = $conn->query("SELECT id_pedido FROM pedidos");
                while ($row = $result_pedidos->fetch_assoc()) {
                    echo "<option value=\"" . $row['id_pedido'] . "\"" . ($id_pedido == $row['id_pedido'] ? " selected" : "") . ">" . $row['id_pedido'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_producto" class="form-label">ID de Producto:</label>
            <select name="id_producto" id="id_producto" class="form-select">
                <?php
                $result_productos = $conn->query("SELECT id_producto FROM productos");
                while ($row = $result_productos->fetch_assoc()) {
                    echo "<option value=\"" . $row['id_producto'] . "\"" . ($id_producto == $row['id_producto'] ? " selected" : "") . ">" . $row['id_producto'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad:</label>
            <input type="text" name="cantidad" class="form-control" value="<?php echo $cantidad; ?>">
        </div>

        <div class="mb-3">
            <label for="precio_unitario" class="form-label">Precio Unitario:</label>
            <input type="text" name="precio_unitario" class="form-control" value="<?php echo $precio_unitario; ?>">
        </div>

        <input type="submit" value="Guardar" class="btn btn-primary">
    </form>
    <a href="javascript:history.go(-1);" class="btn btn-secondary mt-3">Volver </a>
</body>

</html>