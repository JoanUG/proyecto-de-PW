<?php
// models/User.php

// Incluir la clase Database desde includes/config/database.php
require_once __DIR__ . '/../includes/config/database.php';

class User {
    private $db;
    
    public function __construct() {
        // Usar el método estático de la clase Database para obtener la conexión
        $this->db = Database::getConnection();
    }
    
    // Autenticar usuario
    public function authenticate($username, $password) {
        $query = "SELECT id, username, password, nombre_completo, email, rol, departamento_id, telefono FROM usuarios WHERE username = :username AND activo = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $user['password'])) {
                unset($user['password']);
                // Renombrar campos para mantener compatibilidad con el resto del código
                $user['nombre'] = $user['nombre_completo'];
                $user['role'] = $user['rol'];
                unset($user['nombre_completo']);
                unset($user['rol']);
                return $user;
            }
        }
        
        return false;
    }
    
    // Obtener usuario por ID
    public function getById($id) {
        $query = "SELECT id, username, nombre_completo as nombre, email, rol as role, created_at, departamento_id, telefono FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Obtener todos los usuarios
    public function getAll($filtros = []) {
        $query = "SELECT id, username, nombre_completo as nombre, email, rol as role, created_at, activo, departamento_id, telefono FROM usuarios WHERE 1=1";
        $params = [];
        
        if (!empty($filtros['role'])) {
            $query .= " AND rol = :role";
            $params[':role'] = $filtros['role'];
        }
        
        if (!empty($filtros['search'])) {
            $query .= " AND (username LIKE :search OR nombre_completo LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $filtros['search'] . '%';
        }
        
        if (!empty($filtros['departamento_id'])) {
            $query .= " AND departamento_id = :departamento_id";
            $params[':departamento_id'] = $filtros['departamento_id'];
        }
        
        $query .= " ORDER BY nombre_completo ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Crear nuevo usuario
    public function create($data) {
        $query = "INSERT INTO usuarios (username, password, nombre_completo, email, rol, departamento_id, telefono) 
                  VALUES (:username, :password, :nombre, :email, :role, :departamento_id, :telefono)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':departamento_id', $data['departamento_id'] ?? null);
        $stmt->bindParam(':telefono', $data['telefono'] ?? null);
        
        return $stmt->execute();
    }
    
    // Actualizar usuario
    public function update($id, $data) {
        $query = "UPDATE usuarios SET nombre_completo = :nombre, email = :email, rol = :role, departamento_id = :departamento_id, telefono = :telefono WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':departamento_id', $data['departamento_id'] ?? null);
        $stmt->bindParam(':telefono', $data['telefono'] ?? null);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    // Cambiar contraseña
    public function changePassword($id, $new_password) {
        $query = "UPDATE usuarios SET password = :password WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':password', password_hash($new_password, PASSWORD_DEFAULT));
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    // Obtener estadísticas de usuarios
    public function getStats() {
        $query = "SELECT 
                    rol as role,
                    COUNT(*) as total,
                    SUM(CASE WHEN activo = 1 THEN 1 ELSE 0 END) as activos,
                    SUM(CASE WHEN activo = 0 THEN 1 ELSE 0 END) as inactivos
                  FROM usuarios 
                  GROUP BY rol";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Nuevo método para obtener usuarios por departamento
    public function getByDepartamento($departamento_id) {
        $query = "SELECT id, username, nombre_completo as nombre, email, rol as role, created_at, telefono FROM usuarios WHERE departamento_id = :departamento_id AND activo = 1 ORDER BY nombre_completo ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':departamento_id', $departamento_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>