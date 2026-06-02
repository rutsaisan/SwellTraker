<?php
/**
 * inc/auth.php — Guard de sesión compartido
 * Incluir en TODAS las páginas protegidas.
 */
if (session_status() === PHP_SESSION_NONE) session_start();

if (empty($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}
?>
