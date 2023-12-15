<?php
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST["nombre_usuario"];
    $contrasena = $_POST["contrasena"];
    $correo_electronico = $_POST["correo_electronico"];
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $direccion = $_POST["direccion"];
    $ciudad = $_POST["ciudad"];
    $pais = $_POST["pais"];

    // Llamar al procedimiento almacenado
    $query = "CALL sp_CrearUsuario('$nombre_usuario', '$contrasena', '$correo_electronico', '$nombre', '$apellidos', '$direccion', '$ciudad', '$pais')";

    if ($conn->query($query) === TRUE) {
        header("Location: indexUsuario.php"); // Redireccionar después de agregar
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="container mt-5">

    <h1 class="mb-4">Agregar Usuario</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="mb-3">
            <label for="nombre_usuario" class="form-label">Nombre de Usuario:</label>
            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario">
        </div>

        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena">
        </div>

        <div class="mb-3">
            <label for="correo_electronico" class="form-label">Correo Electrónico:</label>
            <input type="text" class="form-control" id="correo_electronico" name="correo_electronico">
        </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre">
        </div>

        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos:</label>
            <input type="text" class="form-control" id="apellidos" name="apellidos">
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección:</label>
            <input type="text" class="form-control" id="direccion" name="direccion">
        </div>

        <div class="mb-3">
            <label for="ciudad" class="form-label">Ciudad:</label>
            <input type="text" class="form-control" id="ciudad" name="ciudad">
        </div>

        <div class="mb-3">
            <label for="pais" class="form-label">País:</label>
            <input type="text" class="form-control" id="pais" name="pais">
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
    <a href="javascript:history.go(-1);" class="btn btn-secondary mt-3">Volver atras</a>

</body>

</html>

<?php
$conn->close();
?>