<?php
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $envio_id = $_GET["id"];

    // Llamar al procedimiento almacenado para eliminar el detalle de envío
    $query = "CALL sp_EliminarDetalleEnvio($envio_id)";

    if ($conn->query($query) === TRUE) {
        header("Location: indexEnvio.php"); // Redireccionar después de eliminar
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Eliminar Detalle de Envío</title>
</head>

<body>

    <h1>Eliminar Detalle de Envío</h1>

    <p>¿Estás seguro de que deseas eliminar este detalle de envío?</p>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
        <input type="submit" value="Eliminar">
        <a href="indexDetallesEnvio.php">Cancelar</a>
    </form>

</body>

</html>