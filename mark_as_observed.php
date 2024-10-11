<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $solicitud_id = $_POST['solicitud_id'];
    try {
        // Marcar solo la solicitud seleccionada como observada
        $stmt = $conn->prepare("UPDATE solicitudes SET observado = 1 WHERE id = :solicitud_id");
        $stmt->bindParam(':solicitud_id', $solicitud_id);
        $stmt->execute();

        // Redirigir de vuelta a observer.php
        header("Location: observer.php");
        exit();
    } catch (PDOException $e) {
        echo "Error al marcar la solicitud como observada: " . $e->getMessage();
    }
}
?>