<?php
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST["id_usuario"];
    $fecha = $_POST["fecha"];
    $total = $_POST["total"];

    // Llamar al procedimiento almacenado
    $query = "CALL sp_CrearPedido('$id_usuario', '$fecha', '$total')";

    if ($conn->query($query) === TRUE) {
        header("Location: indexPedido.php"); // Redireccionar después de agregar
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Pedido</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="container mt-5">

    <h1>Agregar Pedido</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="mb-3">
            <label for="id_usuario" class="form-label">ID Usuario:</label>
            <select name="id_usuario" class="form-select">
                <?php
                // Obtener la lista de usuarios de tu base de datos
                $query = "SELECT id_usuario, nombre_usuario FROM usuarios";
                $result = $conn->query($query);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id_usuario'] . "'>" . $row['nombre_usuario'] . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>No hay usuarios disponibles</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha:</label>
            <input type="date" name="fecha" class="form-control">
        </div>

        <div class="mb-3">
            <label for="total" class="form-label">Total:</label>
            <input type="text" name="total" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>

    <a href="javascript:history.go(-1);" class="btn btn-secondary mt-3">Volver </a>

</body>

</html>