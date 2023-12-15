<?php
include('db.php');

session_start();

// Inicializar la respuesta
$response = [
    'success' => false,
    'message' => 'Hubo un error al agregar el producto al carrito.'
];

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
    $_SESSION['carrito_cantidades'] = [];
    $_SESSION['precio_total'] = 0;
}

// Procesar la solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_producto'])) {
        $id_producto = $_POST['id_producto'];

        // Aquí coloca tu lógica para agregar el producto al carrito
        agregarAlCarrito($id_producto);

        // Actualizar la respuesta si la operación fue exitosa
        $response['success'] = true;
        $response['message'] = 'Producto agregado al carrito exitosamente.';
        $response['total_price'] = $_SESSION['precio_total'];
        $response['quantity'] = $_SESSION['carrito_cantidades'][$id_producto];
        $response['product_price'] = obtenerPrecioProducto($id_producto);
    }
}

// Devolver la respuesta como JSON
echo json_encode($response);

function agregarAlCarrito($id_producto)
{
    global $conn;

    // Verificar si el producto ya está en el carrito
    if (in_array($id_producto, $_SESSION['carrito'])) {
        // Incrementar la cantidad si el producto ya está en el carrito
        $_SESSION['carrito_cantidades'][$id_producto]++;
    } else {
        // Agregar el producto al carrito si no está presente
        $_SESSION['carrito'][] = $id_producto;
        // Inicializar la cantidad a 1 si es un nuevo producto en el carrito
        $_SESSION['carrito_cantidades'][$id_producto] = 1;
    }

    // Actualizar el stock en la base de datos
    actualizarStock($id_producto, -1);

    // Actualizar el precio total
    actualizarPrecioTotal($id_producto);
    // Script de JavaScript para recargar la página
    // Redirigir a la misma página después de actualizar el carrito
    echo '<script>window.location.href = window.location.href;</script>';

    exit;
}

function obtenerPrecioProducto($id_producto)
{
    global $conn;

    $sql_producto = "SELECT precio FROM productos WHERE id_producto = $id_producto";
    $resultado_producto = mysqli_query($conn, $sql_producto);

    if ($resultado_producto && mysqli_num_rows($resultado_producto) > 0) {
        return mysqli_fetch_assoc($resultado_producto)['precio'];
    }

    return 0;
}

function actualizarStock($id_producto, $cantidad)
{
    global $conn;

    // Actualizar el stock en la base de datos
    $stmt = $conn->prepare("UPDATE productos SET stock = stock + ? WHERE id_producto = ?");
    $stmt->bind_param("ii", $cantidad, $id_producto);
    $stmt->execute();
    $stmt->close();
}

function actualizarPrecioTotal($id_producto)
{
    global $conn;

    $precio_producto = obtenerPrecioProducto($id_producto);
    $_SESSION['precio_total'] += $precio_producto;
}
?>