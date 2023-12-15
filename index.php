<?php
include('db.php');
session_start();


$categoria_seleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : null;

// Añadir la cláusula WHERE si se selecciona una categoría
$where_clause = '';
if ($categoria_seleccionada !== null && $categoria_seleccionada !== 'null') {
    // Si no es "Mostrar todas", construir la cláusula WHERE
    $where_clause = " WHERE id_categoria = $categoria_seleccionada";
}
// Añadir la cláusula LIMIT y OFFSET para paginación
$por_pagina = 8;
$pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $por_pagina;

// Modificar la consulta de productos
$sql_productos = "SELECT * FROM productos" . $where_clause;
$sql_productos .= " LIMIT $por_pagina OFFSET $offset";

$resultado_productos = mysqli_query($conn, $sql_productos);

// Obtener la lista de categorías
$sql_categorias = "SELECT * FROM categorias";
$resultado_categorias = mysqli_query($conn, $sql_categorias);

$categorias = [];
if (mysqli_num_rows($resultado_categorias) > 0) {
    while ($row = mysqli_fetch_assoc($resultado_categorias)) {
        $categorias[] = $row;
    }
}
// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
    $_SESSION['carrito_cantidades'] = [];
}

// Procesar la solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_producto'])) {
        $id_producto = $_POST['id_producto'];

        // Determinar la acción (agregar o quitar)
        $action = isset($_POST['action']) ? $_POST['action'] : '';

        if ($action === 'agregar') {
            // Aquí coloca tu lógica para agregar el producto al carrito
            $result = agregarAlCarrito($id_producto);

            if ($result) {
                // Actualizar la respuesta si la operación fue exitosa
                $response['success'] = true;
                $response['message'] = 'Producto agregado al carrito exitosamente.';
            } else {
                $response['message'] = 'Error al agregar el producto al carrito.';
            }
        } elseif ($action === 'quitar') {
            // Lógica para quitar del carrito
            $result = quitarDelCarrito($id_producto);

            if ($result) {
                // Actualizar la respuesta si la operación fue exitosa
                $response['success'] = true;
                $response['message'] = 'Producto eliminado del carrito exitosamente.';
            } else {
                $response['message'] = 'Error al quitar el producto del carrito.';
            }
        }
    } else {
        $response['message'] = 'ID de producto no proporcionado.';
    }
}

// Obtener la lista de productos
$sql_productos = "SELECT * FROM productos" . $where_clause;
$resultado_productos = mysqli_query($conn, $sql_productos);

$productos = [];
if (mysqli_num_rows($resultado_productos) > 0) {
    while ($row = mysqli_fetch_assoc($resultado_productos)) {
        $productos[] = $row;
    }
}


function agregarAlCarrito($id_producto)
{
    global $conn;

    // Obtener información sobre el producto
    $producto_info = obtenerInfoProducto($id_producto);

    // Verificar si el producto existe y tiene stock disponible
    if ($producto_info && $producto_info['stock_actual'] > 0) {
        // Verificar si el producto ya está en el carrito
        if (array_key_exists($id_producto, $_SESSION['carrito_cantidades'])) {
            // Incrementar la cantidad si el producto ya está en el carrito
            $_SESSION['carrito_cantidades'][$id_producto]++;
        } else {
            // Agregar el producto al carrito si no está presente
            $_SESSION['carrito'][] = $id_producto;
            // Inicializar la cantidad a 1 si es un nuevo producto en el carrito
            $_SESSION['carrito_cantidades'][$id_producto] = 1;
        }

        // Actualizar el stock en la base de datos
        $result = actualizarStockCarrito($id_producto, -1);

        if (!$result) {
            // Si hay un error al actualizar el stock, revertir los cambios en el carrito
            quitarDelCarrito($id_producto);
        }

        // Recargar la página después de actualizar el carrito usando JavaScript
        echo '<script>location.reload();</script>';

        return $result;
    } else {
        // Si el producto no existe o no tiene stock disponible, mostrar un mensaje de error
        $response['message'] = 'No hay stock disponible para este producto.';
        return false;
    }
}

function quitarDelCarrito($id_producto)
{
    global $conn;

    // Verificar si el producto está en el carrito
    if (in_array($id_producto, $_SESSION['carrito'])) {
        // Buscar la posición del producto en el array de carrito
        $key = array_search($id_producto, $_SESSION['carrito']);

        // Si se encuentra, eliminar el producto del carrito
        if ($key !== false) {
            // Obtener la cantidad antes de eliminar el producto del carrito
            $cantidad_anterior = $_SESSION['carrito_cantidades'][$id_producto];

            // Reducir la cantidad en 1
            $_SESSION['carrito_cantidades'][$id_producto]--;

            // Si la cantidad llega a 0, eliminar la entrada de cantidad
            if ($_SESSION['carrito_cantidades'][$id_producto] <= 0) {
                unset($_SESSION['carrito_cantidades'][$id_producto]);
                unset($_SESSION['carrito'][$key]);
            }

            // Actualizar el stock en la base de datos (revertir)
            actualizarStockCarrito($id_producto, $cantidad_anterior);

            return true; // Producto eliminado exitosamente
        }
    }

    return false; // No se encontró el producto en el carrito
}

function procesarPago()
{
    global $conn;

    // Iterar sobre los productos en el carrito
    foreach ($_SESSION['carrito'] as $id_producto_carrito) {
        // Obtener la cantidad comprada de este producto
        $cantidad_comprada = $_SESSION['carrito_cantidades'][$id_producto_carrito];

        // Restar la cantidad comprada del stock_inicial en la base de datos
        $result = actualizarStockCarrito($id_producto_carrito, $cantidad_comprada, true);

        if (!$result) {
            // Manejar el error (puede lanzar una excepción o mostrar un mensaje)
            $response['message'] = 'Error al procesar el pago.';
            return false;
        }
    }

    // Puedes limpiar el carrito después de procesar el pago
    $_SESSION['carrito'] = [];
    $_SESSION['carrito_cantidades'] = [];

    // Redirigir al usuario a una página de confirmación
    header('Location: confirmacion_pago.php');
    exit();
}

function actualizarStockCarrito($id_producto, $cantidad, $reversion = false)
{
    global $conn;

    // Determinar el signo de la actualización según si es una reversión o no
    $signo = $reversion ? '+' : '-';

    // Actualizar el stock_actual en la base de datos
    $stmt = $conn->prepare("UPDATE productos SET stock_actual = stock_actual $signo ? WHERE id_producto = ?");
    $stmt->bind_param("ii", $cantidad, $id_producto);
    $result = $stmt->execute();
    $stmt->close();

    // Obtener el nuevo stock_actual después de la actualización
    $stock_actual = obtenerStock($id_producto);

    // Imprimir mensajes de depuración
    echo "Stock_actual actualizado. ID: $id_producto, Cantidad: $cantidad, Stock_actual actual: $stock_actual, Reversión: $reversion<br>";

    return $result;
}

function obtenerInfoProducto($id_producto)
{
    global $conn;

    // Obtener información sobre el producto
    $sql_producto = "SELECT * FROM productos WHERE id_producto = $id_producto";
    $resultado_producto = mysqli_query($conn, $sql_producto);

    if ($resultado_producto && mysqli_num_rows($resultado_producto) > 0) {
        return mysqli_fetch_assoc($resultado_producto);
    }

    return false;
}

function obtenerStock($id_producto)
{
    global $conn;

    // Obtener el stock_actual del producto
    $sql_stock = "SELECT stock_actual FROM productos WHERE id_producto = $id_producto";
    $resultado_stock = mysqli_query($conn, $sql_stock);

    if ($resultado_stock && mysqli_num_rows($resultado_stock) > 0) {
        $row = mysqli_fetch_assoc($resultado_stock);
        return $row['stock_actual'];
    }

    return false;
}

function mostrarProductosCarrito()
{
    global $conn;

    $totalPrice = 0; // Inicializar el precio total

    if (!empty($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as $id_producto_carrito) {
            $sql_carrito = "SELECT * FROM productos WHERE id_producto = $id_producto_carrito";
            $resultado_carrito = mysqli_query($conn, $sql_carrito);

            if ($resultado_carrito && mysqli_num_rows($resultado_carrito) > 0) {
                $producto_carrito = mysqli_fetch_assoc($resultado_carrito);

                $cantidad = isset($_SESSION['carrito_cantidades'][$id_producto_carrito]) ? $_SESSION['carrito_cantidades'][$id_producto_carrito] : 0;

                $precioProducto = $producto_carrito['precio'] * $cantidad; // Calcular el precio de este producto

                $totalPrice += $precioProducto; // Agregar el precio de este producto al total

                $ruta_imagen_carrito = 'producto/uploads/' . ($producto_carrito['imagen'] ?? 'default.jpg');
                ?>

                <!-- Debugging output for each product -->
                <div class='debug-info'>
                    <p><strong>Producto:</strong>
                        <?php echo $producto_carrito['nombre']; ?>
                    </p>
                    <p><strong>Cantidad:</strong>
                        <?php echo $cantidad; ?>
                    </p>
                    <p><strong>Precio por unidad:</strong> S/.
                        <?php echo $producto_carrito['precio']; ?>
                    </p>
                    <p><strong>Subtotal:</strong>
                        <?php echo $precioProducto; ?>
                    </p>
                    <button class='eliminar-btn'
                        onclick="quitarDelCarrito(<?php echo $producto_carrito['id_producto']; ?>)">Eliminar</button>
                </div>
                <?php
            }
        }
    } else {
        echo '<p>El carrito está vacío.</p>';
    }

    // Debugging output for the total price
    echo "<div class='debug-info total-info'>";
    echo "<p style='font-size: 20px;'><strong>Total:</strong> S/. $totalPrice </p>";
    echo "</div>";

    // Actualizar el precio total en HTML
    echo '<script>document.getElementById("precio-total").innerText = "PEN' . number_format($totalPrice, 2) . '";</script>';
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>

<body>
    <div class="container mt-4">
        <h1 class="text-center">Tienda Online</h1>



        <div class="mt-4">
            <h5 class="mb-3">Filtrar por categoría:</h5>
            <div class="btn-group" role="group" aria-label="Filtrar por categoría">
                <a href="?categoria=null" class="btn btn-outline-secondary">Mostrar todas</a>
                <?php foreach ($categorias as $categoria): ?>
                    <a href="?categoria=<?php echo $categoria['id_categoria']; ?>" class="btn btn-outline-secondary">
                        <?php echo $categoria['nombre']; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <h2 class="mt-4">Productos Disponibles</h2>
                <div class="row row-cols-1 row-cols-md-4 g-4">
                    <?php
                    $inicio = ($pagina_actual - 1) * $por_pagina;
                    $fin = $inicio + $por_pagina - 1;

                    foreach ($productos as $indice => $producto) {
                        if ($indice >= $inicio && $indice <= $fin) {
                            ?>
                            <div class="col mb-4">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="car-image card-image mt-2 mb-2">
                                                <?php if (!empty($producto['imagen'])) { ?>
                                                    <div class="image-box">
                                                        <img src="Producto/<?= $producto['imagen']; ?>" alt='Producto'
                                                            class="img-fluid" style="max-width: 100%;">
                                                    </div>
                                                <?php } else { ?>
                                                    <p>default.jpg</p>
                                                <?php } ?>
                                            </div>

                                            <h5 class="card-title mt-2 text-truncate">
                                                <?php echo $producto['nombre']; ?>
                                            </h5>

                                            <p class="card-text card-description">
                                                <?php
                                                $descripcion_corta = substr($producto['descripcion'], 0, 53);
                                                echo $descripcion_corta;

                                                if (strlen($producto['descripcion']) > 53) {
                                                    echo '...';
                                                } else {
                                                    echo str_repeat(' ', 53 - strlen($producto['descripcion']));
                                                }
                                                ?>
                                            </p>

                                            <p class="card-text">Precio: S/.
                                                <?php echo number_format($producto['precio'], 2); ?>
                                            </p>
                                            <p class="card-text">Stock:
                                                <?php echo $producto['stock']; ?>
                                            </p>
                                        </div>

                                        <div class="text-center mt-2">
                                            <?php if ($producto['stock'] > 0): ?>
                                                <button onclick="agregarAlCarrito(<?php echo $producto['id_producto']; ?>)"
                                                    class="btn btn-success">Agregar al carrito</button>

                                                <!-- Botón "Ver detalles" -->
                                                <a href="detalle_producto.php?id_producto=<?php echo $producto['id_producto']; ?>"
                                                    class="text-info ">Ver detalles</a>

                                            <?php else: ?>
                                                <button class="btn btn-secondary" disabled>Producto agotado</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>


                <div class="pagination mt-4 d-flex justify-content-center">
                    <style>
                        /* Agrega el color verde para la paginación */

                        #custom-pagination .custom-pagination-color:focus {
                            background-color: #28a745;
                            /* Color cuando está activo o enfocado */
                            border-color: #28a745;
                            color: #fff;
                            /* Color del texto */
                        }
                    </style>
                    <nav aria-label="Page navigation">
                        <ul id="custom-pagination" class="pagination">
                            <?php
                            $total_paginas = ceil(count($productos) / $por_pagina);
                            for ($i = 1; $i <= $total_paginas; $i++) {
                                echo '<li class="page-item' . ($i == $pagina_actual ? ' active' : '') . '"><a class="page-link custom-pagination-color" href="?pagina=' . $i . '">' . $i . '</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>


            </div>

            <div class="col-md-4 d-flex justify-content-end">
                <div id="sidecart" class="root-sidecart">
                    <div class="cart-wrap">
                        <div class="cart-body">
                            <div class="cart-main">
                                <div class="group-header-container">
                                    <!-- Contenido del encabezado del carrito -->
                                    <h2>Carrito</h2>
                                </div>
                                <div class="cart-list" id="productos-carrito">
                                    <!-- Contenido de la lista de productos en el carrito -->
                                    <?php mostrarProductosCarrito(); ?>
                                </div>
                            </div>
                            <div class="cart-side">
                                <!-- Contenido del resumen del carrito y botones de acción -->
                                <div class="cart-summary">

                                    <?php if (isset($_SESSION['usuario'])): ?>
                                        <p class="text-end">Bienvenido,
                                            <?php echo $_SESSION['usuario']['nombre']; ?> | <a href="logout.php">Cerrar
                                                Sesión</a>
                                        </p>
                                    <?php else: ?>
                                        <p class="text-end"><a href="login.php" class="btn btn-outline-primary">Inicia
                                                sesión</a> o <a href="registro.php"
                                                class="btn btn-outline-secondary">Regístrate</a> para comprar.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src="script.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"></script>
</body>

</html>