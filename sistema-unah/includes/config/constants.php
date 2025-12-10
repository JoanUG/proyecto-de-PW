<?php
// Constantes del sistema
define('SITE_NAME', 'Sistema de Gestión UNAH');
define('SITE_URL', 'http://localhost/sistema-unah/');
define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/sistema-unah/');

// Rutas de archivos
define('UPLOAD_TESIS_PATH', SITE_ROOT . 'assets/uploads/tesis/');
define('UPLOAD_PRACTICAS_PATH', SITE_ROOT . 'assets/uploads/practicas/');

// Configuración de sesión
define('SESSION_TIMEOUT', 1800); // 30 minutos en segundos
define('SESSION_WARNING', 300);  // 5 minutos antes de advertencia

// Roles de usuario
define('ROLE_ADMIN', 'admin');
define('ROLE_PROFESOR', 'profesor');
define('ROLE_ESTUDIANTE', 'estudiante');

// Estados
define('ESTADO_PENDIENTE', 'pendiente');
define('ESTADO_EN_PROCESO', 'en_proceso');
define('ESTADO_FINALIZADA', 'finalizada');
define('ESTADO_APROBADA', 'aprobada');
?>