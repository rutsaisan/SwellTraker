<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../inc/config.php';

header('Content-Type: application/json');

if (empty($_SESSION['loggedin']) || empty($_SESSION['id'])) {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}
$user_id = $_SESSION['id'];

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Get user's favorite spots
    $stmt = $conexion->prepare("SELECT s.* FROM spots s JOIN spots_favoritos sf ON s.id = sf.spot_id WHERE sf.usuario_id = ? ORDER BY sf.añadido_en DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $spots = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($spots);
    exit;
}

if ($method === 'POST') {
    // Add a spot
    $data = json_decode(file_get_contents('php://input'), true);
    $lat = $data['lat'] ?? null;
    $lng = $data['lng'] ?? null;
    $name = $data['name'] ?? 'Spot Personalizado';
    
    if ($lat === null || $lng === null) {
        echo json_encode(['error' => 'Lat/Lng missing']);
        exit;
    }
    
    // Check if spot already exists in spots table by lat/lng
    $stmt = $conexion->prepare("SELECT id FROM spots WHERE ROUND(latitud, 4) = ROUND(?, 4) AND ROUND(longitud, 4) = ROUND(?, 4) LIMIT 1");
    $stmt->bind_param("dd", $lat, $lng);
    $stmt->execute();
    $result = $stmt->get_result();
    $spot = $result->fetch_assoc();
    
    if ($spot) {
        $spot_id = $spot['id'];
    } else {
        $stmt = $conexion->prepare("INSERT INTO spots (nombre, pais, latitud, longitud) VALUES (?, 'Ubicación Personalizada', ?, ?)");
        $stmt->bind_param("sdd", $name, $lat, $lng);
        $stmt->execute();
        $spot_id = $conexion->insert_id;
    }
    
    // Add to favorites
    try {
        $stmt = $conexion->prepare("INSERT IGNORE INTO spots_favoritos (usuario_id, spot_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $spot_id);
        $stmt->execute();
        echo json_encode(['success' => true, 'spot_id' => $spot_id]);
    } catch(Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

if ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $lat = $data['lat'] ?? null;
    $lng = $data['lng'] ?? null;
    
    if ($lat === null || $lng === null) {
        echo json_encode(['error' => 'Lat/Lng missing']);
        exit;
    }

    // Find the spot
    $stmt = $conexion->prepare("SELECT id FROM spots WHERE ROUND(latitud, 4) = ROUND(?, 4) AND ROUND(longitud, 4) = ROUND(?, 4) LIMIT 1");
    $stmt->bind_param("dd", $lat, $lng);
    $stmt->execute();
    $result = $stmt->get_result();
    $spot = $result->fetch_assoc();
    
    if ($spot) {
        $stmt = $conexion->prepare("DELETE FROM spots_favoritos WHERE usuario_id = ? AND spot_id = ?");
        $stmt->bind_param("ii", $user_id, $spot['id']);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Spot not found']);
    }
    exit;
}
