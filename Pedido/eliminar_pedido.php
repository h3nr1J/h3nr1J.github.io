<?php
include("../db.php");


if (isset($_GET["id"])) {
    $id_pedido = $_GET["id"];

    // Llamar al procedimiento almacenado para eliminar el pedido
    $query = "CALL sp_EliminarPedido($id_pedido)";

    if ($conn->query($query) === TRUE) {
        header("Location: indexPedido.php"); // Redireccionar después de eliminar
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $conn->close();
}
?>