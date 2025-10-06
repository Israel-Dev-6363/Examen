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

// Procesar eliminación de usuario
if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM personas WHERE id = ?");
    $stmt->bind_param("i", $id_eliminar);
    $stmt->execute();
    $stmt->close();
    
    // Redirigir para evitar reenvío del formulario
    header("Location: admin_dashboard.php");
    exit();
}

// Consultar todos los usuarios
$sql = "SELECT id, nombre, correo, fecha_registro FROM personas ORDER BY fecha_registro DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Panel Admin</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Hola, <?php echo $_SESSION['admin_usuario']; ?>
                </span>
                <a class="nav-link btn btn-outline-warning btn-sm" href="logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="text-warning">Gestión de Usuarios</h1>
    <div>
        <a href="admin_registro.html" class="btn btn-primary">Nuevo Usuario</a>
        <a href="lista.php" class="btn btn-outline-info">Vista Pública</a>
    </div>
</div>
        </div>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo Electrónico</th>
                            <th>Fecha de Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row["id"]; ?></td>
                            <td><?php echo htmlspecialchars($row["nombre"]); ?></td>
                            <td><?php echo htmlspecialchars($row["correo"]); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row["fecha_registro"])); ?></td>
                            <td>
                                <a href="editar_usuario.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                <a href="admin_dashboard.php?eliminar=<?php echo $row['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="alert alert-info">
                Total de usuarios registrados: <strong><?php echo $result->num_rows; ?></strong>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                <h4>No hay usuarios registrados</h4>
                <p>Registra el primer usuario haciendo clic en el botón "Nuevo Usuario"</p>
            </div>
        <?php endif; ?>
    </div>
    
    <?php $conn->close(); ?>
</body>
</html>