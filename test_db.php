<?php
require_once 'inc/config.php';

// Check if user 1 exists, if not create dummy
$res = $conexion->query("SELECT id FROM usuarios LIMIT 1");
if ($res->num_rows == 0) {
    $conexion->query("INSERT INTO usuarios (nombre, email, password_hash) VALUES ('Test', 'test@test.com', 'test')");
}
$uid = $conexion->query("SELECT id FROM usuarios LIMIT 1")->fetch_assoc()['id'];

echo "UID: $uid\n";

$errors = [];

// Test missions
if (!$conexion->query("INSERT IGNORE INTO wavepilot_misiones_completadas (usuario_id, mision_id) VALUES ($uid, 's1')")) {
    $errors[] = "Misiones error: " . $conexion->error;
}

// Test logros
if (!$conexion->query("INSERT IGNORE INTO wavepilot_logros (usuario_id, logro_idx) VALUES ($uid, 1)")) {
    $errors[] = "Logros error: " . $conexion->error;
}

// Test equipo
if (!$conexion->query("INSERT INTO wavepilot_equipo (usuario_id, clave, valor) VALUES ($uid, 'board', 'Test Board') ON DUPLICATE KEY UPDATE valor='Test Board'")) {
    $errors[] = "Equipo error: " . $conexion->error;
}

if (empty($errors)) {
    echo "All OK!";
} else {
    print_r($errors);
}
?>
