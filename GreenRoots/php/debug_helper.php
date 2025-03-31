<?php
// debug_helper.php

function logDebug($message, $data = null) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}\n";
    
    if ($data !== null) {
        $logMessage .= "Data: " . print_r($data, true) . "\n";
    }
    
    file_put_contents('debug.txt', $logMessage, FILE_APPEND);
}

function checkDatabaseConnection($conn) {
    if ($conn->connect_error) {
        logDebug("Database Connection Failed", $conn->connect_error);
        return false;
    }
    return true;
}

function validatePackageData($data) {
    $required_fields = ['product_id', 'name', 'quantity', 'status'];
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        logDebug("Missing Required Fields", $missing_fields);
        return false;
    }
    
    return true;
}
?>