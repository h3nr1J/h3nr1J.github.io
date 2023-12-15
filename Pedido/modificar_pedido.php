<?php
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pedido = $_POST["id_pedido"];
    $id_usuario = $_POST["id_usuario"];
    $fecha = $_POST["fecha"];
    $total = $_POST["total"];

    // Llamar al procedimiento almacenado para modificar el pedido
    $query = "CALL sp_ModificarPedido($id_pedido, '$id_usuario', '$fecha', '$total')";

    if ($conn->query($query) === TRUE) {
        header("Location: indexPedido.php"); // Redireccionar después de modificar
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $conn->close();
} elseif (isset($_GET["id"])) {
    $id_pedido = $_GET["id"];
    $query = "SELECT * FROM pedidos WHERE id_pedido = $id_pedido";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $id_usuario = $row["id_usuario"];
        $fecha = $row["fecha"];
        $total = $row["total"];
    } else {
        echo "No se encontró un pedido con el ID proporcionado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Pedido</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="container mt-5">

    <h1>Modificar Pedido</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="id_pedido" value="<?php echo $id_pedido; ?>">

        <div class="mb-3">
            <label for="id_usuario" class="form-label">ID Usuario:</label>
            <select name="id_usuario" class="form-select">
                <?php
                // Obtener la lista de usuarios de tu base de datos
                $query = "SELECT id_usuario, nombre_usuario FROM usuarios";
                $result = $conn->query($query);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Seleccionar el usuario actualmente asociado al pedido
                        $selected = ($id_usuario == $row['id_usuario']) ? "selected" : "";
                        echo "<option value='" . $row['id_usuario'] . "' $selected>" . $row['nombre_usuario'] . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>No hay usuarios disponibles</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha:</label>
            <input type="date" name="fecha" value="<?php echo $fecha; ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label for="total" class="form-label">Total:</label>
            <input type="text" name="total" value="<?php echo $total; ?>" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
    <a href="javascript:history.go(-1);" class="btn btn-secondary mt-3">Volver </a>

</body>

</html>