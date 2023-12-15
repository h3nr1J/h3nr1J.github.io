<?php
include("../db.php");

$sql = "CALL sp_ListarProductos()";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Listado de Productos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h1>Listado de Productos</h1>
        <a href="crear_producto.php" class="mt-3 btn btn-primary mb-3">Agregar Producto</a>
        <a href="javascript:history.go(-1);" class=" btn btn-secondary">Volver </a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <td>" . $row['id_producto'] . "</td>
                        <td>" . $row['nombre'] . "</td>
                        <td>" . $row['descripcion'] . "</td>
                        <td>" . $row['categoria'] . "</td>
                        <td>" . $row['precio'] . "</td>
                        <td>" . $row['stock'] . "</td>
                        <td><img src='" . $row['imagen'] . "' alt='Producto' style='max-width: 100px; max-height: 100px;'></td>
                        <td>
                            <a href='eliminar_producto.php?id=" . $row['id_producto'] . "' class='btn btn-danger btn-sm'>Eliminar</a>
                            <a href='modificar_producto.php?id=" . $row['id_producto'] . "' class='btn btn-primary btn-sm'>Modificar</a>
                        </td>
                    </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No se encontraron productos.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>