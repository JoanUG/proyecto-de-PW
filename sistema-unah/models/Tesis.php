<?php
// models/Tesis.php - ADAPTADO A TU ESTRUCTURA REAL

require_once __DIR__ . '/../includes/config/database.php';

class Tesis {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    // Obtener todas las tesis (ADAPTADO)
    public function getAll($filtros = []) {
        $query = "SELECT t.*, 
                         u_est.nombre_completo as estudiante_nombre,
                         u_tut.nombre_completo as tutor_nombre
                  FROM tesis t
                  LEFT JOIN usuarios u_est ON t.estudiante_id = u_est.id
                  LEFT JOIN usuarios u_tut ON t.tutor_id = u_tut.id
                  WHERE 1=1";
        $params = [];
        
        if (!empty($filtros['estudiante_id'])) {
            $query .= " AND t.estudiante_id = :estudiante_id";
            $params[':estudiante_id'] = $filtros['estudiante_id'];
        }
        
        if (!empty($filtros['tutor_id'])) {
            $query .= " AND t.tutor_id = :tutor_id";
            $params[':tutor_id'] = $filtros['tutor_id'];
        }
        
        if (!empty($filtros['estado'])) {
            $query .= " AND t.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }
        
        if (!empty($filtros['departamento_id'])) {
            $query .= " AND t.departamento_id = :departamento_id";
            $params[':departamento_id'] = $filtros['departamento_id'];
        }
        
        if (!empty($filtros['search'])) {
            $query .= " AND (t.titulo LIKE :search OR t.descripcion LIKE :search OR t.linea_investigacion LIKE :search)";
            $params[':search'] = '%' . $filtros['search'] . '%';
        }
        
        // Ordenar por fecha_registro (que es tu columna equivalente a created_at)
        $query .= " ORDER BY t.fecha_registro DESC";
        
        if (!empty($filtros['limit'])) {
            $query .= " LIMIT " . (int)$filtros['limit'];
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener tesis por ID (ADAPTADO)
    public function getById($id) {
        $query = "SELECT t.*, 
                         u_est.nombre_completo as estudiante_nombre,
                         u_est.email as estudiante_email,
                         u_tut.nombre_completo as tutor_nombre,
                         u_tut.email as tutor_email
                  FROM tesis t
                  LEFT JOIN usuarios u_est ON t.estudiante_id = u_est.id
                  LEFT JOIN usuarios u_tut ON t.tutor_id = u_tut.id
                  WHERE t.id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Obtener tesis por estudiante (ADAPTADO)
    public function getByEstudiante($estudiante_id) {
        $query = "SELECT t.*, u.nombre_completo as tutor_nombre 
                  FROM tesis t
                  LEFT JOIN usuarios u ON t.tutor_id = u.id
                  WHERE t.estudiante_id = :estudiante_id
                  ORDER BY t.fecha_registro DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':estudiante_id', $estudiante_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener tesis por tutor (ADAPTADO)
    public function getByTutor($tutor_id) {
        $query = "SELECT t.*, u.nombre_completo as estudiante_nombre 
                  FROM tesis t
                  LEFT JOIN usuarios u ON t.estudiante_id = u.id
                  WHERE t.tutor_id = :tutor_id
                  ORDER BY t.fecha_registro DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tutor_id', $tutor_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Crear nueva tesis (ADAPTADO a tu estructura)
    public function create($data) {
        $query = "INSERT INTO tesis (titulo, descripcion, estudiante_id, tutor_id, departamento_id, 
                  linea_investigacion, estado, fecha_registro, archivo_propuesta)
                  VALUES (:titulo, :descripcion, :estudiante_id, :tutor_id, :departamento_id, 
                  :linea_investigacion, :estado, :fecha_registro, :archivo_propuesta)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':titulo', $data['titulo']);
        $stmt->bindParam(':descripcion', $data['descripcion'] ?? null);
        $stmt->bindParam(':estudiante_id', $data['estudiante_id']);
        $stmt->bindParam(':tutor_id', $data['tutor_id']);
        $stmt->bindParam(':departamento_id', $data['departamento_id'] ?? null);
        $stmt->bindParam(':linea_investigacion', $data['linea_investigacion'] ?? null);
        $stmt->bindParam(':estado', $data['estado'] ?? 'propuesta');
        $stmt->bindParam(':fecha_registro', $data['fecha_registro'] ?? date('Y-m-d'));
        $stmt->bindParam(':archivo_propuesta', $data['archivo_propuesta'] ?? null);
        
        return $stmt->execute();
    }
    
    // Actualizar tesis (ADAPTADO)
    public function update($id, $data) {
        $query = "UPDATE tesis SET 
                  titulo = :titulo, 
                  descripcion = :descripcion, 
                  tutor_id = :tutor_id,
                  departamento_id = :departamento_id,
                  linea_investigacion = :linea_investigacion,
                  estado = :estado,
                  fecha_aprobacion = :fecha_aprobacion,
                  fecha_defensa = :fecha_defensa,
                  calificacion = :calificacion,
                  archivo_propuesta = :archivo_propuesta,
                  archivo_tesis = :archivo_tesis
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':titulo', $data['titulo']);
        $stmt->bindParam(':descripcion', $data['descripcion'] ?? null);
        $stmt->bindParam(':tutor_id', $data['tutor_id']);
        $stmt->bindParam(':departamento_id', $data['departamento_id'] ?? null);
        $stmt->bindParam(':linea_investigacion', $data['linea_investigacion'] ?? null);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':fecha_aprobacion', $data['fecha_aprobacion'] ?? null);
        $stmt->bindParam(':fecha_defensa', $data['fecha_defensa'] ?? null);
        $stmt->bindParam(':calificacion', $data['calificacion'] ?? null);
        $stmt->bindParam(':archivo_propuesta', $data['archivo_propuesta'] ?? null);
        $stmt->bindParam(':archivo_tesis', $data['archivo_tesis'] ?? null);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    // Eliminar tesis (igual)
    public function delete($id) {
        $query = "DELETE FROM tesis WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // Obtener estadísticas de tesis (ADAPTADO)
    public function getStats() {
        $query = "SELECT estado, COUNT(*) as total FROM tesis GROUP BY estado";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar tesis (ADAPTADO)
    public function search($term, $limit = 10) {
        $query = "SELECT t.*, 
                         COALESCE(u_est.nombre_completo, 'Sin asignar') as estudiante_nombre
                  FROM tesis t
                  LEFT JOIN usuarios u_est ON t.estudiante_id = u_est.id
                  WHERE t.titulo LIKE :term OR t.descripcion LIKE :term OR t.linea_investigacion LIKE :term
                  ORDER BY t.fecha_registro DESC
                  LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $searchTerm = '%' . $term . '%';
        $stmt->bindParam(':term', $searchTerm);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Nuevo método: obtener tesis por departamento
    public function getByDepartamento($departamento_id) {
        $query = "SELECT t.*, 
                         u_est.nombre_completo as estudiante_nombre,
                         u_tut.nombre_completo as tutor_nombre
                  FROM tesis t
                  LEFT JOIN usuarios u_est ON t.estudiante_id = u_est.id
                  LEFT JOIN usuarios u_tut ON t.tutor_id = u_tut.id
                  WHERE t.departamento_id = :departamento_id
                  ORDER BY t.fecha_registro DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':departamento_id', $departamento_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Nuevo método: obtener tesis próximas a defender
    public function getProximasDefensa($dias = 30) {
        $query = "SELECT t.*, 
                         u_est.nombre_completo as estudiante_nombre,
                         u_tut.nombre_completo as tutor_nombre
                  FROM tesis t
                  LEFT JOIN usuarios u_est ON t.estudiante_id = u_est.id
                  LEFT JOIN usuarios u_tut ON t.tutor_id = u_tut.id
                  WHERE t.fecha_defensa IS NOT NULL 
                  AND t.fecha_defensa BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :dias DAY)
                  AND t.estado NOT IN ('defendida', 'finalizada', 'aprobada')
                  ORDER BY t.fecha_defensa ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':dias', $dias, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>