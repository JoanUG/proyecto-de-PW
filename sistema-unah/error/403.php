<?php
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Denegado - Sistema UNAH</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }
        
        .error-container {
            max-width: 600px;
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
            background: linear-gradient(to right, #ff416c, #ff4b2b);
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
            color: #ff416c;
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
        
        .details ul {
            padding-left: 20px;
            margin-bottom: 0;
        }
        
        .details li {
            margin-bottom: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="icon-container">
            <i class="fas fa-ban"></i>
        </div>
        
        <div class="error-code">403</div>
        
        <div class="error-title">Acceso Denegado</div>
        
        <div class="error-message">
            No tienes permisos para acceder a esta página. 
            Si crees que esto es un error, contacta al administrador del sistema.
        </div>
        
        <div class="details">
            <h4>Posibles causas:</h4>
            <ul>
                <li>Tu cuenta no tiene los permisos necesarios</li>
                <li>Estás intentando acceder a un recurso restringido</li>
                <li>Tu sesión ha expirado o no has iniciado sesión</li>
                <li>Error de configuración del sistema</li>
            </ul>
        </div>
        
        <div class="mt-4">
            <a href="dashboard.php" class="btn-home me-3">
                <i class="fas fa-home me-2"></i> Volver al Inicio
            </a>
            <a href="logout.php" class="btn-home" style="background: linear-gradient(to right, #6c757d, #495057);">
                <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
            </a>
        </div>
        
        <div class="mt-4 text-muted small">
            <p>Sistema de Gestión UNAH • Departamento de Informática</p>
            <p>Si el problema persiste, contacta a: soporte@unah.edu.cu</p>
        </div>
    </div>
    
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>