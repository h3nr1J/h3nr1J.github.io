<?php
session_start();

// Supongamos que ya tienes una conexión a la base de datos en tu archivo db.php
include('db.php');

// Verificar si existe una sesión de carrito, si no, crear una nueva
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Obtener los detalles de los productos en el carrito
$productos_en_carrito = [];
foreach ($_SESSION['carrito'] as $id_producto) {
    $sql_producto = "SELECT * FROM productos WHERE id_producto = $id_producto";
    $resultado_producto = mysqli_query($conn, $sql_producto);
    if ($resultado_producto && mysqli_num_rows($resultado_producto) > 0) {
        $producto = mysqli_fetch_assoc($resultado_producto);
        $productos_en_carrito[] = $producto;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="index.css">
    <!-- Agrega tu estilo específico para la página del carrito si es necesario -->
</head>

<body>
    <div class="content">
        <h1>Carrito de Compras</h1>
        <a href="logout.php">Cerrar Sesión</a>

        <h2>Productos en el Carrito</h2>
        <?php if (empty($productos_en_carrito)): ?>
            <p>El carrito está vacío.</p>
        <?php else: ?>
            <div class="product-cards">
                <?php foreach ($productos_en_carrito as $producto): ?>
                    <div class="product-card">
                        <!-- Muestra los detalles del producto en el carrito -->
                        <img src="Uploads/<?php echo $producto['imagen']; ?>" alt="Producto" class="product-image">
                        <h3>
                            <?php echo $producto['nombre']; ?>
                        </h3>
                        <p>
                            <?php echo $producto['descripcion']; ?>
                        </p>
                        <p>Precio: $
                            <?php echo $producto['precio']; ?>
                        </p>
                        <!-- Puedes agregar más detalles según tus necesidades -->
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>