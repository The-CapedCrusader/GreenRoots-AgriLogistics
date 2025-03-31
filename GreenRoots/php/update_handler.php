<?php
require_once 'db_config.php';
require_once 'debug_helper.php';

header('Content-Type: application/json');

try {
    // Log incoming request
    logDebug("Received update package request", $_POST);
    
    if (!isset($_POST['packageId'])) {
        throw new Exception("Package ID is required");
    }
    
    $product_id = $_POST['packageId'];
    $name = $_POST['productType'] ?? null;
    $quantity = $_POST['quantity'] ?? null;
    $status = $_POST['status'] ?? null;
    
    // Build update SQL dynamically based on provided fields
    $updateFields = [];
    $params = [];
    $types = '';
    
    if ($name !== null) {
        $updateFields[] = "name = ?";
        $params[] = $name;
        $types .= 's';
    }
    
    if ($quantity !== null) {
        $updateFields[] = "quantity = ?";
        $params[] = $quantity;
        $types .= 's';
    }
    
    if ($status !== null) {
        $updateFields[] = "status = ?";
        $params[] = $status;
        $types .= 's';
    }
    
    if (empty($updateFields)) {
        throw new Exception("No fields to update");
    }
    
    // Add product_id to parameters
    $params[] = $product_id;
    $types .= 's';
    
    $sql = "UPDATE products SET " . implode(", ", $updateFields) . " WHERE product_id = ?";
    logDebug("Update SQL", $sql);
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        logDebug("SQL Prepare Failed", $conn->error);
        throw new Exception("Failed to prepare statement");
    }
    
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        logDebug("Package updated successfully", [
            'product_id' => $product_id,
            'affected_rows' => $stmt->affected_rows
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Package updated successfully'
        ]);
    } else {
        logDebug("Failed to update package", $stmt->error);
        throw new Exception("Failed to update package");
    }
    
} catch (Exception $e) {
    logDebug("Error in update_package.php", $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$stmt->close();
$conn->close();
?>