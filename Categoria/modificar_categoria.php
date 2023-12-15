<?php
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_categoria = $_POST["id_categoria"];
    $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
    $descripcion = mysqli_real_escape_string($conn, $_POST["descripcion"]);

    // Validar los datos antes de ejecutar la consulta
    if (empty($nombre) || empty($descripcion)) {
        echo "Nombre y descripción son campos obligatorios.";
    } else {
        // Llamar al procedimiento almacenado para modificar la categoría
        $query = "CALL sp_ModificarCategoria($id_categoria, '$nombre', '$descripcion')";

        if ($conn->query($query) === TRUE) {
            header("Location: indexCategoria.php"); // Redireccionar después de modificar
            exit();
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    }

    $conn->close();
} elseif (isset($_GET["id"])) {
    $id_categoria = $_GET["id"];
    $query = "SELECT * FROM categorias WHERE id_categoria = $id_categoria";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $nombre = $row["nombre"];
        $descripcion = $row["descripcion"];
    } else {
        echo "No se encontró una categoría con el ID proporcionado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Modificar Categoría</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h1 class="mb-4">Modificar Categoría</h1>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="id_categoria" value="<?php echo $id_categoria; ?>">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" name="nombre" value="<?php echo $nombre; ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <input type="text" class="form-control" name="descripcion" value="<?php echo $descripcion; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
        <a href="javascript:history.go(-1);" class="mt-2 btn btn-secondary">Volver </a>

    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>