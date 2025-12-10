<?php
// includes/session_manager.php

// Definir constante de timeout si no está definida
if (!defined('SESSION_TIMEOUT')) {
    define('SESSION_TIMEOUT', 1800); // 30 minutos por defecto
}
session_start();

// Inicializar tiempo de sesión si no existe
if (!isset($_SESSION['last_activity'])) {
    $_SESSION['last_activity'] = time();
}

// Verificar inactividad
function verificarInactividad() {
    $inactividad = time() - $_SESSION['last_activity'];
    
    if ($inactividad > SESSION_TIMEOUT) {
        session_destroy();
        header('Location: ../index.php?timeout=1');
        exit();
    }
    
    $_SESSION['last_activity'] = time();
}

// Regenerar ID de sesión periódicamente
function regenerarSesion() {
    if (!isset($_SESSION['regenerated'])) {
        $_SESSION['regenerated'] = time();
    }
    
    // Regenerar cada 5 minutos
    if (time() - $_SESSION['regenerated'] > 300) {
        session_regenerate_id(true);
        $_SESSION['regenerated'] = time();
    }
}

// Verificar si el usuario está autenticado
function isAuthenticated() {
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

// Obtener información del usuario actual
function getUserInfo() {
    if (isAuthenticated()) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'nombre' => $_SESSION['nombre'] ?? '',
            'role' => $_SESSION['role'] ?? '',
            'email' => $_SESSION['email'] ?? ''
        ];
    }
    return null;
}

// Función para mantener la sesión activa (usada en AJAX)
function keepAlive() {
    $_SESSION['last_activity'] = time();
    return ['status' => 'ok', 'message' => 'Sesión activa'];
}

// Llamar estas funciones en cada carga de página
verificarInactividad();
regenerarSesion();
?>