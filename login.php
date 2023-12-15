<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db.php');

    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    // Verificar en la tabla de usuarios
    $sql_usuarios = "SELECT * FROM usuarios WHERE nombre_usuario='$nombre_usuario' AND contrasena='$contrasena' LIMIT 1";
    $resultado_usuarios = mysqli_query($conn, $sql_usuarios);

    // Verificar en la tabla de administradores
    $sql_administradores = "SELECT * FROM administradores WHERE nombre_usuario='$nombre_usuario' AND contrasena='$contrasena' LIMIT 1";
    $resultado_administradores = mysqli_query($conn, $sql_administradores);

    if (mysqli_num_rows($resultado_usuarios) == 1) {
        $row = mysqli_fetch_assoc($resultado_usuarios);
        $_SESSION['id_usuario'] = $row['id_usuario'];
        $_SESSION['nombre_usuario'] = $row['nombre_usuario'];
        $_SESSION['rol'] = $row['rol'];
        header("Location: panel_usuario.php");
        exit();
    } elseif (mysqli_num_rows($resultado_administradores) == 1) {
        $row = mysqli_fetch_assoc($resultado_administradores);
        $_SESSION['id_administrador'] = $row['id_administrador'];
        $_SESSION['nombre_usuario'] = $row['nombre_usuario'];
        $_SESSION['rol'] = $row['rol'];
        header("Location: panel_admin.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div id="login-container">
        <h1>Iniciar Sesión</h1>

        <?php if (isset($error)): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required><br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required><br>

            <input type="submit" value="Iniciar Sesión">
        </form>
    </div>
</body>

</html>