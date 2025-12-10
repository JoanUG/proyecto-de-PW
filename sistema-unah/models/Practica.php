<?php
// models/Practica.php - ADAPTADO A TU ESTRUCTURA REAL

require_once __DIR__ . '/../includes/config/database.php';

class Practica {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    // Obtener todas las prácticas (ADAPTADO)
    public function getAll($filtros = []) {
        $query = "SELECT p.*, 
                         u_est.nombre_completo as estudiante_nombre,
                         u_tut.nombre_completo as tutor_interno_nombre
                  FROM practicas p
                  LEFT JOIN usuarios u_est ON p.estudiante_id = u_est.id
                  LEFT JOIN usuarios u_tut ON p.tutor_interno_id = u_tut.id
                  WHERE 1=1";
        $params = [];
        
        if (!empty($filtros['estudiante_id'])) {
            $query .= " AND p.estudiante_id = :estudiante_id";
            $params[':estudiante_id'] = $filtros['estudiante_id'];
        }
        
        if (!empty($filtros['tutor_interno_id'])) {
            $query .= " AND p.tutor_interno_id = :tutor_interno_id";
            $params[':tutor_interno_id'] = $filtros['tutor_interno_id'];
        }
        
        if (!empty($filtros['departamento_id'])) {
            $query .= " AND p.departamento_id = :departamento_id";
            $params[':departamento_id'] = $filtros['departamento_id'];
        }
        
        if (!empty($filtros['estado'])) {
            $query .= " AND p.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }
        
        if (!empty($filtros['search'])) {
            $query .= " AND (p.empresa LIKE :search OR p.tutor_externo LIKE :search)";
            $params[':search'] = '%' . $filtros['search'] . '%';
        }
        
        // Ordenar por fecha_inicio (que es tu columna de fecha)
        $query .= " ORDER BY p.fecha_inicio DESC, p.id DESC";
        
        if (!empty($filtros['limit'])) {
            $query .= " LIMIT " . (int)$filtros['limit'];
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener prácticas por estudiante (ADAPTADO)
    public function getByEstudiante($estudiante_id) {
        $query = "SELECT p.*, 
                         u_tut.nombre_completo as tutor_interno_nombre,
                         u_tut.email as tutor_interno_email
                  FROM practicas p
                  LEFT JOIN usuarios u_tut ON p.tutor_interno_id = u_tut.id
                  WHERE p.estudiante_id = :estudiante_id
                  ORDER BY p.fecha_inicio DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':estudiante_id', $estudiante_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener práctica por ID (ADAPTADO)
    public function getById($id) {
        $query = "SELECT p.*, 
                         u_est.nombre_completo as estudiante_nombre,
                         u_est.email as estudiante_email,
                         u_tut.nombre_completo as tutor_interno_nombre,
                         u_tut.email as tutor_interno_email
                  FROM practicas p
                  LEFT JOIN usuarios u_est ON p.estudiante_id = u_est.id
                  LEFT JOIN usuarios u_tut ON p.tutor_interno_id = u_tut.id
                  WHERE p.id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Crear nueva práctica (ADAPTADO a tu estructura)
    public function create($data) {
        $query = "INSERT INTO practicas (estudiante_id, empresa, tutor_externo, tutor_interno_id, 
                  departamento_id, horas_totales, horas_completadas, fecha_inicio, fecha_fin, 
                  estado, evaluacion, observaciones)
                  VALUES (:estudiante_id, :empresa, :tutor_externo, :tutor_interno_id, 
                  :departamento_id, :horas_totales, :horas_completadas, :fecha_inicio, :fecha_fin, 
                  :estado, :evaluacion, :observaciones)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':estudiante_id', $data['estudiante_id']);
        $stmt->bindParam(':empresa', $data['empresa']);
        $stmt->bindParam(':tutor_externo', $data['tutor_externo'] ?? null);
        $stmt->bindParam(':tutor_interno_id', $data['tutor_interno_id'] ?? null);
        $stmt->bindParam(':departamento_id', $data['departamento_id'] ?? null);
        $stmt->bindParam(':horas_totales', $data['horas_totales'] ?? 160);
        $stmt->bindParam(':horas_completadas', $data['horas_completadas'] ?? 0);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio'] ?? null);
        $stmt->bindParam(':fecha_fin', $data['fecha_fin'] ?? null);
        $stmt->bindParam(':estado', $data['estado'] ?? 'pendiente');
        $stmt->bindParam(':evaluacion', $data['evaluacion'] ?? null);
        $stmt->bindParam(':observaciones', $data['observaciones'] ?? null);
        
        return $stmt->execute();
    }
    
    // Actualizar práctica (ADAPTADO)
    public function update($id, $data) {
        $query = "UPDATE practicas SET 
                  empresa = :empresa,
                  tutor_externo = :tutor_externo,
                  tutor_interno_id = :tutor_interno_id,
                  departamento_id = :departamento_id,
                  horas_totales = :horas_totales,
                  horas_completadas = :horas_completadas,
                  fecha_inicio = :fecha_inicio,
                  fecha_fin = :fecha_fin,
                  estado = :estado,
                  evaluacion = :evaluacion,
                  observaciones = :observaciones
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':empresa', $data['empresa']);
        $stmt->bindParam(':tutor_externo', $data['tutor_externo'] ?? null);
        $stmt->bindParam(':tutor_interno_id', $data['tutor_interno_id'] ?? null);
        $stmt->bindParam(':departamento_id', $data['departamento_id'] ?? null);
        $stmt->bindParam(':horas_totales', $data['horas_totales'] ?? 160);
        $stmt->bindParam(':horas_completadas', $data['horas_completadas'] ?? 0);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio'] ?? null);
        $stmt->bindParam(':fecha_fin', $data['fecha_fin'] ?? null);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':evaluacion', $data['evaluacion'] ?? null);
        $stmt->bindParam(':observaciones', $data['observaciones'] ?? null);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    // Eliminar práctica
    public function delete($id) {
        $query = "DELETE FROM practicas WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // Obtener estadísticas de prácticas (ADAPTADO)
    public function getStats() {
        $query = "SELECT estado, COUNT(*) as total FROM practicas GROUP BY estado";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar prácticas (ADAPTADO)
    public function search($term, $limit = 10) {
        $query = "SELECT p.*, 
                         u_est.nombre_completo as estudiante_nombre
                  FROM practicas p
                  LEFT JOIN usuarios u_est ON p.estudiante_id = u_est.id
                  WHERE p.empresa LIKE :term OR p.tutor_externo LIKE :term
                  ORDER BY p.fecha_inicio DESC
                  LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $searchTerm = '%' . $term . '%';
        $stmt->bindParam(':term', $searchTerm);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Nuevo método: obtener prácticas por tutor interno
    public function getByTutorInterno($tutor_interno_id) {
        $query = "SELECT p.*, 
                         u_est.nombre_completo as estudiante_nombre
                  FROM practicas p
                  LEFT JOIN usuarios u_est ON p.estudiante_id = u_est.id
                  WHERE p.tutor_interno_id = :tutor_interno_id
                  ORDER BY p.fecha_inicio DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tutor_interno_id', $tutor_interno_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Nuevo método: obtener prácticas por departamento
    public function getByDepartamento($departamento_id) {
        $query = "SELECT p.*, 
                         u_est.nombre_completo as estudiante_nombre,
                         u_tut.nombre_completo as tutor_interno_nombre
                  FROM practicas p
                  LEFT JOIN usuarios u_est ON p.estudiante_id = u_est.id
                  LEFT JOIN usuarios u_tut ON p.tutor_interno_id = u_tut.id
                  WHERE p.departamento_id = :departamento_id
                  ORDER BY p.fecha_inicio DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':departamento_id', $departamento_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Nuevo método: actualizar horas completadas
    public function updateHoras($id, $horas_completadas) {
        $query = "UPDATE practicas SET horas_completadas = :horas_completadas WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':horas_completadas', $horas_completadas);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // Nuevo método: calcular progreso de horas
    public function getProgresoHoras($id) {
        $query = "SELECT horas_totales, horas_completadas FROM practicas WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data && $data['horas_totales'] > 0) {
            $porcentaje = ($data['horas_completadas'] / $data['horas_totales']) * 100;
            return [
                'horas_completadas' => $data['horas_completadas'],
                'horas_totales' => $data['horas_totales'],
                'porcentaje' => round($porcentaje, 2)
            ];
        }
        
        return null;
    }
    
    // Nuevo método: obtener prácticas próximas a finalizar
    public function getProximasFinalizar($dias = 7) {
        $query = "SELECT p.*, 
                         u_est.nombre_completo as estudiante_nombre,
                         u_tut.nombre_completo as tutor_interno_nombre
                  FROM practicas p
                  LEFT JOIN usuarios u_est ON p.estudiante_id = u_est.id
                  LEFT JOIN usuarios u_tut ON p.tutor_interno_id = u_tut.id
                  WHERE p.fecha_fin IS NOT NULL 
                  AND p.fecha_fin BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :dias DAY)
                  AND p.estado NOT IN ('finalizada', 'evaluada')
                  ORDER BY p.fecha_fin ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':dias', $dias, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>