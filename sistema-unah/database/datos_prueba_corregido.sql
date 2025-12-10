USE `sistema-unah`;

-- Insertar tesis de prueba (AJUSTADO para usar nombre_completo y rol)
INSERT INTO tesis (titulo, descripcion, estudiante_id, tutor_id, codigo, estado, fecha_inicio) VALUES
('Sistema de Gestión Inteligente para Cultivos de Tabaco', 'Desarrollo de un sistema que utiliza IA para optimizar el crecimiento del tabaco', 4, 2, 'TES-2024-001', 'en_proceso', '2024-01-15'),
('Plataforma IoT para Monitoreo de Invernaderos', 'Sistema basado en IoT para controlar variables ambientales en invernaderos', 5, 3, 'TES-2024-002', 'pendiente', '2024-02-01'),
('Aplicación Móvil para Diagnóstico de Plagas en Cultivos', 'App que utiliza visión por computadora para identificar plagas agrícolas', 4, 2, 'TES-2024-003', 'finalizada', '2023-11-10'),
('Sistema de Predicción de Rendimiento de Café', 'Modelo de ML para predecir producción de café basado en variables climáticas', 5, 3, 'TES-2024-004', 'aprobada', '2023-09-20');

-- Insertar prácticas de prueba
INSERT INTO practicas (estudiante_id, empresa, supervisor, periodo, estado, calificacion) VALUES
(4, 'Empresa Agropecuaria La Habana', 'Ing. Roberto Díaz', 'Enero-Marzo 2024', 'finalizada', 4.8),
(5, 'Centro de Investigaciones Agrícolas', 'Dra. Laura Méndez', 'Febrero-Abril 2024', 'en_curso', NULL);

-- Insertar avances de tesis
INSERT INTO avances_tesis (tesis_id, porcentaje, descripcion, fecha_avance, aprobado) VALUES
(1, 30, 'Revisión bibliográfica y definición de requerimientos', '2024-02-15', TRUE),
(1, 60, 'Desarrollo del módulo de sensores y backend', '2024-03-10', TRUE),
(3, 100, 'Tesis completada y aprobada por el tribunal', '2023-12-20', TRUE);

-- Insertar seguimiento de prácticas
INSERT INTO seguimiento_practicas (practica_id, actividad, horas, fecha) VALUES
(1, 'Capacitación en sistemas de riego', 40, '2024-01-20'),
(1, 'Implementación de sistema de control', 60, '2024-02-15'),
(2, 'Investigación sobre cultivos resistentes', 35, '2024-02-28');

-- Insertar notificaciones
INSERT INTO notificaciones (usuario_id, tipo, titulo, mensaje) VALUES
(4, 'info', 'Nuevo avance aprobado', 'Tu tutor ha aprobado el avance del 10 de marzo'),
(5, 'warning', 'Recordatorio', 'Tu práctica profesional finaliza en 15 días'),
(2, 'success', 'Nueva tesis asignada', 'Se te ha asignado como tutor de una nueva tesis');

-- Asignar temáticas a tesis
INSERT INTO tesis_tematicas (tesis_id, tematica_id) VALUES
(1, 1), (1, 3),  -- Tesis 1: IA y IoT
(2, 3),          -- Tesis 2: IoT
(3, 6), (3, 9),  -- Tesis 3: Apps móviles y logística
(4, 4);          -- Tesis 4: Machine Learning