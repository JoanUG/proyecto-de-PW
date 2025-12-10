#!/bin/bash

echo "🚀 Instalación del Sistema de Gestión UNAH"
echo "=========================================="

# Verificar PHP
if ! command -v php &> /dev/null; then
    echo "❌ PHP no está instalado. Por favor, instala XAMPP o LAMP primero."
    exit 1
fi

echo "✅ PHP encontrado: $(php --version | head -n 1)"

# Verificar MySQL
if ! command -v mysql &> /dev/null; then
    echo "❌ MySQL no está instalado. Por favor, instala XAMPP o LAMP primero."
    exit 1
fi

echo "✅ MySQL encontrado"

# Crear estructura de carpetas
echo "📁 Creando estructura de carpetas..."
mkdir -p assets/{css,js,img,uploads/{tesis,practicas}}
mkdir -p includes/config
mkdir -p {models,controllers,views/{auth,tesis,practicas,reportes,usuarios},api,database}
mkdir -p error

echo "📝 Creando archivos de configuración..."

# Crear archivo .htaccess
cat > .htaccess << 'EOF'
Options -Indexes
ServerSignature Off
RewriteEngine On
RewriteRule ^dashboard/?$ dashboard.php [L,QSA]
RewriteRule ^login/?$ index.php [L,QSA]
RewriteRule ^logout/?$ logout.php [L,QSA]
RewriteRule ^tesis/?$ listar_tesis.php [L,QSA]
ErrorDocument 404 /error/404.php
EOF

# Crear página de error 404
cat > error/404.php << 'EOF'
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
EOF

# Crear archivo de configuración de ejemplo
cat > includes/config/database.php.example << 'EOF'
<?php
class Database {
    private static $instance = null;
    private $conn;
    
    // CONFIGURA ESTOS VALORES CON TUS DATOS
    private $host = "localhost";
    private $db_name = "sistema_unah";
    private $username = "tu_usuario_mysql";
    private $password = "tu_contraseña_mysql";
    
    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
        } catch(PDOException $exception) {
            error_log("Error de conexión: " . $exception->getMessage());
            die("Error al conectar con la base de datos. Por favor, contacte al administrador.");
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
?>
EOF

echo "📋 Copia database.php.example a database.php y configura tus credenciales MySQL"
echo "🔧 Ejecuta database/schema.sql en phpMyAdmin para crear la base de datos"
echo ""
echo "🎉 ¡Instalación completada!"
echo ""
echo "Para iniciar el sistema:"
echo "1. Configura includes/config/database.php"
echo "2. Importa database/schema.sql en MySQL"
echo "3. Accede a http://localhost/sistema-unah/"
echo ""
echo "Credenciales por defecto:"
echo "  - Admin: admin / unah2024"
echo "  - Profesor: profesor / unah2024"
echo "  - Estudiante: estudiante / unah2024"