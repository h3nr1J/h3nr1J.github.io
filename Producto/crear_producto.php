<?php
include("../db.php");

$imagen_path = null; // Definimos $imagen_path fuera del bloque condicional

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $id_categoria = $_POST["id_categoria"];
    $nombre_sin_espacios = strstr($nombre, ' ', true);

    if ($nombre_sin_espacios === false) {
        $nombre_sin_espacios = $nombre;
    }

    if (!empty($_FILES["imagen"]["tmp_name"])) {
        $target_dir = "uploads/";
        $imageFileType = strtolower(pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION));
        $imagen_path = $target_dir . "producto" . $nombre_sin_espacios . "." . $imageFileType;

        $uploadOk = 1;

        $check = getimagesize($_FILES["imagen"]["tmp_name"]);
        if ($check === false) {
            echo "El archivo no es una imagen.";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png") {
            echo "Lo siento, solo se permiten archivos JPG y PNG.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1 && move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen_path) === false) {
            echo "Hubo un error al subir la imagen.";
            $imagen_path = null;
        }
    }

    $query = $conn->prepare("CALL sp_CrearProducto(?, ?, ?, ?, ?, ?)");
    $query->bind_param("ssddis", $nombre, $descripcion, $precio, $stock, $id_categoria, $imagen_path);

    if ($query->execute()) {
        header("Location: indexProductos.php");
        exit();
    } else {
        echo "Error: " . $query->error;
    }
}

$query_categorias = "SELECT id_categoria, nombre FROM categorias";
$result_categorias = $conn->query($query_categorias);

if ($result_categorias && $result_categorias->num_rows > 0) {
    while ($row_categoria = $result_categorias->fetch_assoc()) {
        $categorias[$row_categoria['id_categoria']] = $row_categoria['nombre'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Agregar Producto</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h1>Agregar Producto</h1>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre">
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" class="form-control" name="descripcion">
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio:</label>
                <input type="text" class="form-control" name="precio">
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock:</label>
                <input type="text" class="form-control" name="stock">
            </div>

            <div class="mb-3">
                <label for="id_categoria" class="form-label">Categoría:</label>
                <select class="form-select" name="id_categoria">
                    <?php
                    foreach ($categorias as $id => $nombre_categoria) {
                        echo "<option value='$id'>$nombre_categoria</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <input type="file" class="form-control" name="imagen">
            </div>

            <!-- Verificamos si $imagen_path está definido antes de usarlo -->
            <?php if ($imagen_path !== null): ?>
                <img src="<?php echo $imagen_path; ?>" alt="Producto Imagen" class="img-fluid mb-3"
                    style="max-width: 300px;">
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>

    <!-- Bootstrap JS and Popper.js, required for Bootstrap components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>