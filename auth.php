<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php"); // Redirige a la página de inicio de sesión si el usuario no está autenticado
    exit();
}

if (isset($_POST["cerrar_sesion"])) {
    header("Location: logout.php"); // Redirige a la página de cierre de sesión
    exit();
}
?>