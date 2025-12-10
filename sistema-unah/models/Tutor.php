<?php
class Tutor {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    // Obtener todos los tutores (profesores)
    public function getAll() {
        $query = "SELECT * FROM usuarios WHERE role = 'profesor' AND activo = 1 ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener tutor por ID
    public function getById($id) {
        $query = "SELECT * FROM usuarios WHERE id = :id AND role = 'profesor'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Obtener carga de trabajo de un tutor
    public function getCargaTrabajo($tutor_id) {
        $query = "SELECT 
                    COUNT(*) as total_tesis,
                    SUM(CASE WHEN estado = 'en_proceso' THEN 1 ELSE 0 END) as tesis_en_proceso,
                    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as tesis_pendientes
                  FROM tesis 
                  WHERE tutor_id = :tutor_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tutor_id', $tutor_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Asignar tutor a tesis
    public function asignarTesis($tesis_id, $tutor_id) {
        $query = "UPDATE tesis SET tutor_id = :tutor_id WHERE id = :tesis_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tutor_id', $tutor_id);
        $stmt->bindParam(':tesis_id', $tesis_id);
        return $stmt->execute();
    }
    
    // Obtener tesis asignadas a un tutor
    public function getTesisAsignadas($tutor_id) {
        $query = "SELECT t.*, e.nombre as estudiante_nombre 
                  FROM tesis t
                  LEFT JOIN usuarios e ON t.estudiante_id = e.id
                  WHERE t.tutor_id = :tutor_id
                  ORDER BY t.estado, t.fecha_inicio DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tutor_id', $tutor_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener estadísticas de tutores
    public function getEstadisticas() {
        $query = "SELECT 
                    u.id,
                    u.nombre,
                    COUNT(t.id) as total_tesis,
                    AVG(CASE WHEN t.estado = 'finalizada' THEN DATEDIFF(t.fecha_fin, t.fecha_inicio) END) as duracion_promedio
                  FROM usuarios u
                  LEFT JOIN tesis t ON u.id = t.tutor_id
                  WHERE u.role = 'profesor'
                  GROUP BY u.id, u.nombre
                  ORDER BY total_tesis DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>