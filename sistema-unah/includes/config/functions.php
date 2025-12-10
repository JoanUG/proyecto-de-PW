<?php
// Funciones de utilidad general

function sanitizar($data) {
    if (is_array($data)) {
        return array_map('sanitizar', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function generarCodigo($longitud = 8) {
    $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $codigo;
}

function formatoFecha($fecha, $formato = 'd/m/Y') {
    if (empty($fecha) || $fecha == '0000-00-00') {
        return 'N/A';
    }
    $date = new DateTime($fecha);
    return $date->format($formato);
}

function calcularPorcentaje($parcial, $total) {
    if ($total == 0) return 0;
    return round(($parcial / $total) * 100, 2);
}

function subirArchivo($file, $directorio, $tiposPermitidos = ['pdf', 'doc', 'docx']) {
    $nombre_original = basename($file["name"]);
    $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
    
    // Validar tipo de archivo
    if (!in_array($extension, $tiposPermitidos)) {
        throw new Exception("Tipo de archivo no permitido. Solo se aceptan: " . implode(', ', $tiposPermitidos));
    }
    
    // Validar tamaño (máximo 10MB)
    if ($file["size"] > 10000000) {
        throw new Exception("El archivo es demasiado grande. Máximo 10MB.");
    }
    
    // Generar nombre único
    $nombre_archivo = time() . '_' . uniqid() . '.' . $extension;
    $ruta_destino = $directorio . $nombre_archivo;
    
    // Crear directorio si no existe
    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true);
    }
    
    if (move_uploaded_file($file["tmp_name"], $ruta_destino)) {
        return [
            'nombre_original' => $nombre_original,
            'nombre_archivo' => $nombre_archivo,
            'ruta' => $ruta_destino,
            'extension' => $extension,
            'tamano' => $file["size"]
        ];
    }
    
    throw new Exception("Error al subir el archivo.");
}

function mostrarAlerta($tipo, $mensaje) {
    $clases = [
        'success' => 'alert-success',
        'error' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info'
    ];
    
    if (isset($clases[$tipo])) {
        return '<div class="alert ' . $clases[$tipo] . ' alert-dismissible fade show" role="alert">
                ' . $mensaje . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    return '';
}

function esAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === ROLE_ADMIN;
}

function esProfesor() {
    return isset($_SESSION['role']) && $_SESSION['role'] === ROLE_PROFESOR;
}

function esEstudiante() {
    return isset($_SESSION['role']) && $_SESSION['role'] === ROLE_ESTUDIANTE;
}

function tienePermiso($permisoRequerido) {
    $permisos = [
        ROLE_ADMIN => ['admin', 'profesor', 'estudiante'],
        ROLE_PROFESOR => ['profesor', 'estudiante'],
        ROLE_ESTUDIANTE => ['estudiante']
    ];
    
    if (!isset($_SESSION['role'])) return false;
    
    return in_array($permisoRequerido, $permisos[$_SESSION['role']]);
}
?>