<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "INSERT INTO products (product_id, name, details, quantity, price, status) 
                VALUES (:packageId, :productType, :details, :quantity, :price, :status)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':packageId' => $_POST['packageId'],
            ':productType' => $_POST['productType'],
            ':details' => $_POST['details'] ?? '',
            ':quantity' => $_POST['quantity'],
            ':price' => $_POST['price'] ?? '0',
            ':status' => 'active'
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Package created successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>