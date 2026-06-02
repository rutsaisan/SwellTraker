<?php
/**
 * api/wavepilot.php
 * API JSON para guardar y cargar el estado del WavePilot por usuario.
 * Todas las rutas requieren sesión activa.
 */

if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json; charset=utf-8');

/* ── Auth ── */
if (empty($_SESSION['loggedin'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

require_once '../inc/config.php';
$uid = (int) $_SESSION['id'];

$action = $_GET['action'] ?? $_POST['action'] ?? '';

/* ── Asegurar que existe el perfil ── */
function ensureProfile(mysqli $db, int $uid): void {
    $stmt = $db->prepare(
        'INSERT IGNORE INTO wavepilot_perfiles (usuario_id) VALUES (?)'
    );
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $stmt->close();
}

/* ═══════════════════════════════════════════════════════════
   GET: cargar estado completo del usuario
════════════════════════════════════════════════════════════ */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'load') {

    ensureProfile($conexion, $uid);

    /* Perfil */
    $stmt = $conexion->prepare(
        'SELECT personaje, xp, nivel, xp_para_siguiente, sesiones_surf
         FROM wavepilot_perfiles WHERE usuario_id = ?'
    );
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $perfil = $stmt->get_result()->fetch_assoc() ?? [];
    $stmt->close();

    /* Misiones completadas */
    $stmt = $conexion->prepare(
        'SELECT mision_id FROM wavepilot_misiones_completadas WHERE usuario_id = ?'
    );
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $misiones = array_column($rows, 'mision_id');

    /* Equipo */
    $stmt = $conexion->prepare(
        'SELECT clave, valor FROM wavepilot_equipo WHERE usuario_id = ?'
    );
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $equipo = [];
    foreach ($rows as $r) $equipo[$r['clave']] = $r['valor'];

    /* Logros */
    $stmt = $conexion->prepare(
        'SELECT logro_idx FROM wavepilot_logros WHERE usuario_id = ?'
    );
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $logros = array_column($rows, 'logro_idx');

    echo json_encode([
        'ok'       => true,
        'perfil'   => $perfil,
        'misiones' => $misiones,
        'equipo'   => $equipo,
        'logros'   => $logros,
    ]);
    exit;
}

/* ═══════════════════════════════════════════════════════════
   POST: guardar cambios
════════════════════════════════════════════════════════════ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $body = json_decode(file_get_contents('php://input'), true) ?? [];
    $act  = $body['action'] ?? $action;

    ensureProfile($conexion, $uid);

    /* ── Actualizar perfil (XP, nivel, personaje, sesiones) ── */
    if ($act === 'save_perfil') {
        $stmt = $conexion->prepare(
            'UPDATE wavepilot_perfiles
             SET personaje=?, xp=?, nivel=?, xp_para_siguiente=?, sesiones_surf=?
             WHERE usuario_id=?'
        );
        $stmt->bind_param(
            'iiiiii',
            $body['personaje'],
            $body['xp'],
            $body['nivel'],
            $body['xp_para_siguiente'],
            $body['sesiones_surf'],
            $uid
        );
        $ok = $stmt->execute();
        $stmt->close();
        echo json_encode(['ok' => $ok]);
        exit;
    }

    /* ── Completar misión ── */
    if ($act === 'complete_mision') {
        $id = $body['mision_id'] ?? '';
        if (!$id) { echo json_encode(['ok' => false, 'error' => 'mision_id requerido']); exit; }

        $stmt = $conexion->prepare(
            'INSERT IGNORE INTO wavepilot_misiones_completadas (usuario_id, mision_id) VALUES (?, ?)'
        );
        $stmt->bind_param('is', $uid, $id);
        $ok = $stmt->execute();
        $stmt->close();
        echo json_encode(['ok' => $ok]);
        exit;
    }

    /* ── Guardar equipo ── */
    if ($act === 'save_equipo') {
        $clave = $body['clave'] ?? '';
        $valor = $body['valor'] ?? '';
        if (!$clave) { echo json_encode(['ok' => false, 'error' => 'clave requerida']); exit; }

        $stmt = $conexion->prepare(
            'INSERT INTO wavepilot_equipo (usuario_id, clave, valor)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE valor=VALUES(valor)'
        );
        $stmt->bind_param('iss', $uid, $clave, $valor);
        $ok = $stmt->execute();
        $stmt->close();
        echo json_encode(['ok' => $ok]);
        exit;
    }

    /* ── Desbloquear logro ── */
    if ($act === 'unlock_logro') {
        $idx = (int)($body['logro_idx'] ?? -1);
        if ($idx < 0) { echo json_encode(['ok' => false]); exit; }

        $stmt = $conexion->prepare(
            'INSERT IGNORE INTO wavepilot_logros (usuario_id, logro_idx) VALUES (?, ?)'
        );
        $stmt->bind_param('ii', $uid, $idx);
        $ok = $stmt->execute();
        $stmt->close();
        echo json_encode(['ok' => $ok]);
        exit;
    }

    /* ── Resetear progreso ── */
    if ($act === 'reset') {
        $conexion->query("DELETE FROM wavepilot_misiones_completadas WHERE usuario_id = $uid");
        $conexion->query("DELETE FROM wavepilot_equipo WHERE usuario_id = $uid");
        $conexion->query("DELETE FROM wavepilot_logros WHERE usuario_id = $uid");
        $conexion->query("UPDATE wavepilot_perfiles SET personaje=0, xp=0, nivel=1, xp_para_siguiente=100, sesiones_surf=0 WHERE usuario_id = $uid");

        echo json_encode(['ok' => true]);
        exit;
    }
}

http_response_code(400);
echo json_encode(['error' => 'Acción no reconocida']);
