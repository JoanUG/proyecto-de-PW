<?php
// models/Tesis.php

require_once __DIR__ . '/../includes/config/database.php';

class Tesis {
    private $db;
    
    public function __construct() {
        // Usar Database::getConnection() en lugar de getDB()
        $this->db = Database::getConnection();
    }
    
    // Obtener todas las tesis
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
        
        if (!empty($filtros['search'])) {
            $query .= " AND (t.titulo LIKE :search OR t.descripcion LIKE :search)";
            $params[':search'] = '%' . $filtros['search'] . '%';
        }
        
        $query .= " ORDER BY t.created_at DESC";
        
        if (!empty($filtros['limit'])) {
            $query .= " LIMIT " . (int)$filtros['limit'];
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener tesis por ID
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
    
    // Obtener tesis por estudiante
    public function getByEstudiante($estudiante_id) {
        $query = "SELECT t.*, u.nombre_completo as tutor_nombre 
                  FROM tesis t
                  LEFT JOIN usuarios u ON t.tutor_id = u.id
                  WHERE t.estudiante_id = :estudiante_id
                  ORDER BY t.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':estudiante_id', $estudiante_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener tesis por tutor
    public function getByTutor($tutor_id) {
        $query = "SELECT t.*, u.nombre_completo as estudiante_nombre 
                  FROM tesis t
                  LEFT JOIN usuarios u ON t.estudiante_id = u.id
                  WHERE t.tutor_id = :tutor_id
                  ORDER BY t.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tutor_id', $tutor_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Crear nueva tesis
    public function create($data) {
        $query = "INSERT INTO tesis (titulo, descripcion, estudiante_id, tutor_id, codigo, estado, fecha_inicio)
                  VALUES (:titulo, :descripcion, :estudiante_id, :tutor_id, :codigo, :estado, :fecha_inicio)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':titulo', $data['titulo']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':estudiante_id', $data['estudiante_id']);
        $stmt->bindParam(':tutor_id', $data['tutor_id']);
        $stmt->bindParam(':codigo', $data['codigo']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        
        return $stmt->execute();
    }
    
    // Actualizar tesis
    public function update($id, $data) {
        $query = "UPDATE tesis SET titulo = :titulo, descripcion = :descripcion, 
                  tutor_id = :tutor_id, estado = :estado, fecha_inicio = :fecha_inicio
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':titulo', $data['titulo']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':tutor_id', $data['tutor_id']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    // Eliminar tesis
    public function delete($id) {
        $query = "DELETE FROM tesis WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // Obtener estadísticas de tesis
    public function getStats() {
        $query = "SELECT estado, COUNT(*) as total FROM tesis GROUP BY estado";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>