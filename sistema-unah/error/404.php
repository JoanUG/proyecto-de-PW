<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página no encontrada - Sistema UNAH</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background: #f8f9fa;
        }
        .error-code {
            font-size: 120px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        .error-message {
            font-size: 24px;
            color: #6c757d;
            margin-bottom: 30px;
        }
        .btn-home {
            background: #2c5e1a;
            color: white;
            padding: 10px 30px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="error-code">404</div>
    <div class="error-message">La página que buscas no existe</div>
    <a href="../dashboard.php" class="btn-home">Volver al inicio</a>
</body>
</html>