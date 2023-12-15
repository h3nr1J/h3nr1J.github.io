<?php
include("../db.php");

if (isset($_GET["id"])) {
    $id_usuario = $_GET["id"];

    // Llamar al procedimiento almacenado para eliminar el usuario
    $query = "CALL sp_EliminarUsuario($id_usuario)";

    if ($conn->query($query) === TRUE) {
        header("Location: indexUsuario.php"); // Redireccionar después de eliminar
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "ID de usuario no proporcionado.";
}
?>