<?php
// Supongamos que ya tienes una conexión a la base de datos en tu archivo db.php
include('db.php');
// Obtener la lista de categorías
$sql_categorias = "SELECT * FROM categorias";
$resultado_categorias = mysqli_query($conn, $sql_categorias);

$categorias = [];
if (mysqli_num_rows($resultado_categorias) > 0) {
    while ($row = mysqli_fetch_assoc($resultado_categorias)) {
        $categorias[] = $row;
    }
}
// Verificar si se proporciona un ID de producto válido
if (isset($_GET['id_producto'])) {
    $producto_id = $_GET['id_producto'];

    // Consultar información del producto específico
    $sql_detalle_producto = "SELECT * FROM productos WHERE id_producto = $producto_id";
    $resultado_detalle_producto = mysqli_query($conn, $sql_detalle_producto);
    if ($resultado_detalle_producto && mysqli_num_rows($resultado_detalle_producto) > 0) {
        $detalle_producto = mysqli_fetch_assoc($resultado_detalle_producto);
        // También puedes realizar consultas adicionales si necesitas más información relacionada con el producto.
    } else {
        // Redirigir o manejar el caso en que no se encuentre el producto
        header("Location: panel_usuario.php");
        exit();
    }
} else {
    // Redirigir o manejar el caso en que no se proporciona un ID de producto
    header("Location: panel_usuario.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <h1 class="display-4 text-center">Detalles del Producto</h1>
        <a href="panel_usuario.php" class="text-primary d-block text-center mb-4">Volver a la lista de productos</a>

        <?php if (!empty($detalle_producto)): ?>
            <div class="card mx-auto" style="max-width: 600px;">
                <div class="card-body">
                    <h2 class="card-title h1 text-center mb-4">
                        <?php echo $detalle_producto['nombre']; ?>
                    </h2>
                    <img src="Producto/<?= $detalle_producto['imagen']; ?>" alt='Producto'
                        class="img-fluid mx-auto mb-3 d-block">
                    <p class="card-text lead mb-4 text-center">
                        <strong>Descripción:</strong>
                        <?php echo $detalle_producto['descripcion']; ?>
                    </p>
                    <p class="card-text lead mb-4 text-center">
                        <strong>Precio:</strong> $
                        <?php echo $detalle_producto['precio']; ?>
                    </p>
                    <p class="card-text lead mb-4 text-center">
                        <strong>Stock:</strong>
                        <?php echo $detalle_producto['stock']; ?>
                    </p>


                    <!-- Puedes agregar más detalles según sea necesario -->
                </div>
            </div>
        <?php else: ?>
            <p class="mt-3 lead mb-4 text-center">El producto no existe o no se ha especificado un ID de producto válido.
            </p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>