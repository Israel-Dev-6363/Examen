<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros Guardados</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container-main {
            max-width: 900px;
            margin: 30px auto;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        .table-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .btn-custom {
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="container-main">
        <div class="header">
            <h1>📊 Todos los Registros Guardados</h1>
            <p class="lead">Base de datos: formulario_db | Tabla: contactos</p>
        </div>

        <?php
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
            echo "❌ Error de conexión a la base de datos: " . $conn->connect_error;
            echo "</div>";
        } else {
            // Consulta para obtener todos los registros
            $sql = "SELECT id, nombre, email, DATE_FORMAT(fecha_envio, '%d/%m/%Y %H:%i:%s') as fecha_formateada 
                    FROM contactos 
                    ORDER BY fecha_envio DESC";
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                echo "<div class='table-container'>";
                echo "<div class='d-flex justify-content-between align-items-center mb-3'>";
                echo "<h3>Total de registros: <span class='badge bg-primary'>" . $result->num_rows . "</span></h3>";
                echo "<div>";
                echo "<a href='formulario.html' class='btn btn-primary btn-custom'>➕ Nuevo Registro</a>";
                echo "<a href='procesar_formulario.php' class='btn btn-secondary btn-custom'>↩️ Volver</a>";
                echo "</div>";
                echo "</div>";
                
                echo "<div class='table-responsive'>";
                echo "<table class='table table-striped table-hover'>";
                echo "<thead class='table-dark'>";
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Nombre</th>";
                echo "<th>Email</th>";
                echo "<th>Fecha de Registro</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                
                // Mostrar cada registro
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><strong>" . $row["id"] . "</strong></td>";
                    echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td>" . $row["fecha_formateada"] . "</td>";
                    echo "</tr>";
                }
                
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-warning text-center' role='alert'>";
                echo "<h4>📭 No hay registros en la base de datos</h4>";
                echo "<p>Aún no se han guardado registros en la tabla 'contactos'</p>";
                echo "</div>";
            }
            
            // Cerrar conexión
            $conn->close();
        }
        ?>
        
        <div class="text-center mt-4">
            <div class="btn-group" role="group">
                <a href="formulario.html" class="btn btn-success">📝 Ir al Formulario</a>
                <a href="procesar_formulario.php" class="btn btn-info">🔄 Procesar Otro</a>
            </div>
        </div>
        
        <footer class="text-center mt-5">
            <div class="alert alert-light" role="alert">
                <strong>Hecho por Diego Israel Garcia Chen</strong> | 
                <span id="fecha-actual"></span>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar fecha actual
        const now = new Date();
        document.getElementById('fecha-actual').textContent = 'Fecha: ' + now.toLocaleDateString('es-MX');
    </script>
</body>
</html>