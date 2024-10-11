<?php
$host = "localhost";
$dbname = "control_almacen";
$username = "root";
$password = ""; // Cambia esta contraseña si es diferente en tu sistema

// Crear conexión
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configurar PDO para mostrar errores
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Conexión fallida: " . $e->getMessage();
}
?>