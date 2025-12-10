<?php
// test_root.php - Probar con usuario root
try {
    $conn = new PDO("mysql:host=localhost;dbname=sistema-unah", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conectado exitosamente como ROOT<br>";
    
    // Ver qué usuarios existen
    $stmt = $conn->query("SELECT user, host FROM mysql.user");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Usuarios en MySQL:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Usuario</th><th>Host</th></tr>";
    foreach ($usuarios as $usuario) {
        echo "<tr>";
        echo "<td>" . $usuario['user'] . "</td>";
        echo "<td>" . $usuario['host'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch(PDOException $e) {
    echo "❌ Error con root: " . $e->getMessage();
}
?>