<?php
session_start();
// Simulate logged in user
$_SESSION['loggedin'] = true;
$_SESSION['usuario_id'] = 1; // Assuming admin user is 1

require 'inc/config.php';

// Simulate POST logic
$lat = 39.4795;
$lng = -0.3228;
$name = "La Malvarrosa, Valencia";
$user_id = 1;

try {
    $stmt = $conexion->prepare("SELECT id FROM spots WHERE ROUND(latitud, 4) = ROUND(?, 4) AND ROUND(longitud, 4) = ROUND(?, 4) LIMIT 1");
    if(!$stmt) die("Prepare fail 1: " . $conexion->error);
    $stmt->bind_param("dd", $lat, $lng);
    if(!$stmt->execute()) die("Execute fail 1: " . $stmt->error);
    $result = $stmt->get_result();
    $spot = $result->fetch_assoc();

    if ($spot) {
        $spot_id = $spot['id'];
        echo "Found spot $spot_id\n";
    } else {
        $stmt = $conexion->prepare("INSERT INTO spots (nombre, pais, latitud, longitud) VALUES (?, 'Ubicación Personalizada', ?, ?)");
        if(!$stmt) die("Prepare fail 2: " . $conexion->error);
        $stmt->bind_param("sdd", $name, $lat, $lng);
        if(!$stmt->execute()) die("Execute fail 2: " . $stmt->error);
        $spot_id = $conexion->insert_id;
        echo "Inserted spot $spot_id\n";
    }

    $stmt = $conexion->prepare("INSERT IGNORE INTO spots_favoritos (usuario_id, spot_id) VALUES (?, ?)");
    if(!$stmt) die("Prepare fail 3: " . $conexion->error);
    $stmt->bind_param("ii", $user_id, $spot_id);
    if(!$stmt->execute()) die("Execute fail 3: " . $stmt->error);
    echo "Inserted favorite successfully\n";

    // Now test GET logic
    $stmt = $conexion->prepare("SELECT s.* FROM spots s JOIN spots_favoritos sf ON s.id = sf.spot_id WHERE sf.usuario_id = ? ORDER BY sf.añadido_en DESC");
    if(!$stmt) die("Prepare fail 4: " . $conexion->error);
    $stmt->bind_param("i", $user_id);
    if(!$stmt->execute()) die("Execute fail 4: " . $stmt->error);
    $result = $stmt->get_result();
    $spots = $result->fetch_all(MYSQLI_ASSOC);
    var_dump($spots);
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}
