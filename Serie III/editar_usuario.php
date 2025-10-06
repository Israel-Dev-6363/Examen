<?php
session_start();

// Verificar si el admin está logueado
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.html");
    exit();
}

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

// Obtener datos del usuario a editar
$usuario = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT id, nombre, correo FROM personas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();
}

// Procesar actualización
$actualizado = false;
$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    
    if (!empty($nombre) && !empty($correo)) {
        $stmt = $conn->prepare("UPDATE personas SET nombre = ?, correo = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nombre, $correo, $id);
        
        if ($stmt->execute()) {
            $mensaje = "Usuario actualizado correctamente";
            $actualizado = true;
            // Actualizar datos locales
            $usuario['nombre'] = $nombre;
            $usuario['correo'] = $correo;
        } else {
            $mensaje = "Error al actualizar usuario: " . $stmt->error;
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
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Editar Usuario</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link btn btn-outline-warning btn-sm" href="admin_dashboard.php">Volver al Panel</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="text-center">Editar Usuario</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($usuario): ?>
                            <?php if ($mensaje): ?>
                                <div class="alert alert-success text-center">
                                    <h5><?php echo $mensaje; ?></h5>
                                    <?php if ($actualizado): ?>
                                        <div class="mt-3">
                                            <a href="admin_dashboard.php" class="btn btn-success">OK</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!$actualizado): ?>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre completo</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="correo" class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control" id="correo" name="correo" 
                                           value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                                    <a href="admin_dashboard.php" class="btn btn-outline-secondary">Cancelar</a>
                                </div>
                            </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-danger text-center">
                                <h4>Usuario no encontrado</h4>
                                <a href="admin_dashboard.php" class="btn btn-outline-primary">Volver al Panel</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>