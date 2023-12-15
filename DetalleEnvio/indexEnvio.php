<?php
include("../db.php");

$sql = "SELECT * FROM detalles_envio";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Listado de Detalles de Envío</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h1>Listado de Detalles de Envío</h1>
        <a href="crear_detalle_envio.php" class="btn btn-primary">Agregar Detalle de Envío</a>
        <a href="javascript:history.go(-1);" class=" btn btn-secondary">Volver </a>

        <br><br>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Envío</th>
                    <th>ID Pedido</th>
                    <th>Nombre Cliente</th>
                    <th>Dirección Envío</th>
                    <th>Ciudad Envío</th>
                    <th>País Envío</th>
                    <th>Código Postal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <td>" . $row['id_envio'] . "</td>
                        <td>" . $row['id_pedido'] . "</td>
                        <td>" . $row['nombre_cliente'] . "</td>
                        <td>" . $row['direccion_envio'] . "</td>
                        <td>" . $row['ciudad_envio'] . "</td>
                        <td>" . $row['pais_envio'] . "</td>
                        <td>" . $row['codigo_postal'] . "</td>
                        <td>
                            <a href='eliminar_detalle_envio.php?id=" . $row['id_envio'] . "' class='btn btn-danger'>Eliminar</a>
                            <a href='modificar_detalle_envio.php?id=" . $row['id_envio'] . "' class='btn btn-primary'>Modificar</a>
                        </td>
                    </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No se encontraron detalles de envío.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

    <!-- Bootstrap JS and Popper.js, required for Bootstrap components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>