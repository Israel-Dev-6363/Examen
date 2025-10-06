<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del Formulario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .result-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            background-color: white;
            border: 3px solid #198754;
        }
        .result-title {
            text-align: center;
            margin-bottom: 30px;
            color: #495057;
        }
        .success {
            color: #198754;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="result-container">
            <h2 class="result-title">Datos Recibidos</h2>
            
            <?php
            // Verificar si se enviaron los datos
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Obtener y sanitizar los datos
                $nombre = htmlspecialchars(trim($_POST['nombre']));
                $email = htmlspecialchars(trim($_POST['email']));
                
                // Mostrar los datos recibidos
                echo "<div class='mb-3'>";
                echo "<p><strong>Nombre:</strong> " . $nombre . "</p>";
                echo "<p><strong>Correo Electrónico:</strong> " . $email . "</p>";
                echo "</div>";
                
                // Configuración de la base de datos
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "formulario_db";
                
                // Crear conexión
                $conn = new mysqli($servername, $username, $password, $dbname);
                
                // Verificar conexión
                if ($conn->connect_error) {
                    echo "<div class='alert alert-danger' role='alert'>";
                    echo "Error de conexión a la base de datos: " . $conn->connect_error;
                    echo "</div>";
                } else {
                    // Preparar y ejecutar la consulta SQL
                    $sql = "INSERT INTO contactos (nombre, email) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);
                    
                    if ($stmt) {
                        $stmt->bind_param("ss", $nombre, $email);
                        
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success' role='alert'>";
                            echo "✅ Los datos se guardaron correctamente en la base de datos";
                            echo "</div>";
                            
                            // Mostrar el ID del registro insertado
                            echo "<p class='success'>Registro guardado con ID: " . $stmt->insert_id . "</p>";
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>";
                            echo "❌ Error al guardar en la base de datos: " . $stmt->error;
                            echo "</div>";
                        }
                        
                        $stmt->close();
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>";
                        echo "❌ Error al preparar la consulta: " . $conn->error;
                        echo "</div>";
                    }
                    
                    $conn->close();
                }
                
                // Mostrar fecha y hora de envío
                date_default_timezone_set('America/Mexico_City');
                $fecha = date('d/m/Y H:i:s');
                echo "<p><strong>Fecha y hora de envío:</strong> " . $fecha . "</p>";
                
                echo "<div class='alert alert-info mt-3' role='alert'>";
                echo "Hecho por Diego Israel Garcia Chen";
                echo "</div>";
                
                // Botones para acciones adicionales
                echo "<div class='d-grid gap-2 mt-4'>";
                echo "<a href='formulario.html' class='btn btn-secondary'>Volver al Formulario</a>";
                echo "<a href='ver_registros.php' class='btn btn-success'>Ver Todos los Registros</a>";
                echo "</div>";
                
            } else {
                echo "<div class='alert alert-warning' role='alert'>";
                echo "No se han recibido datos del formulario.";
                echo "</div>";
                echo "<div class='d-grid gap-2 mt-4'>";
                echo "<a href='formulario.html' class='btn btn-primary'>Ir al Formulario</a>";
                echo "<a href='ver_registros.php' class='btn btn-success'>Ver Todos los Registros</a>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>