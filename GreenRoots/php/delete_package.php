<?php
require_once 'db_config.php';

header('Content-Type: application/json');

if (isset($_POST['packageId'])) {
    $packageId = $_POST['packageId'];
    
    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("s", $packageId);
    
    // Execute the query
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Package deleted successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error deleting package: ' . $conn->error
        ]);
    }
    
    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Package ID not provided'
    ]);
}

$conn->close();
?>