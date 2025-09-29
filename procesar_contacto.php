<?php
// 1. --- CONFIGURACIÓN DE LA BASE DE DATOS ---
// Reemplaza estos valores con los de tu base de datos
$servername = "localhost"; // Generalmente es "localhost"
$username = "root"; // El usuario de tu base de datos
$password = ""; // La contraseña de tu base de datos (a menudo vacía para 'root' en local)
$dbname = "neoplasticismo_project"; // El nombre de tu base de datos

// 2. --- CREAR LA CONEXIÓN ---
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión falló
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// --- VERIFICAR Y CREAR LA TABLA 'registro' SI NO EXISTE ---
$tableName = 'registro';
$checkTableQuery = "SHOW TABLES LIKE '$tableName'";
$result = $conn->query($checkTableQuery);

if ($result->num_rows == 0) {
    // La tabla no existe, procedemos a crearla
    $sqlCreateTable = "CREATE TABLE registro (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        mensaje TEXT NOT NULL,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (!$conn->query($sqlCreateTable)) {
        die("Error al crear la tabla: " . $conn->error);
    }
}

// 3. --- VERIFICAR SI SE RECIBIERON DATOS POR POST ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 4. --- OBTENER Y SANEAR LOS DATOS DEL FORMULARIO ---
    // htmlspecialchars() previene ataques XSS
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validar que los campos no estén vacíos y el email sea válido
    if (!empty($name) && !empty($email) && !empty($message) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        // 5. --- PREPARAR LA CONSULTA SQL (Previene inyección SQL) ---
        $stmt = $conn->prepare("INSERT INTO registro (nombre, email, mensaje) VALUES (?, ?, ?)");
        
        // "s" indica que los 3 parámetros son de tipo string (cadena)
        $stmt->bind_param("sss", $name, $email, $message);

        // 6. --- EJECUTAR LA CONSULTA ---
        if ($stmt->execute()) {
            // Si fue exitoso, redirige a una página de "gracias" o de vuelta al inicio.
            // Es mejor práctica redirigir que imprimir HTML directamente aquí.
            // Por ejemplo, podrías crear una página gracias.html
            // header("Location: gracias.html");
            // O simplemente volver al inicio:
            header("Location: index.html?status=success");
            exit(); // Es importante llamar a exit() después de una redirección.
        } else {
            // En caso de error, también puedes redirigir con un parámetro de error.
            header("Location: contacto.html?status=error");
            exit();
        }

        // 7. --- CERRAR EL STATEMENT ---
        $stmt->close();
    }
}

// 8. --- CERRAR LA CONEXIÓN A LA BASE DE DATOS ---
$conn->close();
?>