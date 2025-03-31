<?php
require_once 'config.php';

try {
    $sql = "SELECT * FROM products ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $packages]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>