<?php
require 'inc/config.php';

try {
    $stmt = $pdo->prepare('SELECT * FROM spots_favoritos');
    $stmt->execute();
    var_dump($stmt->fetchAll());
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
