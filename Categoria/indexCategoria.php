<?php
include("../db.php");

$sql = "SELECT * FROM categorias";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Categorías</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-5">
        <h1 class="mb-4">Listado de Categorías</h1>
        <a href="crear_categoria.php" class="btn btn-success mb-4">Agregar Categoría</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Categoría</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <td>" . $row['id_categoria'] . "</td>
                        <td>" . $row['nombre'] . "</td>
                        <td>" . $row['descripcion'] . "</td>
                        <td>
                            <a href='eliminar_categoria.php?id=" . $row['id_categoria'] . "' class='btn btn-danger'>Eliminar</a>
                            <a href='modificar_categoria.php?id=" . $row['id_categoria'] . "' class='btn btn-primary'>Modificar</a>
                        </td>
                      </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No se encontraron categorías.</td></tr>";
                }
                ?>

            </tbody>
        </table>
        <a href="javascript:history.go(-1);" class="btn btn-secondary">Volver al Panel de Administrador</a>

    </div>

</body>

</html>

<?php
$conn->close();
?>