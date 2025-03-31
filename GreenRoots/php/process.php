<?php
// process.php
require_once 'config.php';
require_once 'ProductManager.php';

$productManager = new ProductManager($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        try {
            // Generate product ID based on type
            $productType = $_POST['product_type'];
            $productId = $productManager->generateProductId($productType);
            
            $productData = [
                'product_id' => $productId,
                'name' => $_POST['name'],
                'details' => $_POST['details'],
                'quantity' => $_POST['quantity'],
                'price' => $_POST['price'],
                'season' => $_POST['season'],
                'icon' => $_POST['icon']
            ];
            
            $locationData = [
                'product_id' => $productId,
                'latitude' => $_POST['latitude'],
                'longitude' => $_POST['longitude'],
                'location_name' => $_POST['location_name']
            ];
            
            if ($productManager->createProduct($productData, $locationData)) {
                header('Location: index.php?success=created');
            }
        } catch (Exception $e) {
            header('Location: index.php?error=' . urlencode($e->getMessage()));
        }
    }
    
    if ($action === 'update') {
        try {
            $productId = $_POST['product_id'];
            
            $productData = [
                'name' => $_POST['name'],
                'details' => $_POST['details'],
                'quantity' => $_POST['quantity'],
                'price' => $_POST['price'],
                'season' => $_POST['season'],
                'icon' => $_POST['icon']
            ];
            
            $locationData = [
                'latitude' => $_POST['latitude'],
                'longitude' => $_POST['longitude'],
                'location_name' => $_POST['location_name']
            ];
            
            if ($productManager->updateProduct($productId, $productData, $locationData)) {
                header('Location: index.php?success=updated');
            }
        } catch (Exception $e) {
            header('Location: index.php?error=' . urlencode($e->getMessage()));
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    
    if ($action === 'delete' && isset($_GET['id'])) {
        try {
            $productId = $_GET['id'];
            if ($productManager->deleteProduct($productId)) {
                header('Location: index.php?success=deleted');
            }
        } catch (Exception $e) {
            header('Location: index.php?error=' . urlencode($e->getMessage()));
        }
    }
}

exit();
?>