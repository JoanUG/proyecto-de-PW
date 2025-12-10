<?php
http_response_code(500);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error del Servidor - Sistema UNAH</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }
        
        .error-container {
            max-width: 700px;
            width: 100%;
            background: white;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        
        .error-code {
            font-size: 120px;
            font-weight: 800;
            background: linear-gradient(to right, #f093fb, #f5576c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
            line-height: 1;
        }
        
        .error-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 15px;
        }
        
        .error-message {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .btn-home {
            display: inline-block;
            background: linear-gradient(to right, #2c5e1a, #4a7c3f);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(44, 94, 26, 0.2);
            color: white;
        }
        
        .icon-container {
            margin-bottom: 30px;
        }
        
        .icon-container i {
            font-size: 4rem;
            color: #f5576c;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
            text-align: left;
        }
        
        .details h4 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .details pre {
            background: #343a40;
            color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        
        .troubleshooting {
            margin-top: 30px;
            padding: 20px;
            background: #fff3cd;
            border-radius: 10px;
            border-left: 4px solid #ffc107;
        }
        
        .troubleshooting h5 {
            color: #856404;
            margin-bottom: 10px;
        }
        
        .troubleshooting ul {
            padding-left: 20px;
            margin-bottom: 0;
            color: #856404;
        }
        
        .troubleshooting li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="icon-container">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        
        <div class="error-code">500</div>
        
        <div class="error-title">Error Interno del Servidor</div>
        
        <div class="error-message">
            Ha ocurrido un error inesperado en el servidor. 
            Nuestro equipo técnico ha sido notificado y está trabajando para solucionarlo.
        </div>
        
        <div class="details">
            <h4>Información del Error:</h4>
            <pre><?php
                // Mostrar información básica del error
                echo "Fecha: " . date('Y-m-d H:i:s') . "\n";
                echo "URL: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
                echo "Método: " . ($_SERVER['REQUEST_METHOD'] ?? 'N/A') . "\n";
                echo "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . "\n";
                
                // Si hay un error específico, mostrarlo (solo en desarrollo)
                if (isset($_SESSION['last_error'])) {
                    echo "\nÚltimo error:\n";
                    echo $_SESSION['last_error'];
                    unset($_SESSION['last_error']);
                }
            ?></pre>
        </div>
        
        <div class="troubleshooting">
            <h5><i class="fas fa-wrench me-2"></i> Solución de Problemas:</h5>
            <ul>
                <li>Intenta recargar la página en unos minutos</li>
                <li>Verifica tu conexión a internet</li>
                <li>Limpia la caché del navegador</li>
                <li>Si el problema persiste, contacta al soporte técnico</li>
            </ul>
        </div>
        
        <div class="mt-4">
            <a href="dashboard.php" class="btn-home me-3">
                <i class="fas fa-home me-2"></i> Volver al Inicio
            </a>
            <a href="javascript:location.reload()" class="btn-home" style="background: linear-gradient(to right, #007bff, #0056b3);">
                <i class="fas fa-redo me-2"></i> Recargar Página
            </a>
        </div>
        
        <div class="mt-4 text-muted small">
            <p>Sistema de Gestión UNAH • Departamento de Informática</p>
            <p>Soporte técnico: soporte@unah.edu.cu • Tel: +53 7 1234567</p>
            <p>Fecha: <?php echo date('d/m/Y H:i:s'); ?></p>
        </div>
    </div>
    
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <script>
        // Auto-reload después de 30 segundos si el usuario no hace nada
        setTimeout(() => {
            if (confirm('¿Deseas recargar la página para verificar si el error se ha solucionado?')) {
                location.reload();
            }
        }, 30000);
    </script>
</body>
</html>