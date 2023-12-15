const agregarAlCarrito = (id_producto) => {
    // Realizar la solicitud AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "agregar_al_carrito.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            const response = JSON.parse(xhr.responseText);

            // Actualizar la interfaz según la respuesta del servidor
            if (response.success) {
                // Alerta opcional
                // alert(response.message);

                // Actualizar el precio total en tu interfaz
                const precioTotalElement = document.getElementById("precio-total");
                precioTotalElement.innerText = '$' + response.total_price.toFixed(2);

                // Recargar la página para reflejar los cambios
                location.reload();
            } else {
                alert(response.message);
            }
        }
    };

    // Enviar la solicitud con los datos del producto
    xhr.send("id_producto=" + id_producto + "&action=agregar");
};




const quitarDelCarrito = (id_producto) => {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "quitar_del_carrito.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            const response = JSON.parse(xhr.responseText);

            // Actualizar la interfaz según la respuesta del servidor
            if (response.success) {
                // Recargar la página después de quitar del carrito
                location.reload();
            } else {
                alert(response.message);
            }
        }
    };

    xhr.send("id_producto=" + id_producto + "&action=quitar");
};

const cargarProductosCarrito = () => {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "cargar_carrito.php", true);
    xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            const carritoContainer = document.getElementById("productos-carrito");
            carritoContainer.innerHTML = xhr.responseText;
        }
    };
    xhr.send();
};

window.onload = cargarProductosCarrito;
