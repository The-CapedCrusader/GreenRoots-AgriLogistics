<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "UPDATE products 
                SET name = :productType, 
                    quantity = :quantity, 
                    status = :status, 
                    details = :details,
                    price = :price
                WHERE product_id = :packageId";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':packageId' => $_POST['packageId'],
            ':productType' => $_POST['productType'],
            ':quantity' => $_POST['quantity'],
            ':status' => $_POST['status'],
            ':details' => $_POST['details'] ?? '',
            ':price' => $_POST['price'] ?? '0'
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Package updated successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>