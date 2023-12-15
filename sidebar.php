<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Ecommerce</title>

    <!-- Enlace al CSS de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Tu estilo personalizado para el sidebar -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            /* Color de fondo del sidebar */
            padding-top: 20px;
        }

        .sidebar a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 18px;
            color: #d1d3d8;
            /* Color del texto del sidebar */
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            color: #fff;
            /* Cambia el color del texto al pasar el ratón */
        }

        .sidebar hr {
            background-color: #868e96;
            margin: 15px 0;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>

</head>

<body>

    <!-- Sidebar -->
    <div class="d-flex flex-column p-3 text-white bg-dark sidebar">
        <div class="text-center">
            <!-- Aquí puedes agregar tu logo o información adicional -->
        </div>
        <hr>

        <a href="Admin/index_admin.php" class="text-white">Administradores</a>
        <a href="Usuario/indexUsuario.php" class="text-white">Usuarios</a>
        <a href="Pedido/indexPedido.php" class="text-white">Pedidos</a>
        <a href="Categoria/indexCategoria.php" class="text-white">Categorias</a>
        <a href="Producto/indexProductos.php" class="text-white">Productos</a>
        <a href="DetalleEnvio/indexEnvio.php" class="text-white">Detalle Envios</a>
        <button onclick="cerrarSesion()">Cerrar Sesión</button>



    </div>



    <!-- Scripts de Bootstrap y tus scripts personalizados -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-cC5FJFZlSSl3uSSaGE8E+yp3zOeNX0q33xhubH1wj9BwqSmKbSSUnQlmh/jooCp"
        crossorigin="anonymous"></script>

    <!-- Tu script personalizado -->
    <script>
        function cerrarSesion() {
            // Realizar una solicitud al servidor para cerrar la sesión
            // Puedes usar AJAX aquí si no quieres recargar toda la página

            // Redirigir a la página de inicio de sesión después de cerrar la sesión
            window.location.href = 'logout.php';
        }
    </script>
</body>

</html>