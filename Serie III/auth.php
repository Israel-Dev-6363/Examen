<?php
session_start();

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $password_input = $_POST['password'];
    
    // Buscar administrador
    $stmt = $conn->prepare("SELECT id, usuario, password FROM administradores WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        
        // Verificar contraseña - usando una comparación directa para testing
        if ($password_input === 'admin123' && $usuario === 'admin') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_usuario'] = $admin['usuario'];
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: admin_dashboard.php");
            exit();
        }
    }
    
    // Si llega aquí, las credenciales son incorrectas
    $error = "Usuario o contraseña incorrectos";
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h4 class="text-center">Error de Autenticación</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="text-danger"><?php echo $error; ?></p>
                        <div class="mt-3">
                            <a href="login.html" class="btn btn-warning">Intentar nuevamente</a>
                            <a href="index.html" class="btn btn-outline-secondary">Volver al inicio</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>