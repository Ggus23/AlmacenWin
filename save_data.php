<?php
include 'config.php';  // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validar que los campos requeridos no estén vacíos
        if (empty($_POST['orden']) || empty($_POST['gerencia'])) {
            throw new Exception("Faltan campos obligatorios.");
        }

        // Iniciar la transacción
        $conn->beginTransaction();

        // Recibir los datos del formulario
        $orden = $_POST['orden'];
        $gerencia = $_POST['gerencia'];

        // Insertar en la tabla "solicitudes"
        $stmt = $conn->prepare("INSERT INTO solicitudes (orden, gerencia) VALUES (:orden, :gerencia)");
        $stmt->bindParam(':orden', $orden);
        $stmt->bindParam(':gerencia', $gerencia);
        $stmt->execute();

        // Obtener el ID de la última solicitud insertada
        $solicitud_id = $conn->lastInsertId();

        // Insertar los materiales en la tabla "materiales"
        $cantidad = $_POST['cantidad'];
        $nombre = $_POST['nombre'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $activo = $_POST['activo'];
        $tiempo = $_POST['tiempo'];
        $descripcion = $_POST['descripcion'];

        $stmt = $conn->prepare("INSERT INTO materiales (solicitud_id, cantidad, nombre, marca, modelo, activo, tiempo, descripcion)
                                VALUES (:solicitud_id, :cantidad, :nombre, :marca, :modelo, :activo, :tiempo, :descripcion)");

        for ($i = 0; $i < count($cantidad); $i++) {
            $stmt->bindParam(':solicitud_id', $solicitud_id);
            $stmt->bindParam(':cantidad', $cantidad[$i]);
            $stmt->bindParam(':nombre', $nombre[$i]);
            $stmt->bindParam(':marca', $marca[$i]);
            $stmt->bindParam(':modelo', $modelo[$i]);
            $stmt->bindParam(':activo', $activo[$i]);
            $stmt->bindParam(':tiempo', $tiempo[$i]);
            $stmt->bindParam(':descripcion', $descripcion[$i]);
            $stmt->execute();
        }

        // Confirmar la transacción
        $conn->commit();
        echo "Datos guardados correctamente";
    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();
        echo "Error al guardar los datos: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>