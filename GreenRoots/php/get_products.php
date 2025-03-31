<?php
header('Content-Type: application/json');
require_once 'db_config.php';

$sql = "SELECT p.*, l.latitude, l.longitude 
        FROM products p 
        LEFT JOIN locations l ON p.product_id = l.product_id";
$result = $conn->query($sql);

$products = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[$row['product_id']] = array(
            'name' => $row['name'],
            'location' => [$row['latitude'], $row['longitude']],
            'icon' => $row['icon'],
            'details' => $row['details'],
            'quantity' => $row['quantity'],
            'price' => $row['price'],
            'season' => $row['season']
        );
    }
}

echo json_encode($products);
$conn->close();
?>