<?php
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $id_producto = $_GET["id"];

    // Llamar al procedimiento almacenado para eliminar el producto
    $query = "CALL sp_EliminarProducto($id_producto)";

    if ($conn->query($query) === TRUE) {
        header("Location: indexProductos.php"); // Redireccionar después de eliminar
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Eliminar Producto</title>
</head>

<body>

    <h1>Eliminar Producto</h1>

    <p>¿Estás seguro de que deseas eliminar este producto?</p>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
        <input type="submit" value="Eliminar">
        <a href="indexProductos.php">Cancelar</a>
    </form>

</body>

</html>