<?php
include("../db.php");

if (isset($_GET["id"])) {
    $id_administrador = $_GET["id"];

    // Llamar al procedimiento almacenado para eliminar el administrador
    $query = "CALL sp_EliminarAdministrador($id_administrador)";

    if ($conn->query($query) === TRUE) {
        header("Location: index_admin.php"); // Redireccionar después de eliminar
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "ID de administrador no proporcionado.";
}
?>