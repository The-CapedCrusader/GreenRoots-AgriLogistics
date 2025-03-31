<?php
// ProductManager.php
class ProductManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create new product with location
    public function createProduct($productData, $locationData) {
        try {
            $this->pdo->beginTransaction();

            // Insert product
            $sql = "INSERT INTO products (product_id, name, details, quantity, price, season, icon) 
                    VALUES (:product_id, :name, :details, :quantity, :price, :season, :icon)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($productData);

            // Insert location
            $sql = "INSERT INTO locations (product_id, latitude, longitude, location_name) 
                    VALUES (:product_id, :latitude, :longitude, :location_name)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($locationData);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Read all products with their locations
    public function getAllProducts() {
        $sql = "SELECT p.*, l.latitude, l.longitude, l.location_name, 
                GROUP_CONCAT(d.device_id) as devices
                FROM products p 
                LEFT JOIN locations l ON p.product_id = l.product_id 
                LEFT JOIN devices d ON p.product_id = d.product_id 
                WHERE p.status != 'inactive'
                GROUP BY p.product_id";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Get single product with details
    public function getProduct($productId) {
        $sql = "SELECT p.*, l.latitude, l.longitude, l.location_name
                FROM products p 
                LEFT JOIN locations l ON p.product_id = l.product_id 
                WHERE p.product_id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }

    // Update product and location
    public function updateProduct($productId, $productData, $locationData) {
        try {
            $this->pdo->beginTransaction();

            // Update product
            $sql = "UPDATE products SET 
                    name = :name,
                    details = :details,
                    quantity = :quantity,
                    price = :price,
                    season = :season,
                    icon = :icon
                    WHERE product_id = :product_id";
            
            $productData['product_id'] = $productId;
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($productData);

            // Update location
            $sql = "UPDATE locations SET 
                    latitude = :latitude,
                    longitude = :longitude,
                    location_name = :location_name
                    WHERE product_id = :product_id";
            
            $locationData['product_id'] = $productId;
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($locationData);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Delete product (soft delete)
    public function deleteProduct($productId) {
        $sql = "UPDATE products SET status = 'inactive' WHERE product_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$productId]);
    }

    // Generate new product ID
    public function generateProductId($prefix) {
        $sql = "SELECT product_id FROM products WHERE product_id LIKE ? ORDER BY product_id DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$prefix . '%']);
        $lastId = $stmt->fetch();
        
        if ($lastId) {
            $num = intval(substr($lastId['product_id'], strlen($prefix))) + 1;
        } else {
            $num = 1;
        }
        
        return $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
    }
}
?>