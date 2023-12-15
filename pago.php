<?php
// Incluir archivo de conexión a la base de datos
include('db.php');

// Iniciar sesión si aún no está iniciada
session_start();

// Verificar si hay productos en el carrito
if (!empty($_SESSION['carrito'])) {
    // Iterar sobre los productos en el carrito
    foreach ($_SESSION['carrito'] as $id_producto_carrito) {
        // Actualizar el stock en la base de datos
        $sql_actualizar_stock = "UPDATE productos SET stock = stock - 1 WHERE id_producto = $id_producto_carrito";

        if (!mysqli_query($conn, $sql_actualizar_stock)) {
            // Manejar el caso en que la actualización del stock falla
            echo "Error al actualizar el stock: " . mysqli_error($conn);
            exit();
        }
    }

    // Limpiar el carrito después de procesar el pago
    unset($_SESSION['carrito']);
    unset($_SESSION['carrito_cantidades']);

    // Redirigir a la página de confirmación o a la página principal
    header("Location: confirmacion_pago.php");
    exit();
} else {
    // Redirigir a la página principal en caso de acceso directo a este archivo sin productos en el carrito
    header("Location: index.php");
    exit();
}
?>