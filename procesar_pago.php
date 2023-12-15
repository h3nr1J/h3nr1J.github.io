<!-- procesar_pago.php -->

<?php
// Supongamos que ya tienes una conexión a la base de datos en tu archivo db.php
include('db.php');

// Procesar información del formulario de pago
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Aquí puedes realizar acciones adicionales según tus necesidades

    // Supongamos que también recibes el ID del producto a través del formulario
    $producto_id = $_POST["producto_id"];

    // Realizar la actualización del stock en la base de datos
    $sql_actualizar_stock = "UPDATE productos SET stock = stock - 1 WHERE id_producto = $producto_id";

    if (mysqli_query($conn, $sql_actualizar_stock)) {
        // Redirigir a la página de confirmación o a la página principal
        header("Location: panel_usuario.php.php");
        exit();
    } else {
        // Manejar el caso en que la actualización del stock falla
        echo "Error al actualizar el stock: " . mysqli_error($conn);
    }
}

// Redirigir a la página principal en caso de acceso directo a este archivo
header("Location: panel_usuario.php");
exit();
?>