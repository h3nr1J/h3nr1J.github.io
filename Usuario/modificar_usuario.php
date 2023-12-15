<?php
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST["id_usuario"];
    $nombre_usuario = $_POST["nombre_usuario"];
    $contrasena = $_POST["contrasena"];
    $correo_electronico = $_POST["correo_electronico"];
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $direccion = $_POST["direccion"];
    $ciudad = $_POST["ciudad"];
    $pais = $_POST["pais"];

    // Llamar al procedimiento almacenado para modificar el usuario
    $query = "CALL sp_ModificarUsuario($id_usuario, '$nombre_usuario', '$contrasena', '$correo_electronico', '$nombre', '$apellidos', '$direccion', '$ciudad', '$pais')";

    if ($conn->query($query) === TRUE) {
        header("Location: indexUsuario.php"); // Redireccionar después de modificar
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $conn->close();
} elseif (isset($_GET["id"])) {
    $id_usuario = $_GET["id"];
    $query = "SELECT * FROM usuarios WHERE id_usuario = $id_usuario";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $nombre_usuario = $row["nombre_usuario"];
        $contrasena = $row["contrasena"];
        $correo_electronico = $row["correo_electronico"];
        $nombre = $row["nombre"];
        $apellidos = $row["apellidos"];
        $direccion = $row["direccion"];
        $ciudad = $row["ciudad"];
        $pais = $row["pais"];
    } else {
        echo "No se encontró un usuario con el ID proporcionado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="container mt-5">

    <h1 class="mb-4">Modificar Usuario</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">

        <div class="mb-3">
            <label for="nombre_usuario" class="form-label">Nombre de Usuario:</label>
            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario"
                value="<?php echo $nombre_usuario; ?>">
        </div>

        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena"
                value="<?php echo $contrasena; ?>">
        </div>

        <div class="mb-3">
            <label for="correo_electronico" class="form-label">Correo Electrónico:</label>
            <input type="text" class="form-control" id="correo_electronico" name="correo_electronico"
                value="<?php echo $correo_electronico; ?>">
        </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
        </div>

        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos:</label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $apellidos; ?>">
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección:</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $direccion; ?>">
        </div>

        <div class="mb-3">
            <label for="ciudad" class="form-label">Ciudad:</label>
            <input type="text" class="form-control" id="ciudad" name="ciudad" value="<?php echo $ciudad; ?>">
        </div>

        <div class="mb-3">
            <label for="pais" class="form-label">País:</label>
            <input type="text" class="form-control" id="pais" name="pais" value="<?php echo $pais; ?>">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>

    <a href="javascript:history.go(-1);" class="btn btn-secondary mt-3">Volver atras</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-h2EQsMrWZ9q7UgiEqDs7hIwb9U7V/ksn5+jHqUpnMDDwK5PmAX1t7xj2a80vHtfs"
        crossorigin="anonymous"></script>
</body>

</html>

<?php
$conn->close();
?>