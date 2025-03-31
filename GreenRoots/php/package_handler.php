<?php
require_once 'db_config.php';
require_once 'debug_helper.php';

header('Content-Type: application/json');

try {
    // Log incoming request
    logDebug("Received create package request", $_POST);
    
    // Validate required fields
    if (!validatePackageData($_POST)) {
        throw new Exception("Missing required fields");
    }
    
    $product_id = $_POST['packageId'];
    $name = $_POST['productType'];
    $quantity = $_POST['quantity'];
    $status = $_POST['status'];
    
    // Check database connection
    if (!checkDatabaseConnection($conn)) {
        throw new Exception("Database connection failed");
    }
    
    // Prepare SQL statement
    $sql = "INSERT INTO products (product_id, name, quantity, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        logDebug("SQL Prepare Failed", $conn->error);
        throw new Exception("Failed to prepare statement");
    }
    
    $stmt->bind_param("ssss", $product_id, $name, $quantity, $status);
    
    // Execute and log result
    if ($stmt->execute()) {
        logDebug("Package created successfully", [
            'product_id' => $product_id,
            'affected_rows' => $stmt->affected_rows
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Package created successfully'
        ]);
    } else {
        logDebug("Failed to create package", $stmt->error);
        throw new Exception("Failed to create package");
    }
    
} catch (Exception $e) {
    logDebug("Error in create_package.php", $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$stmt->close();
$conn->close();
?>