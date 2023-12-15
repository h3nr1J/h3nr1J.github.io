<?php
include('db.php');
session_start();

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'Acceso no permitido'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_producto'])) {
        $id_producto = $_POST['id_producto'];

        // Buscar la posición del producto en el array de carrito
        $key = array_search($id_producto, $_SESSION['carrito']);

        // Si se encuentra, eliminar el producto del carrito
        if ($key !== false) {
            unset($_SESSION['carrito'][$key]);
            $response['success'] = true;
            $response['message'] = 'Producto eliminado del carrito';
        } else {
            $response['message'] = 'Producto no encontrado en el carrito';
        }
    } else {
        $response['message'] = 'ID de producto no proporcionado';
    }
}

echo json_encode($response);
?>