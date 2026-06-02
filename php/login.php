<?php
session_start();
require_once '../inc/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $sql = "SELECT id, password_hash, nombre FROM usuarios WHERE email = ?";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id_usuario, $pass_bd, $nombre);
            $stmt->fetch();

            if (password_verify($password, $pass_bd)) {
                $_SESSION['loggedin'] = true;
                $_SESSION['id']      = $id_usuario;
                $_SESSION['nombre']  = $nombre;
                $_SESSION['email']   = $email;

                header("Location: ../inicio.php");
                exit;
            } else {
                header("Location: ../index.php?error=1");
                exit;
            }
        } else {
            header("Location: ../index.php?error=2");
            exit;
        }
        $stmt->close();
    }
    $conexion->close();
}
?>
