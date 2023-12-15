<?php
include("../db.php");

$sql = "CALL sp_ListarUsuarios()";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="container mt-5">

    <h1 class="mb-4">Listado de Usuarios</h1>

    <a href="crear_usuario.php" class="btn btn-primary mb-4">Agregar Usuario</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Usuario</th>
                <th>Nombre de Usuario</th>
                <th>Contraseña</th>
                <th>Correo Electrónico</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Dirección</th>
                <th>Ciudad</th>
                <th>País</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['id_usuario'] . "</td>
                            <td>" . $row['nombre_usuario'] . "</td>
                            <td>" . $row['contrasena'] . "</td>
                            <td>" . $row['correo_electronico'] . "</td>
                            <td>" . $row['nombre'] . "</td>
                            <td>" . $row['apellidos'] . "</td>
                            <td>" . $row['direccion'] . "</td>
                            <td>" . $row['ciudad'] . "</td>
                            <td>" . $row['pais'] . "</td>
                            <td>
                                <a href='eliminar_usuario.php?id=" . $row['id_usuario'] . "' class='btn btn-danger'>Eliminar</a>
                                <a href='modificar_usuario.php?id=" . $row['id_usuario'] . "' class='btn btn-warning'>Modificar</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No se encontraron usuarios.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="javascript:history.go(-1);" class="btn btn-secondary">Volver al Panel de Administrador</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-h2EQsMrWZ9q7UgiEqDs7hIwb9U7V/ksn5+jHqUpnMDDwK5PmAX1t7xj2a80vHtfs"
        crossorigin="anonymous"></script>
</body>

</html>

<?php
$conn->close();
?>