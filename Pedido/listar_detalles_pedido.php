<?php
include("../db.php");

if (isset($_GET['id_pedido'])) {
    $id_pedido = $_GET['id_pedido'];

    $query = "CALL sp_ListarDetallesPedido($id_pedido)";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        echo "<h1>Detalles del Pedido</h1>";

        echo "<table border='1'>
                <tr>
                    <th>ID Detalle</th>
                    <th>Nombre del Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['id_detalle'] . "</td>
                    <td>" . $row['nombre_producto'] . "</td>
                    <td>" . $row['cantidad'] . "</td>
                    <td>" . $row['precio_unitario'] . "</td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "No se encontraron detalles para el pedido con ID $id_pedido.";
    }
} else {
    echo "ID de pedido no proporcionado.";
}

$conn->close();
?>