<?php
// ── Configuración de base de datos ────────────────────────
// Base de datos en máquina virtual
$host = "172.24.103.203";
$user = "swelltracker";
$pass = "SurferAdmin123$";
$db   = "swelltracker";

$conexion = new mysqli($host, $user, $pass, $db);

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
?>
