<?php
session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Incluir configuraciones
require_once 'includes/config/database.php';
require_once 'includes/config/functions.php';

$error = '';
$username = '';

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizar($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Por favor, complete todos los campos';
    } else {
        // Crear instancia del modelo User
        require_once 'models/User.php';
        $userModel = new User();
        
        $user = $userModel->authenticate($username, $password);
        
        if ($user) {
            // Iniciar sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['login_time'] = time();
            $_SESSION['last_activity'] = time();
            
            // Redirigir al dashboard
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Usuario o contraseña incorrectos';
        }
    }
}

// Verificar si hubo timeout
$timeout_message = '';
if (isset($_GET['timeout'])) {
    $timeout_message = 'Su sesión ha expirado por inactividad. Por favor, inicie sesión nuevamente.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestión UNAH</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        
        .login-header {
            background: linear-gradient(to right, #2c5e1a, #4a7c3f);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .login-header i {
            font-size: 4rem;
            margin-bottom: 20px;
            display: block;
        }
        
        .login-header h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }
        
        .login-header p {
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .login-body {
            padding: 40px;
        }
        
        .form-control {
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #4a7c3f;
            box-shadow: 0 0 0 0.25rem rgba(74, 124, 63, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(to right, #2c5e1a, #4a7c3f);
            color: white;
            padding: 15px;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 94, 26, 0.3);
        }
        
        .demo-credentials {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        
        .demo-credentials code {
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
        }
        
        .system-features {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .system-features h5 {
            color: #2c5e1a;
            margin-bottom: 15px;
        }
        
        .system-features ul {
            padding-left: 20px;
            margin-bottom: 0;
        }
        
        .system-features li {
            margin-bottom: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-university"></i>
            <h1>Universidad Agraria de La Habana</h1>
            <p>Sistema de Gestión de Tesis y Prácticas Profesionales</p>
        </div>
        
        <div class="login-body">
            <?php if ($timeout_message): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo $timeout_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">
                        <i class="fas fa-user me-1"></i> Usuario
                    </label>
                    <input type="text" class="form-control" id="username" name="username" 
                           value="<?php echo htmlspecialchars($username); ?>" 
                           placeholder="Ingrese su usuario" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-1"></i> Contraseña
                    </label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Ingrese su contraseña" required>
                    <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" id="showPassword">
                        <label class="form-check-label" for="showPassword">Mostrar contraseña</label>
                    </div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Recordar sesión</label>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                </button>
            </form>
            
            <div class="demo-credentials">
                <p class="mb-2"><strong>Credenciales de demostración:</strong></p>
                <div class="row">
                    <div class="col-4">
                        <small><strong>Admin:</strong></small><br>
                        <code>admin</code> / <code>unah2024</code>
                    </div>
                    <div class="col-4">
                        <small><strong>Profesor:</strong></small><br>
                        <code>profesor</code> / <code>unah2024</code>
                    </div>
                    <div class="col-4">
                        <small><strong>Estudiante:</strong></small><br>
                        <code>estudiante</code> / <code>unah2024</code>
                    </div>
                </div>
            </div>
            
            <div class="system-features">
                <h5><i class="fas fa-info-circle me-2"></i> Resuelve la problemática:</h5>
                <ul>
                    <li>Centralización de gestión de tesis</li>
                    <li>Evita duplicidad de temas</li>
                    <li>Seguimiento en tiempo real</li>
                    <li>Reportes estadísticos automáticos</li>
                    <li>Asignación óptima de tutores</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mostrar/ocultar contraseña
        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            passwordField.type = this.checked ? 'text' : 'password';
        });
        
        // Auto-focus en campo de usuario
        document.getElementById('username').focus();
    </script>
</body>
</html>