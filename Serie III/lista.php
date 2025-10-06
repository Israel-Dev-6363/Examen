<?php
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

// Consultar datos
$sql = "SELECT id, nombre, correo, fecha_registro FROM personas ORDER BY fecha_registro DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary">Lista de Usuarios Registrados</h1>
            <a href="index.html" class="btn btn-primary">Nuevo Registro</a>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row["id"]; ?></td>
                            <td><?php echo htmlspecialchars($row["nombre"]); ?></td>
                            <td><?php echo htmlspecialchars($row["correo"]); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row["fecha_registro"])); ?></td>
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
                <p>Registra el primer usuario haciendo clic en el botón "Nuevo Registro"</p>
            </div>
        <?php endif; ?>
        
        <div class="text-center mt-4">
            <a href="index.html" class="btn btn-outline-primary">Volver al formulario</a>
             <a href="login.html" class="btn btn-outline-warning">Acceso Administrador</a>
        </div>
    </div>
    
    <?php $conn->close(); ?>
</body>
</html>