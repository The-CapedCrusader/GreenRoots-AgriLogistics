<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
require_once 'db_config.php';

// Log incoming data
$logFile = fopen("debug.txt", "a");
fwrite($logFile, "\n\n" . date('Y-m-d H:i:s') . "\n");
fwrite($logFile, "Request received\n");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log raw input
    $raw_data = file_get_contents('php://input');
    fwrite($logFile, "Raw data: " . $raw_data . "\n");
    
    $data = json_decode($raw_data, true);
    fwrite($logFile, "Decoded data: " . print_r($data, true) . "\n");
    
    try {
        // Insert into products table
        $stmt = $conn->prepare("INSERT INTO products (product_id, name, details, quantity, price, season, icon) VALUES (?, ?, ?, ?, ?, ?, '🚀')");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        fwrite($logFile, "Prepared products statement\n");
        
        $stmt->bind_param("ssssss", 
            $data['productId'],
            $data['name'],
            $data['details'],
            $data['quantity'],
            $data['price'],
            $data['season']
        );
        
        fwrite($logFile, "Bound parameters\n");
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        fwrite($logFile, "Executed products insert\n");
        
        // Insert into locations table
        $stmt = $conn->prepare("INSERT INTO locations (product_id, latitude, longitude) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("sdd",
            $data['productId'],
            $data['latitude'],
            $data['longitude']
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        fwrite($logFile, "Executed locations insert\n");
        
        echo json_encode(['success' => true]);
        fwrite($logFile, "Success response sent\n");
    } catch (Exception $e) {
        fwrite($logFile, "Error: " . $e->getMessage() . "\n");
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    fclose($logFile);
}
?>