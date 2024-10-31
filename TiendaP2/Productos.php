<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['USER'])) {
    header('Location: index.html'); // Redirigir a la página de inicio de sesión si no está autenticado
    exit();
}

// Tiempo de inactividad permitido (1 minuto)
$inactive = 60; // 60 segundos = 1 minuto

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $inactive) {
    // Si ha pasado más de 1 minuto
    session_unset(); // Destruir las variables de sesión
    session_destroy(); // Destruir la sesión
    header('Location: index.html'); // Redirigir al login
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // Actualiza la última actividad

// Calcular el tiempo en sesión
$time_in_session = time() - $_SESSION['LAST_ACTIVITY'];
$minutes = floor($time_in_session / 60);
$seconds = $time_in_session % 60;

$servidor = "localhost";
$usuario = "root";
$pwd = "";
$bd = "tienda";

// Conexión a la base de datos
$con = mysqli_connect($servidor, $usuario, $pwd, $bd);

// Verificar la conexión
if (!$con) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Realizar la consulta
$c = "SELECT * FROM producto"; // Asegúrate de que la tabla 'producto' existe
$v = mysqli_query($con, $c);

// Verificar si la consulta fue exitosa
if (!$v) {
    die("Error en la consulta: " . mysqli_error($con)); // Muestra el error de la consulta
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Css/Estilos.css">
    <script>
        var lastActivity = <?= $_SESSION['LAST_ACTIVITY'] ?>; // Obtener la última actividad
        var sessionStart = new Date().getTime(); // Guardar el tiempo de inicio de la sesión
        var inactiveTime = 60 * 1000; // 1 minuto en milisegundos

        function updateSessionTime() {
            var currentTime = new Date().getTime();
            var timeInSession = Math.floor((currentTime - sessionStart) / 1000); // Tiempo en segundos
            var minutes = Math.floor(timeInSession / 60);
            var seconds = timeInSession % 60;

            document.getElementById('session-time').innerText = "Tiempo en sesión: " + minutes + "m " + seconds + "s";

            // Redirigir si ha pasado el tiempo de inactividad
            if (timeInSession >= 60) { // Cambia a 60 para 1 minuto
                window.location.href = 'index.html'; // Redirigir a la página de inicio
            }
        }

        setInterval(updateSessionTime, 1000); // Actualizar cada segundo
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Lista de Productos</h2>
        <p id="session-time" class="text-center"></p> <!-- Mostrar tiempo en sesión -->
        <div class="row">
            <?php while ($p = mysqli_fetch_array($v)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="ruta/a/imagen/de/producto.jpg" class="card-img-top" alt="<?= htmlspecialchars($p['Nombre']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($p['Nombre']); ?></h5>
                            <p class="card-text">Descripción: <?= htmlspecialchars($p['Descripcion']); ?></p>
                            <p class="card-text"><strong>Precio: $<?= htmlspecialchars($p['Precio']); ?></strong></p>
                            <a href="#" class="btn btn-primary">Agregar al carrito</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
mysqli_close($con);
?>
