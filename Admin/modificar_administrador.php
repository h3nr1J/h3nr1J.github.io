<?php
include("../db.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_administrador = $_POST["id_administrador"];
    $nombre_usuario = $_POST["nombre_usuario"];
    $contrasena = $_POST["contrasena"];
    $correo_electronico = $_POST["correo_electronico"];
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];

    $query = "CALL sp_ModificarAdministrador($id_administrador, '$nombre_usuario', '$contrasena', '$correo_electronico', '$nombre', '$apellidos')";

    if ($conn->query($query) === TRUE) {
        header("Location: index_admin.php"); // Redireccionar después de modificar
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $conn->close();
} elseif (isset($_GET["id"])) {
    $id_administrador = $_GET["id"];
    $query = "SELECT * FROM administradores WHERE id_administrador = $id_administrador";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $nombre_usuario = $row["nombre_usuario"];
        $contrasena = $row["contrasena"];
        $correo_electronico = $row["correo_electronico"];
        $nombre = $row["nombre"];
        $apellidos = $row["apellidos"];
    } else {
        echo "No se encontró un administrador con el ID proporcionado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Administrador</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h1 class="text-center">Modificar Administrador</h1>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="id_administrador" value="<?php echo $id_administrador; ?>">

            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario:</label>
                <input type="text" class="form-control" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" class="form-control" name="contrasena" value="<?php echo $contrasena; ?>">
            </div>

            <div class="form-group">
                <label for="correo_electronico">Correo Electrónico:</label>
                <input type="text" class="form-control" name="correo_electronico"
                    value="<?php echo $correo_electronico; ?>">
            </div>

            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" name="nombre" value="<?php echo $nombre; ?>">
            </div>

            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" class="form-control" name="apellidos" value="<?php echo $apellidos; ?>">
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>