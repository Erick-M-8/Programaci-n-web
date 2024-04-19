function peticion(url) {
    var inicio = performance.now(); // Iniciar el contador de tiempo

    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            respuesta(this, url);
            var fin = performance.now(); // Finalizar el contador de tiempo
            var tiempoRespuesta = fin - inicio; // Calcular el tiempo de respuesta
            console.log(`Tiempo de respuesta: ${tiempoRespuesta.toFixed(6)} ms`);
        }
    };
    ajax.open("GET", url, true);
    ajax.send();
}

function respuesta(url) {
    if (/delete/i.test(url)) {
        window.location.href = 'index.php';
    } else if (/detalles/i.test(url)) {
        var html = ajax.responseText;
        document.getElementById("area").innerHTML = html;
    } else if (/eliminar_usuario/i.test(url)) {
        window.location.href = 'Admin_usuarios.php';
    }
}