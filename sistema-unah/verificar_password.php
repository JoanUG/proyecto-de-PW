<?php
// verificar_password.php
echo "<h2>Verificador de Contraseñas - Sistema UNAH</h2>";
echo "<hr>";

// 1. Primero, verificar la conexión a la base de datos
echo "<h3>1. Probando conexión a la base de datos:</h3>";

try {
    require_once 'includes/config/database.php';
    $db = Database::getConnection();
    echo "✅ Conexión a MySQL exitosa<br>";
    
    // Verificar qué base de datos estamos usando
    $result = $db->query("SELECT DATABASE() as db");
    $db_name = $result->fetch(PDO::FETCH_ASSOC)['db'];
    echo "✅ Base de datos conectada: <strong>$db_name</strong><br>";
    
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
    die();
}

echo "<hr>";

// 2. Verificar el usuario 'admin' en la base de datos
echo "<h3>2. Buscando usuario 'admin' en la tabla usuarios:</h3>";

$stmt = $db->prepare("SELECT * FROM usuarios WHERE username = 'admin'");
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin) {
    echo "✅ Usuario 'admin' encontrado!<br>";
    echo "<pre>";
    echo "ID: " . $admin['id'] . "\n";
    echo "Username: " . $admin['username'] . "\n";
    echo "Nombre: " . $admin['nombre_completo'] . "\n";
    echo "Email: " . $admin['email'] . "\n";
    echo "Rol: " . $admin['rol'] . "\n";
    echo "Activo: " . ($admin['activo'] ? 'Sí' : 'No') . "\n";
    echo "Contraseña (hash): " . $admin['password'] . "\n";
    echo "Longitud del hash: " . strlen($admin['password']) . " caracteres\n";
    echo "</pre>";
    
    echo "<hr>";
    echo "<h3>3. Probando contraseñas contra el hash almacenado:</h3>";
    
    // Probar diferentes contraseñas
    $passwords_to_test = [
        'unah2024',
        '1234',
        'password',
        'admin',
        'admin123',
        'unah2023',
        'unah2025',
        'UNah2024',
        'UNAH2024',
        'Admin2024'
    ];
    
    $encontrada = false;
    foreach ($passwords_to_test as $password) {
        if (password_verify($password, $admin['password'])) {
            echo "✅ <span style='color: green; font-weight: bold;'>¡CONTRASEÑA ENCONTRADA!</span><br>";
            echo "   La contraseña del usuario 'admin' es: <strong>$password</strong><br>";
            $encontrada = true;
            break;
        }
    }
    
    if (!$encontrada) {
        echo "❌ Ninguna de las contraseñas comunes coincide.<br>";
        echo "   El hash actual no corresponde a las contraseñas probadas.<br>";
    }
    
} else {
    echo "❌ Usuario 'admin' NO encontrado en la tabla usuarios.<br>";
    echo "   Puede que:<br>";
    echo "   1. La tabla 'usuarios' no exista<br>";
    echo "   2. El usuario 'admin' no esté registrado<br>";
    echo "   3. Estés conectado a la base de datos incorrecta<br>";
}

echo "<hr>";

// 4. Mostrar todos los usuarios disponibles
echo "<h3>4. Todos los usuarios en el sistema:</h3>";

$stmt = $db->query("SELECT id, username, nombre_completo, rol, activo FROM usuarios ORDER BY id");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($usuarios) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Username</th><th>Nombre</th><th>Rol</th><th>Activo</th></tr>";
    foreach ($usuarios as $usuario) {
        echo "<tr>";
        echo "<td>" . $usuario['id'] . "</td>";
        echo "<td>" . $usuario['username'] . "</td>";
        echo "<td>" . $usuario['nombre_completo'] . "</td>";
        echo "<td>" . $usuario['rol'] . "</td>";
        echo "<td>" . ($usuario['activo'] ? 'Sí' : 'No') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "❌ No hay usuarios en la tabla 'usuarios'.<br>";
}

echo "<hr>";

// 5. Generar nuevo hash para 'unah2024'
echo "<h3>5. Generando nuevo hash para 'unah2024':</h3>";
$nuevo_hash = password_hash('unah2024', PASSWORD_DEFAULT);
echo "Contraseña: <strong>unah2024</strong><br>";
echo "Nuevo hash: <code style='word-break: break-all;'>$nuevo_hash</code><br>";

// 6. SQL para actualizar la contraseña
echo "<h3>6. SQL para actualizar la contraseña del admin:</h3>";
echo "Copia y pega ESTE código SQL en phpMyAdmin:<br>";
echo "<textarea rows='3' cols='100' style='margin: 10px 0; padding: 10px;'>";
echo "UPDATE usuarios SET password = '$nuevo_hash' WHERE username = 'admin';";
echo "</textarea>";

echo "<hr>";
echo "<h3>🎯 Resumen:</h3>";
echo "1. Ejecuta este script primero para ver qué está pasando<br>";
echo "2. Copia el SQL del punto 6<br>";
echo "3. Ve a phpMyAdmin y ejecuta ese SQL<br>";
echo "4. Prueba login con admin / unah2024<br>";
?>