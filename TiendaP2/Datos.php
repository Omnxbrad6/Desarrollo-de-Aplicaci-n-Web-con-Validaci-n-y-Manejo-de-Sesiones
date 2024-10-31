<?php
include 'config.php'; // Incluir la configuración y el inicio de sesión

header('Content-Type: application/json');

$usernameInput = isset($_POST['usuario']) ? $_POST['usuario'] : '';
$passwordInput = isset($_POST['password']) ? $_POST['password'] : '';

$query = "SELECT * FROM usuarios WHERE username = ?";
$stmt = mysqli_prepare($con, $query);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . mysqli_error($con)]);
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $usernameInput);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if ($row['password'] === $passwordInput) {
        $_SESSION['USER'] = $row['username']; // Almacena el nombre de usuario en la sesión
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
}

mysqli_close($con);
?>
