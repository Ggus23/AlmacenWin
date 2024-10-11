<?php
include 'config.php';

// Definir el número de resultados por página
$limite = 10;  // Puedes ajustar este valor según tus necesidades
// Obtener el número de la página actual
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $limite;

try {
    // Consulta con paginación para obtener las solicitudes y sus materiales asociados
    $stmt = $conn->prepare("
        SELECT solicitudes.id, solicitudes.orden, solicitudes.gerencia, solicitudes.fecha,
               materiales.cantidad, materiales.nombre, materiales.marca, materiales.modelo, 
               materiales.activo, materiales.tiempo, materiales.descripcion
        FROM solicitudes
        JOIN materiales ON solicitudes.id = materiales.solicitud_id
        ORDER BY solicitudes.fecha DESC
        LIMIT :limite OFFSET :offset
    ");
    $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $solicitudes = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $solicitudes[$row['id']]['orden'] = $row['orden'];
        $solicitudes[$row['id']]['gerencia'] = $row['gerencia'];
        $solicitudes[$row['id']]['fecha'] = $row['fecha'];
        $solicitudes[$row['id']]['materiales'][] = [
            'cantidad' => $row['cantidad'],
            'nombre' => $row['nombre'],
            'marca' => $row['marca'],
            'modelo' => $row['modelo'],
            'activo' => $row['activo'],
            'tiempo' => $row['tiempo'],
            'descripcion' => $row['descripcion']
        ];
    }

    // Mostrar las solicitudes y materiales
    foreach ($solicitudes as $id => $solicitud) {
        echo "<h2>Orden Nro: " . htmlspecialchars($solicitud['orden']) . "</h2>";
        echo "<h3>Gerencia: " . htmlspecialchars($solicitud['gerencia']) . "</h3>";
        echo "<h3>Fecha: " . htmlspecialchars($solicitud['fecha']) . "</h3>";
        echo "<h3>Materiales</h3>";
        echo '<table border="1">';
        echo '<tr><th>Cantidad</th><th>Nombre</th><th>Marca</th><th>Modelo</th><th>Activo</th><th>Tiempo</th><th>Descripción</th></tr>';
        foreach ($solicitud['materiales'] as $material) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($material['cantidad']) . "</td>";
            echo "<td>" . htmlspecialchars($material['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($material['marca']) . "</td>";
            echo "<td>" . htmlspecialchars($material['modelo']) . "</td>";
            echo "<td>" . htmlspecialchars($material['activo']) . "</td>";
            echo "<td>" . htmlspecialchars($material['tiempo']) . "</td>";
            echo "<td>" . htmlspecialchars($material['descripcion']) . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    }

    // Paginación: obtener el total de solicitudes
    $total_stmt = $conn->query("SELECT COUNT(*) AS total FROM solicitudes");
    $total_filas = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_paginas = ceil($total_filas / $limite);

    // Botones de paginación
    echo "<div>";
    if ($pagina > 1) {
        echo "<a href='?pagina=" . ($pagina - 1) . "'>Anterior</a> | ";
    }
    if ($pagina < $total_paginas) {
        echo "<a href='?pagina=" . ($pagina + 1) . "'>Siguiente</a>";
    }
    echo "</div>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>