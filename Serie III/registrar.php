<?php
session_start();

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Variables
$registrado = false;
$mensaje = "";
$es_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    
    // Validar datos
    if (!empty($nombre) && !empty($correo)) {
        // Preparar y ejecutar consulta
        $stmt = $conn->prepare("INSERT INTO personas (nombre, correo) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $correo);
        
        if ($stmt->execute()) {
            $mensaje = "Usuario registrado exitosamente";
            $registrado = true;
        } else {
            $mensaje = "Error al registrar usuario: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $mensaje = "Por favor, complete todos los campos";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $es_admin ? 'Registro Completado' : 'Registro de Usuario'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header <?php echo $registrado ? 'bg-success' : 'bg-primary'; ?> text-white">
                        <h3 class="text-center">
                            <?php echo $es_admin ? 'Registro de Usuario' : 'Resultado del Registro'; ?>
                        </h3>
                    </div>
                    <div class="card-body text-center">
                        <?php if ($registrado && $es_admin): ?>
                            <!-- Vista para admin después de registrar -->
                            <div class="alert alert-success">
                                <h5><?php echo $mensaje; ?></h5>
                                <div class="mt-3">
                                    <a href="admin_dashboard.php" class="btn btn-success me-2">OK</a>
                                    <a href="index.html" class="btn btn-outline-primary">Registrar otro usuario</a>
                                </div>
                            </div>
                        <?php elseif (!$registrado && $es_admin): ?>
                            <!-- Vista para admin cuando hay error -->
                            <p class="text-danger"><?php echo $mensaje; ?></p>
                            <div class="mt-3">
                                <a href="index.html" class="btn btn-primary">Intentar nuevamente</a>
                                <a href="admin_dashboard.php" class="btn btn-outline-secondary">Volver al Panel</a>
                            </div>
                        <?php elseif ($registrado && !$es_admin): ?>
                            <!-- Vista para usuario normal después de registrar -->
                            <p><?php echo $mensaje; ?></p>
                            <div class="mt-3">
                                <a href="index.html" class="btn btn-primary">Registrar otro usuario</a>
                                <a href="lista.php" class="btn btn-outline-secondary">Ver lista de usuarios</a>
                            </div>
                        <?php else: ?>
                            <!-- Vista para usuario normal cuando hay error -->
                            <p class="text-danger"><?php echo $mensaje; ?></p>
                            <div class="mt-3">
                                <a href="index.html" class="btn btn-primary">Intentar nuevamente</a>
                                <a href="lista.php" class="btn btn-outline-secondary">Ver lista de usuarios</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>