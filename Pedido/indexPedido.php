<?php
include("../db.php");

$sql = "CALL sp_ListarPedidos()";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Pedidos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="container mt-5">

    <h1 class="mb-4">Listado de Pedidos</h1>

    <a href="crear_pedido.php" class="btn btn-primary">Agregar Pedido</a>
    <a href='crear_detalle.php?id_pedido=$id_pedido' class="btn btn-success">Agregar Detalle</a>
    <br><br>

    <table class="table">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Nombre de Usuario</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . $row['id_pedido'] . "</td>
                        <td>" . $row['nombre_usuario'] . "</td>
                        <td>" . $row['fecha'] . "</td>
                        <td>" . $row['total'] . "</td>
                        <td>
                            <a href='eliminar_pedido.php?id=" . $row['id_pedido'] . "' class='btn btn-danger'>Eliminar</a>
                            <a href='modificar_pedido.php?id=" . $row['id_pedido'] . "' class='btn btn-warning'>Modificar</a>
                            <a href='indexDetalle.php?id_pedido=" . $row['id_pedido'] . "' class='btn btn-info'>Ver Detalles</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No se encontraron pedidos.</td></tr>";
            }
            ?>

        </tbody>
    </table>
    <a href="javascript:history.go(-1);" class="btn btn-secondary mt-3">Volver </a>


</body>

</html>

<?php
$conn->close();
?>