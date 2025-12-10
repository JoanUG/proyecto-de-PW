<?php
// includes/constants.php

// Definir constantes solo si no están ya definidas
if (!defined('SESSION_TIMEOUT')) {
    define('SESSION_TIMEOUT', 1800);       // 30 minutos
}

if (!defined('SESSION_REGENERATE_TIME')) {
    define('SESSION_REGENERATE_TIME', 300); // 5 minutos
}

// Roles de usuario
if (!defined('ROLE_ADMIN')) {
    define('ROLE_ADMIN', 'admin');
}

if (!defined('ROLE_PROFESOR')) {
    define('ROLE_PROFESOR', 'profesor');
}

if (!defined('ROLE_ESTUDIANTE')) {
    define('ROLE_ESTUDIANTE', 'estudiante');
}

if (!defined('ROLE_COORDINADOR')) {
    define('ROLE_COORDINADOR', 'coordinador');
}

// Estados de tesis
if (!defined('TESIS_PENDIENTE')) {
    define('TESIS_PENDIENTE', 'pendiente');
}

if (!defined('TESIS_EN_PROCESO')) {
    define('TESIS_EN_PROCESO', 'en_proceso');
}

if (!defined('TESIS_FINALIZADA')) {
    define('TESIS_FINALIZADA', 'finalizada');
}

if (!defined('TESIS_APROBADA')) {
    define('TESIS_APROBADA', 'aprobada');
}

// Estados de prácticas
if (!defined('PRACTICA_PENDIENTE')) {
    define('PRACTICA_PENDIENTE', 'pendiente');
}

if (!defined('PRACTICA_EN_CURSO')) {
    define('PRACTICA_EN_CURSO', 'en_curso');
}

if (!defined('PRACTICA_FINALIZADA')) {
    define('PRACTICA_FINALIZADA', 'finalizada');
}

if (!defined('PRACTICA_EVALUADA')) {
    define('PRACTICA_EVALUADA', 'evaluada');
}
?>