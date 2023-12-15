<?php
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST["id_producto"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $id_categoria = $_POST["id_categoria"];
    $nombre_categoria = $_POST["nombre_categoria"];
    $nombre_sin_espacios = strstr($nombre, ' ', true); // Esto obtiene el texto antes del primer espacio

    // Verificar si el nombre contiene espacios
    if ($nombre_sin_espacios === false) {
        // Si no hay espacios, usa el nombre completo
        $nombre_sin_espacios = $nombre;
    }

    $target_dir = "uploads/";
    $uploadOk = 1;

    if ($_FILES["imagen"]["name"] != "") {
        $imageFileType = strtolower(pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION));
        $imagen_path = $target_dir . "producto" . $nombre_sin_espacios . "." . $imageFileType;
        $check = getimagesize($_FILES["imagen"]["tmp_name"]);

        if ($check === false) {
            echo "El archivo no es una imagen.";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Lo siento, solo se permiten archivos JPG, JPEG y PNG.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1 && move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen_path) === false) {
            echo "Hubo un error al subir la imagen.";
            $imagen_path = null;
        }
    } else {
        // Si no se selecciona una nueva imagen, utiliza la URL de la imagen actual
        $imagen_actual = $_POST["imagen_actual"];
    }

    // Evitar la inyección de SQL utilizando declaraciones preparadas
    $query = $conn->prepare("CALL sp_ModificarProducto(?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("dssdiis", $id_producto, $nombre, $descripcion, $precio, $stock, $id_categoria, $imagen_path);

    if ($query->execute()) {
        header("Location: indexProductos.php");
        exit();
    } else {
        echo "Error: " . $query->error;
    }
}

if (isset($_GET["id"])) {
    $id_producto = $_GET["id"];
    $query_producto = "SELECT * FROM productos WHERE id_producto = $id_producto";
    $result_producto = $conn->query($query_producto);

    if ($result_producto->num_rows == 1) {
        $row_producto = $result_producto->fetch_assoc();
        $nombre = $row_producto["nombre"];
        $descripcion = $row_producto["descripcion"];
        $precio = $row_producto["precio"];
        $stock = $row_producto["stock"];
        $id_categoria = $row_producto["id_categoria"];
        $imagen_actual = $row_producto["imagen"]; // Asegúrate de que el nombre de la columna sea correcto
    } else {
        echo "No se encontró un producto con el ID proporcionado.";
    }

    $query_categorias = "SELECT id_categoria, nombre FROM categorias";
    $result_categorias = $conn->query($query_categorias);

    if ($result_categorias && $result_categorias->num_rows > 0) {
        while ($row_categoria = $result_categorias->fetch_assoc()) {
            $categorias[$row_categoria['id_categoria']] = $row_categoria['nombre'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Modificar Producto</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h1 class="mb-4">Modificar Producto</h1>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_producto" value="<?php echo $id_producto; ?>">
            <input type="hidden" name="imagen_actual" value="<?php echo $imagen_actual; ?>">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" value="<?php echo $nombre; ?>">
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" class="form-control" name="descripcion" value="<?php echo $descripcion; ?>">
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio:</label>
                <input type="text" class="form-control" name="precio" value="<?php echo $precio; ?>">
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock:</label>
                <input type="text" class="form-control" name="stock" value="<?php echo $stock; ?>">
            </div>

            <div class="mb-3">
                <label for="id_categoria" class="form-label">Categoría:</label>
                <select class="form-select" name="id_categoria">
                    <?php
                    foreach ($categorias as $id => $nombre_categoria) {
                        echo "<option value='$id' " . ($id_categoria == $id ? "selected" : "") . ">$nombre_categoria</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <input type="file" class="form-control" name="imagen">
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="javascript:history.go(-1);" class=" btn btn-secondary">Volver </a>
        </form>

    </div>

    <!-- Bootstrap JS and Popper.js, required for Bootstrap components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>