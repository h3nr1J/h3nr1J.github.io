<?php
include("../db.php");

if (isset($_GET['id_detalle'])) {
    $id_detalle = $_GET['id_detalle'];

    // Llamar al procedimiento almacenado para eliminar el detalle
    $query = "CALL sp_EliminarDetallePedido($id_detalle)";

    if ($conn->query($query) === TRUE) {
        if (isset($_GET['id_pedido'])) {
            $id_pedido = $_GET['id_pedido'];
            header("Location: indexDetalle.php?id_pedido=" . $id_pedido);
            exit();
        } else {
            echo "ID de pedido no proporcionado.";
        }
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
} else {
    echo "ID de detalle no proporcionado.";
}

$conn->close();
?>