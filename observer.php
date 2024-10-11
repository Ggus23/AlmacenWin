<link rel="stylesheet" href="styles2.css">
<?php
include 'config.php';  // Conexión a la base de datos

// Número de resultados por página
$limite = 10;  // Cambia este valor según tus necesidades
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $limite;

try {
    // Obtener las solicitudes, incluyendo las ya observadas
    $stmt = $conn->prepare("
        SELECT solicitudes.id, solicitudes.orden, solicitudes.gerencia, solicitudes.fecha, solicitudes.observado,
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
        $solicitudes[$row['id']]['observado'] = $row['observado'];
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

    echo "<h1>Material que está llegando</h1>";

    // Mostrar los resultados
    foreach ($solicitudes as $id => $solicitud) {
        // Verificamos si ya está observada y aplicamos una clase CSS
        $row_class = $solicitud['observado'] == 1 ? "observado" : "";
        
        echo "<h2>Orden Nro: " . htmlspecialchars($solicitud['orden']) . "</h2>";
        echo "<h3>Gerencia: " . htmlspecialchars($solicitud['gerencia']) . "</h3>";
        echo "<h3>Fecha: " . htmlspecialchars($solicitud['fecha']) . "</h3>";
        echo "<h3>Materiales</h3>";
        echo '<table border="1" class="' . $row_class . '">';
        echo '<tr><th>Cantidad</th><th>Nombre</th><th>Marca</th><th>Modelo</th><th>Activo</th><th>Tiempo</th><th>Descripción</th><th>Acción</th></tr>';
        foreach ($solicitud['materiales'] as $material) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($material['cantidad']) . "</td>";
            echo "<td>" . htmlspecialchars($material['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($material['marca']) . "</td>";
            echo "<td>" . htmlspecialchars($material['modelo']) . "</td>";
            echo "<td>" . htmlspecialchars($material['activo']) . "</td>";
            echo "<td>" . htmlspecialchars($material['tiempo']) . "</td>";
            echo "<td>" . htmlspecialchars($material['descripcion']) . "</td>";
            // Solo mostrar botón de "Marcar como Observado" si no ha sido observado aún
            if ($solicitud['observado'] == 0) {
                echo "<td>
                        <form action='mark_single_observed.php' method='POST'>
                            <input type='hidden' name='solicitud_id' value='" . $id . "'>
                            <button type='submit' style='background-color: #28a745;'>Marcar como Observado</button>
                        </form>
                      </td>";
            } else {
                echo "<td>Observado</td>";
            }
            echo "</tr>";
        }
        echo "</table><br>";
    }

    // Botones de paginación
    echo "<div class='pagination'>";
    if ($pagina > 1) {
        echo "<a href='?pagina=" . ($pagina - 1) . "' class='pagination-button'>Anterior</a>";
    }
        echo "<a href='?pagina=" . ($pagina + 1) . "' class='pagination-button'>Siguiente</a>";
        echo "</div>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!-- Botón para marcar todas las solicitudes como observadas -->
<form action="mark_as_observed.php" method="POST" onsubmit="return confirmarObservado()">
    <button type="submit" style="background-color: #28a745;">Marcar todo como Observado</button>
</form>

<!-- Botón para volver al login -->
<form action="index.php" method="GET">
    <button type="submit" style="background-color: #dc3545;">Volver al Login</button>
</form>

<!-- Confirmación antes de marcar todo como observado -->
<script>
    function confirmarObservado() {
        return confirm("¿Estás seguro de que deseas marcar todo el material como observado?");
    }