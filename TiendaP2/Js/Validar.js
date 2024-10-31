document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const usuarioInput = document.getElementById('usuario');
    const passwordInput = document.getElementById('password');
    const rememberCheckbox = document.getElementById('remember');

    // Cargar credenciales guardadas si existen
    if (localStorage.getItem('rememberedUser')) {
        usuarioInput.value = localStorage.getItem('rememberedUser');
        rememberCheckbox.checked = true;
    }

    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!usuarioInput.value || !passwordInput.value) {
            mostrarAlerta('Por favor, completa todos los campos', 'error');
            return;
        }

        // Enviar datos a Datos.php
        fetch('Datos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `usuario=${encodeURIComponent(usuarioInput.value)}&password=${encodeURIComponent(passwordInput.value)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Manejar recordar usuario
                if (rememberCheckbox.checked) {
                    localStorage.setItem('rememberedUser', usuarioInput.value);
                } else {
                    localStorage.removeItem('rememberedUser');
                }
                mostrarAlerta('Inicio de sesión exitoso', 'success');
                // Redirigir a productos.php
                window.location.href = 'productos.php';
            } else {
                mostrarAlerta(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error); // Agrega esta línea para ver el error en la consola
            mostrarAlerta('Ocurrió un error en el inicio de sesión', 'error');
        });
    });

    function mostrarAlerta(mensaje, tipo) {
        const alertaExistente = document.querySelector('.custom-alert');
        if (alertaExistente) {
            alertaExistente.remove();
        }

        const alerta = document.createElement('div');
        alerta.classList.add('custom-alert', `alert-${tipo}`);
        alerta.textContent = mensaje;

        alerta.style.position = 'fixed';
        alerta.style.top = '20px';
        alerta.style.left = '50%';
        alerta.style.transform = 'translateX(-50%)';
        alerta.style.padding = '10px 20px';
        alerta.style.borderRadius = '5px';
        alerta.style.zIndex = '1000';

        if (tipo === 'error') {
            alerta.style.backgroundColor = '#f8d7da';
            alerta.style.color = '#721c24';
            alerta.style.border = '1px solid #f5c6cb';
        } else {
            alerta.style.backgroundColor = '#d4edda';
            alerta.style.color = '#155724';
            alerta.style.border = '1px solid #c3e6cb';
        }

        document.body.appendChild(alerta);

        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }

    // Verificar si la sesión está activa al cargar la página de productos
    function verificarSesion() {
        fetch('verificar_sesion.php')
        .then(response => {
            if (!response.ok) {
                // Redirigir a la página de inicio de sesión si no hay sesión
                window.location.href = 'index.html';
            }
        })
        .catch(error => console.error('Error al verificar la sesión:', error));
    }

    // Ejecutar la verificación de sesión solo en productos.php
    if (window.location.pathname.includes('productos.php')) {
        verificarSesion();
    }

    // Redirección automática cada 60 segundos para verificar la sesión
    setInterval(verificarSesion, 60000); // Verifica cada 60 segundos
});
