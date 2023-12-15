<?php
include("../db.php");

if (isset($_GET['id_pedido'])) {
    $id_pedido = $_GET['id_pedido'];

    $query = "CALL sp_ListarDetallePedido($id_pedido)";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        ?>
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Detalles del Pedido</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
                integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        </head>

        <body class="container mt-5">
            <h1>Detalles del Pedido</h1>
            <a href='indexPedido.php' class="btn btn-primary mb-3">Atrás</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Detalle</th>
                        <th>Nombre del Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $row['id_detalle']; ?>
                            </td>
                            <td>
                                <?php echo $row['nombre_producto']; ?>
                            </td>
                            <td>
                                <?php echo $row['cantidad']; ?>
                            </td>
                            <td>
                                <?php echo $row['precio_unitario']; ?>
                            </td>
                            <td>
                                <a href='eliminar_detalle.php?id_detalle=<?php echo $row['id_detalle']; ?>&id_pedido=<?php echo $id_pedido; ?>'
                                    class="btn btn-danger">Eliminar</a>
                                <a href='modificar_detalle.php?id=<?php echo $row['id_detalle']; ?>'
                                    class="btn btn-warning">Modificar</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </body>

        </html>
        <?php
    } else {
        echo "No se encontraron detalles para el pedido con ID $id_pedido.";
    }
} else {
    echo "ID de pedido no proporcionado.";
}

$conn->close();
?>