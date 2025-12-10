<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'sistema-unah';
    private $username = 'root';    // Para XAMPP
    private $password = '';        // Sin contraseña en XAMPP
    private $conn;
    
    public function connect() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET NAMES 'utf8mb4'");
            $this->conn->exec("SET CHARACTER SET utf8mb4");
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
        
        return $this->conn;
    }
    
    public static function getConnection() {
        static $conn = null;
        if ($conn === null) {
            $database = new self();
            $conn = $database->connect();
        }
        return $conn;
    }
}
?>