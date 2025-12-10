<?php
// includes/constants.php

// Constantes de sesión
define('SESSION_TIMEOUT', 1800);       // 30 minutos
define('SESSION_REGENERATE_TIME', 300); // 5 minutos

// Roles de usuario
define('ROLE_ADMIN', 'admin');
define('ROLE_PROFESOR', 'profesor');
define('ROLE_ESTUDIANTE', 'estudiante');
define('ROLE_COORDINADOR', 'coordinador');

// Estados de tesis
define('TESIS_PENDIENTE', 'pendiente');
define('TESIS_EN_PROCESO', 'en_proceso');
define('TESIS_FINALIZADA', 'finalizada');
define('TESIS_APROBADA', 'aprobada');

// Estados de prácticas
define('PRACTICA_PENDIENTE', 'pendiente');
define('PRACTICA_EN_CURSO', 'en_curso');
define('PRACTICA_FINALIZADA', 'finalizada');
define('PRACTICA_EVALUADA', 'evaluada');
?>