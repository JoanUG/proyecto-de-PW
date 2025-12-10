<?php
class Avance {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    // Crear nuevo avance
    public function create($data) {
        $query = "INSERT INTO avances_tesis (tesis_id, porcentaje, descripcion, archivo_path, fecha_avance) 
                  VALUES (:tesis_id, :porcentaje, :descripcion, :archivo_path, :fecha_avance)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tesis_id', $data['tesis_id']);
        $stmt->bindParam(':porcentaje', $data['porcentaje']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':archivo_path', $data['archivo_path']);
        $stmt->bindParam(':fecha_avance', $data['fecha_avance']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    // Obtener avances por tesis
    public function getByTesis($tesis_id) {
        $query = "SELECT * FROM avances_tesis WHERE tesis_id = :tesis_id ORDER BY fecha_avance DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tesis_id', $tesis_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Aprobar avance
    public function aprobar($avance_id, $observaciones = '') {
        $query = "UPDATE avances_tesis SET aprobado = TRUE, observaciones_tutor = :observaciones WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':id', $avance_id);
        return $stmt->execute();
    }
    
    // Rechazar avance
    public function rechazar($avance_id, $observaciones) {
        $query = "UPDATE avances_tesis SET aprobado = FALSE, observaciones_tutor = :observaciones WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':id', $avance_id);
        return $stmt->execute();
    }
    
    // Obt último avance de una tesis
    public function getUltimoAvance($tesis_id) {
        $query = "SELECT * FROM avances_tesis WHERE tesis_id = :tesis_id ORDER BY fecha_avance DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tesis_id', $tesis_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Obtener progreso actual de tesis
    public function getProgreso($tesis_id) {
        $query = "SELECT porcentaje FROM avances_tesis WHERE tesis_id = :tesis_id ORDER BY fecha_avance DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tesis_id', $tesis_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['porcentaje'] : 0;
    }
}
?>