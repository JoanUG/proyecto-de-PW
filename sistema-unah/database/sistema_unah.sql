-- SISTEMA UNAH - Base de datos completa
-- Archivo para importar en phpMyAdmin

-- 1. Crear base de datos
CREATE DATABASE IF NOT EXISTS sistema_unah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_unah;

-- 2. Tabla de usuarios
CREATE TABLE usuarios (
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

-- 3. Insertar usuarios
INSERT INTO usuarios (username, password, nombre_completo, email, rol) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador del Sistema', 'admin@unah.edu.cu', 'admin'),
('joan', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Joan Pérez López', 'joan@unah.edu.cu', 'estudiante'),
('maria.prof', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dra. María Rodríguez', 'mrodriguez@unah.edu.cu', 'profesor'),
('carlos', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos Martínez', 'cmartinez@unah.edu.cu', 'estudiante'),
('ana.lopez', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ana López Pérez', 'alopez@unah.edu.cu', 'estudiante'),
('juan.tutor', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Juan Pérez', 'jperez@unah.edu.cu', 'profesor'),
('coordinador1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lic. Roberto Díaz', 'rdiaz@unah.edu.cu', 'coordinador');

-- 4. Tabla de tesis
CREATE TABLE tesis (
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

-- 5. Insertar tesis de prueba
INSERT INTO tesis (titulo, descripcion, estudiante_id, tutor_id, codigo, estado, fecha_inicio) VALUES
('Sistema de Gestión Inteligente para Cultivos de Tabaco', 'Desarrollo de un sistema que utiliza IA para optimizar el crecimiento del tabaco', 2, 6, 'TES-2024-001', 'en_proceso', '2024-01-15'),
('Plataforma IoT para Monitoreo de Invernaderos', 'Sistema basado en IoT para controlar variables ambientales en invernaderos', 4, 3, 'TES-2024-002', 'pendiente', '2024-02-01'),
('Aplicación Móvil para Diagnóstico de Plagas en Cultivos', 'App que utiliza visión por computadora para identificar plagas agrícolas', 2, 6, 'TES-2024-003', 'finalizada', '2023-11-10'),
('Sistema de Predicción de Rendimiento de Café', 'Modelo de ML para predecir producción de café basado en variables climáticas', 5, 3, 'TES-2024-004', 'aprobada', '2023-09-20');

-- 6. Tabla de prácticas profesionales
CREATE TABLE practicas (
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

-- 7. Insertar prácticas de prueba
INSERT INTO practicas (estudiante_id, empresa, supervisor, periodo, estado, calificacion) VALUES
(2, 'Empresa Agropecuaria La Habana', 'Ing. Roberto Díaz', 'Enero-Marzo 2024', 'finalizada', 4.8),
(4, 'Centro de Investigaciones Agrícolas', 'Dra. Laura Méndez', 'Febrero-Abril 2024', 'en_curso', NULL);

-- 8. Tabla de avances de tesis
CREATE TABLE avances_tesis (
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

-- 9. Insertar avances de tesis
INSERT INTO avances_tesis (tesis_id, porcentaje, descripcion, fecha_avance, aprobado) VALUES
(1, 30, 'Revisión bibliográfica y definición de requerimientos', '2024-02-15', TRUE),
(1, 60, 'Desarrollo del módulo de sensores y backend', '2024-03-10', TRUE),
(3, 100, 'Tesis completada y aprobada por el tribunal', '2023-12-20', TRUE);

-- 10. Tabla de seguimiento de prácticas
CREATE TABLE seguimiento_practicas (
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

-- 11. Insertar seguimiento de prácticas
INSERT INTO seguimiento_practicas (practica_id, actividad, horas, fecha) VALUES
(1, 'Capacitación en sistemas de riego', 40, '2024-01-20'),
(1, 'Implementación de sistema de control', 60, '2024-02-15'),
(2, 'Investigación sobre cultivos resistentes', 35, '2024-02-28');

-- 12. Tabla de temáticas
CREATE TABLE tematicas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    area VARCHAR(100),
    activa BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_nombre (nombre),
    INDEX idx_area (area)
);

-- 13. Insertar temáticas
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

-- 14. Tabla de relación tesis-temáticas
CREATE TABLE tesis_tematicas (
    tesis_id INT NOT NULL,
    tematica_id INT NOT NULL,
    PRIMARY KEY (tesis_id, tematica_id),
    FOREIGN KEY (tesis_id) REFERENCES tesis(id) ON DELETE CASCADE,
    FOREIGN KEY (tematica_id) REFERENCES tematicas(id) ON DELETE CASCADE
);

-- 15. Asignar temáticas a tesis
INSERT INTO tesis_tematicas (tesis_id, tematica_id) VALUES
(1, 1), (1, 3),
(2, 3),
(3, 6), (3, 9),
(4, 4);

-- 16. Tabla de notificaciones
CREATE TABLE notificaciones (
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

-- 17. Insertar notificaciones
INSERT INTO notificaciones (usuario_id, tipo, titulo, mensaje) VALUES
(2, 'info', 'Nuevo avance aprobado', 'Tu tutor ha aprobado el avance del 10 de marzo'),
(4, 'warning', 'Recordatorio', 'Tu práctica profesional finaliza en 15 días'),
(6, 'success', 'Nueva tesis asignada', 'Se te ha asignado como tutor de una nueva tesis');

-- 18. Tabla de auditoría
CREATE TABLE auditoria (
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

-- 19. Insertar registros de auditoría
INSERT INTO auditoria (usuario_id, accion, modulo, descripcion) VALUES
(1, 'LOGIN', 'Autenticación', 'Inicio de sesión exitoso'),
(2, 'CONSULTA', 'Tesis', 'Consultó el listado de tesis'),
(6, 'APROBACION', 'Avances', 'Aprobó avance de tesis ID: 1');

-- 20. Mostrar mensaje final
SELECT 'Base de datos creada exitosamente!' AS Mensaje;
SELECT 'Usuarios disponibles:' AS '';
SELECT 'admin / unah2024' AS 'Admin';
SELECT 'joan / unah2024' AS 'Estudiante';
SELECT 'maria.prof / unah2024' AS 'Profesora';