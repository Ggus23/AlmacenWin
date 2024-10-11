<?php
session_start();
$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];

    if ($role == 'send_material') {
        // Redirige a la página para enviar material
        header("Location: llenar_datos.php");  // Página para enviar material
        exit();
    } elseif ($role == 'control_material') {
        // Redirige a la página del observador
        header("Location: observer.php");  // Página para controlar material
        exit();
    } else {
        $login_error = "Por favor, seleccione una opción válida.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        #container {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
            text-align: center;
        }
        h1 {
            color: #007bff;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        p {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div id="container">
        <h1>Control de Almacén</h1>

        <!-- Mostrar un mensaje de error si el usuario no selecciona ninguna opción -->
        <?php if ($login_error): ?>
            <p><?php echo $login_error; ?></p>
        <?php endif; ?>

        <!-- Formulario para seleccionar si se enviará material o se observará -->
        <form method="POST" action="index.php">
            <div class="form-group">
                <label>¿Qué desea hacer?</label>
                <select name="role" required>
                    <option value="">Seleccione una opción</option>
                    <option value="send_material">Enviar material</option>
                    <option value="control_material">Controlar material</option>
                </select>
            </div>

            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>