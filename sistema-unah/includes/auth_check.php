<?php
// includes/auth_check.php

// Definir constante si no existe
if (!defined('SESSION_TIMEOUT')) {
    define('SESSION_TIMEOUT', 1800); // 30 minutos
}

// Incluir constantes si existe
if (file_exists(__DIR__ . '/constants.php')) {
    require_once __DIR__ . '/constants.php';
}

// Incluir el gestor de sesiones
require_once __DIR__ . '/session_manager.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al login
    header('Location: index.php');
    exit();
}

// Verificar que el usuario esté activo (opcional)
if (isset($_SESSION['activo']) && $_SESSION['activo'] != 1) {
    session_destroy();
    header('Location: index.php?error=inactive');
    exit();
}
?>