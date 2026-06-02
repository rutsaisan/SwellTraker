<?php
session_start();
require_once '../inc/config.php';

function mostrarError($mensaje) {
    echo "<script>alert('$mensaje'); window.history.back();</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = filter_input(INPUT_POST, 'nombre',   FILTER_SANITIZE_SPECIAL_CHARS);
    $email    = filter_input(INPUT_POST, 'email',    FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (empty($nombre) || empty($email) || empty($password)) {
        mostrarError("Por favor, completa todos los campos.");
    }

    if (strlen($password) < 8) {
        mostrarError("La contraseña debe tener al menos 8 caracteres.");
    }

    // Comprobar si el email ya existe
    $sql_check = "SELECT id FROM usuarios WHERE email = ?";
    if ($stmt = $conexion->prepare($sql_check)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            mostrarError("Ese email ya está registrado.");
        }
        $stmt->close();
    }

    // Insertar usuario: SE ELIMINA LA REFERENCIA AL 'ROL'
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql_insert = "INSERT INTO usuarios (nombre, email, password_hash) VALUES (?, ?, ?)";

    if ($stmt = $conexion->prepare($sql_insert)) {
        $stmt->bind_param("sss", $nombre, $email, $hash);

        if ($stmt->execute()) {
            $new_id = $stmt->insert_id;
            $stmt->close();

            // Crear perfil WavePilot
            $prof = $conexion->prepare("INSERT IGNORE INTO wavepilot_perfiles (usuario_id) VALUES (?)");
            $prof->bind_param("i", $new_id);
            $prof->execute();
            $prof->close();

            // Iniciar sesión directamente
            $_SESSION['loggedin'] = true;
            $_SESSION['id']      = $new_id;
            $_SESSION['nombre']  = $nombre;
            $_SESSION['email']   = $email;

            header("Location: ../inicio.php");
            exit;
        } else {
            mostrarError("Error al crear la cuenta. Inténtalo de nuevo.");
        }
    }
    $conexion->close();
}
?>