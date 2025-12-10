-- Base de datos: sistema-unah (con guión como tienes actualmente)
CREATE DATABASE IF NOT EXISTS `sistema-unah` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sistema-unah`;

-- Tabla de usuarios (AJUSTADA para coincidir con tu estructura actual)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    rol ENUM('admin', 'profesor', 'estudiante', 'coordinador') DEFAULT 'estudiante',
    departamento_id INT,
    telefono VARCHAR(15),
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_rol (rol)
);

-- Tabla de tesis
CREATE TABLE IF NOT EXISTS tesis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(500) NOT NULL,
    descripcion TEXT,
    estudiante_id INT NOT NULL,
    tutor_id INT,
    codigo VARCHAR(20) UNIQUE,
    estado ENUM('pendiente', 'en_proceso', 'finalizada', 'aprobada') DEFAULT 'pendiente',
    fecha_inicio DATE,
    fecha_fin DATE,
    archivo_path VARCHAR(500),
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (estudiante_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (tutor_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_estudiante (estudiante_id),
    INDEX idx_tutor (tutor_id),
    INDEX idx_estado (estado),
    INDEX idx_fecha_inicio (fecha_inicio),
    FULLTEXT idx_titulo_desc (titulo, descripcion)
);

-- Tabla de prácticas profesionales
CREATE TABLE IF NOT EXISTS practicas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    estudiante_id INT NOT NULL,
    empresa VARCHAR(200) NOT NULL,
    supervisor VARCHAR(100),
    cargo_supervisor VARCHAR(100),
    periodo VARCHAR(50) NOT NULL,
    duracion_semanas INT,
    estado ENUM('pendiente', 'en_curso', 'finalizada', 'evaluada') DEFAULT 'pendiente',
    informe_path VARCHAR(500),
    calificacion DECIMAL(3,2),
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (estudiante_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_estudiante (estudiante_id),
    INDEX idx_empresa (empresa),
    INDEX idx_estado (estado)
);

-- Tabla para seguimiento de avances de tesis
CREATE TABLE IF NOT EXISTS avances_tesis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tesis_id INT NOT NULL,
    porcentaje INT DEFAULT 0 CHECK (porcentaje BETWEEN 0 AND 100),
    descripcion TEXT,
    archivo_path VARCHAR(500),
    fecha_avance DATE DEFAULT (CURRENT_DATE),
    aprobado BOOLEAN DEFAULT FALSE,
    observaciones_tutor TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tesis_id) REFERENCES tesis(id) ON DELETE CASCADE,
    INDEX idx_tesis (tesis_id),
    INDEX idx_fecha (fecha_avance)
);

-- Tabla para seguimiento de prácticas
CREATE TABLE IF NOT EXISTS seguimiento_practicas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    practica_id INT NOT NULL,
    actividad VARCHAR(200),
    descripcion TEXT,
    horas INT,
    fecha DATE DEFAULT (CURRENT_DATE),
    evidencias_path VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (practica_id) REFERENCES practicas(id) ON DELETE CASCADE,
    INDEX idx_practica (practica_id)
);

-- Tabla de temáticas (para evitar duplicidad)
CREATE TABLE IF NOT EXISTS tematicas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    area VARCHAR(100),
    activa BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_nombre (nombre),
    INDEX idx_area (area)
);

-- Tabla de relación tesis-temáticas
CREATE TABLE IF NOT EXISTS tesis_tematicas (
    tesis_id INT NOT NULL,
    tematica_id INT NOT NULL,
    PRIMARY KEY (tesis_id, tematica_id),
    FOREIGN KEY (tesis_id) REFERENCES tesis(id) ON DELETE CASCADE,
    FOREIGN KEY (tematica_id) REFERENCES tematicas(id) ON DELETE CASCADE
);

-- Tabla de notificaciones
CREATE TABLE IF NOT EXISTS notificaciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    tipo ENUM('info', 'warning', 'success', 'danger'),
    titulo VARCHAR(200),
    mensaje TEXT,
    leida BOOLEAN DEFAULT FALSE,
    link VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_leida (leida)
);

-- Tabla de auditoría (logs del sistema)
CREATE TABLE IF NOT EXISTS auditoria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    accion VARCHAR(100),
    modulo VARCHAR(50),
    descripcion TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_usuario (usuario_id),
    INDEX idx_accion (accion),
    INDEX idx_modulo (modulo),
    INDEX idx_fecha (created_at)
);

-- Insertar usuarios iniciales (contraseña encriptada: unah2024)
INSERT INTO usuarios (username, password, nombre_completo, email, rol) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador Sistema', 'admin@unah.edu.cu', 'admin'),
('profesor1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Juan Pérez', 'jperez@unah.edu.cu', 'profesor'),
('profesor2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dra. María Rodríguez', 'mrodriguez@unah.edu.cu', 'profesor'),
('estudiante1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos Martínez', 'cmartinez@unah.edu.cu', 'estudiante'),
('estudiante2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ana López', 'alopez@unah.edu.cu', 'estudiante');

-- Insertar temáticas predefinidas
INSERT INTO tematicas (nombre, area) VALUES
('Inteligencia Artificial en Agricultura', 'Tecnología'),
('Sistemas de Riego Automatizado', 'Automatización'),
('Gestión de Cultivos con IoT', 'IoT'),
('Análisis de Suelos con Machine Learning', 'Ciencia de Datos'),
('Sistemas de Trazabilidad Agropecuaria', 'Gestión'),
('Aplicaciones Móviles para Agricultores', 'Desarrollo Móvil'),
('Plataformas de Comercio Electrónico Agrícola', 'E-commerce'),
('Monitoreo Climático con Sensores', 'Medio Ambiente'),
('Optimización de Cadena de Suministro', 'Logística'),
('Realidad Aumentada en Entrenamiento Agrícola', 'Educación');