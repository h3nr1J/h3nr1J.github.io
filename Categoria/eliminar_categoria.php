<?php
include("../db.php");

if (isset($_GET["id"])) {
    $id_categoria = $_GET["id"];

    // Llamar al procedimiento almacenado para eliminar la categoría
    $query = "CALL sp_EliminarCategoria($id_categoria)";

    if ($conn->query($query) === TRUE) {
        header("Location: indexCategoria.php"); // Redireccionar después de eliminar
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "No se proporcionó un ID de categoría válido.";
}
?>