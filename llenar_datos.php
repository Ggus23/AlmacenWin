<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Control de Almacén - Usuario</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="container">
        <!-- Encabezado con logo y título -->
        <div id="title">
            <img src="logoale.jpg" alt="Logo" width="100">
            <h1>SALIDA DE BIENES-MATERIALES</h1>
            <form id="formulario" method="POST">

                <!-- Datos de la solicitud -->
                <h2>2. MATERIAL A RETIRAR</h2>
                <div class="conteiner-grid" id="control-grid">
                    <!-- Columnas de la tabla -->
                    <div class="item"><strong>CANTIDAD</strong></div>
                    <div class="item"><strong>NOMBRE</strong></div>
                    <div class="item"><strong>MARCA</strong></div>
                    <div class="item"><strong>MODELO</strong></div>
                    <div class="item"><strong>TIEMPO AUSENCIA</strong></div>
                    <div class="item"><strong>DESCRIPCIÓN DETALLADA</strong></div>
                    <div class="item"><strong>FECHA DE SALIDA</strong></div>
                    <!-- Primera fila de inputs -->
                    <div class="item"><input type="text" name="cantidad[]" required></div>
                    <div class="item"><input type="text" name="nombre[]" required></div>
                    <div class="item"><input type="text" name="marca[]" required></div>
                    <div class="item"><input type="text" name="modelo[]" required></div>
                    <div class="item"><input type="text" name="activo[]" required></div>
                    <div class="item"><input type="text" name="tiempo[]" required></div>
                    <div class="item"><input type="text" name="descripcion[]" required></div>
                </div>
                <button type="button" id="add-row-button">Agregar más filas</button>

                <!-- Autorización -->
                <h2>3. AUTORIZACIÓN</h2>
                    <div class="form-group">
                        <label for="gerencia">Gerencia:</label>
                        <select id="gerencia" name="gerencia" required>
                            <option value="">Seleccione una opción</option>
                            <option value="Gerencia 1">Almacen 1</option>
                            <option value="Gerencia 2">Almacen 2</option>
                            <option value="Gerencia 2">Almacen 3</option>
                        </select>
                    </div>
                <!-- Botón de envío -->
                <button id="send-button" type="submit">Enviar</button>
            </form>
        </div>
        <!-- Botón para volver al login -->
        <form action="index.php" method="GET">
            <button type="submit" style="background-color: #dc3545;">Volver al Login</button>
        </form>
    </div>

    <script>
        // Añadir filas dinámicamente
        document.getElementById('add-row-button').addEventListener('click', function() {
            const grid = document.getElementById('control-grid');
            const newRow = `
                <div class="item"><input type="text" name="cantidad[]" required></div>
                <div class="item"><input type="text" name="nombre[]" required></div>
                <div class="item"><input type="text" name="marca[]" required></div>
                <div class="item"><input type="text" name="modelo[]" required></div>
                <div class="item"><input type="text" name="activo[]" required></div>
                <div class="item"><input type="text" name="tiempo[]" required></div>
                <div class="item"><input type="text" name="descripcion[]" required></div>
            `;
            grid.insertAdjacentHTML('beforeend', newRow);
        });

        // Enviar datos vía AJAX
        $('#formulario').on('submit', function(e) {
            e.preventDefault();  // Evitar recarga de la página
            $.ajax({
                url: 'save_data.php',  // Script que procesa el formulario
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert('Formulario enviado correctamente');
                }
            });
        });
    </script>
</body>
</html>