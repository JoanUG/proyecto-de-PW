<?php
session_start();

// Registrar evento de auditoría
if (isset($_SESSION['user_id'])) {
    // Aquí podrías registrar el logout en la base de datos
    require_once 'includes/config/functions.php';
    require_once 'includes/config/constants.php';
    
    // Registrar en logs si tienes sistema de auditoría
    $log_message = "Usuario {$_SESSION['username']} cerró sesión";
    error_log($log_message);
}

// Destruir todas las variables de sesión
$_SESSION = array();

// Si se desea destruir la sesión completamente, borra también la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión
session_destroy();

// Redirigir al login con mensaje
header('Location: index.php?logout=1');
exit();
?>