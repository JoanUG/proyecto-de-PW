<?php
// models/Practica.php

require_once __DIR__ . '/../includes/config/database.php';

class Practica {
    private $db;
    
    public function __construct() {
        // Usar Database::getConnection() en lugar de getDB()
        $this->db = Database::getConnection();
    }
    
    // Método para obtener todas las prácticas
    public function getAll($filtros = []) {
        $query = "SELECT p.*, u.nombre_completo as estudiante_nombre
                  FROM practicas p
                  LEFT JOIN usuarios u ON p.estudiante_id = u.id
                  WHERE 1=1";
        $params = [];
        
        if (!empty($filtros['estudiante_id'])) {
            $query .= " AND p.estudiante_id = :estudiante_id";
            $params[':estudiante_id'] = $filtros['estudiante_id'];
        }
        
        if (!empty($filtros['estado'])) {
            $query .= " AND p.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }
        
        if (!empty($filtros['search'])) {
            $query .= " AND (p.empresa LIKE :search OR p.supervisor LIKE :search)";
            $params[':search'] = '%' . $filtros['search'] . '%';
        }
        
        $query .= " ORDER BY p.created_at DESC";
        
        if (!empty($filtros['limit'])) {
            $query .= " LIMIT " . (int)$filtros['limit'];
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener prácticas por estudiante
    public function getByEstudiante($estudiante_id) {
        $query = "SELECT p.* FROM practicas p WHERE p.estudiante_id = :estudiante_id ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':estudiante_id', $estudiante_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener práctica por ID
    public function getById($id) {
        $query = "SELECT p.*, u.nombre_completo as estudiante_nombre, u.email as estudiante_email
                  FROM practicas p
                  LEFT JOIN usuarios u ON p.estudiante_id = u.id
                  WHERE p.id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Crear nueva práctica
    public function create($data) {
        $query = "INSERT INTO practicas (estudiante_id, empresa, supervisor, periodo, estado, calificacion)
                  VALUES (:estudiante_id, :empresa, :supervisor, :periodo, :estado, :calificacion)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':estudiante_id', $data['estudiante_id']);
        $stmt->bindParam(':empresa', $data['empresa']);
        $stmt->bindParam(':supervisor', $data['supervisor']);
        $stmt->bindParam(':periodo', $data['periodo']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':calificacion', $data['calificacion']);
        
        return $stmt->execute();
    }
    
    // Actualizar práctica
    public function update($id, $data) {
        $query = "UPDATE practicas SET empresa = :empresa, supervisor = :supervisor, 
                  periodo = :periodo, estado = :estado, calificacion = :calificacion
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':empresa', $data['empresa']);
        $stmt->bindParam(':supervisor', $data['supervisor']);
        $stmt->bindParam(':periodo', $data['periodo']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':calificacion', $data['calificacion']);
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
}
?>