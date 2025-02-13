<?php
// model/Product.php
require_once 'db.php';

class Product {
    private $db;

    public function __construct() {
        $this->db = (new DB())->connect();
    }
    public function addProduct($name, $price, $imagePath, $categoryId) {
        try {
            $sql = "INSERT INTO product (name, price, image, category_id) VALUES (:name, :price, :image, :category_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':price' => $price,
                ':image' => $imagePath,
                ':category_id' => $categoryId,
            ]);
            return ["success" => true, "message" => "Product added successfully!"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Update an existing product
    public function updateProduct($productId, $name, $price, $imagePath, $categoryId) {
        try {
            // SQL query to update the product in the database
            $sql = "UPDATE product SET name = :name, price = :price, category_id = :category_id, image = :image WHERE id = :product_id";
            
            // Prepare and execute the query
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':price' => $price,
                ':category_id' => $categoryId,
                ':image' => $imagePath,
                ':product_id' => $productId
            ]);
            
            // Return success message if update was successful
            return ["success" => true, "message" => "Product updated successfully!"];
        } catch (PDOException $e) {
            // Return error message if the update fails
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
    
    // Fetch all products
    public function getAllProducts() {
        try {
            $sql = "SELECT p.id, p.image, p.name, p.price, c.name as category 
                    FROM product p 
                    INNER JOIN category c ON p.category_id = c.id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ["success" => true, "products" => $products];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function getProductsCount() {
        try {
            $sql = "SELECT COUNT(*) as total_products 
                    FROM product";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ["success" => true, "total_products" => $result['total_products']];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
    

    // Fetch a single product by ID
    public function getProductById($id) {
        try {
            $sql = "SELECT p.id, p.image, p.name, p.price, c.name as category , c.id as category_id
                    FROM product p 
                    INNER JOIN category c ON p.category_id = c.id
                    WHERE p.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            return ["success" => true, "product" => $product];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Delete a product by ID
    public function deleteProduct($id) {
        try {
            $sql = "DELETE FROM product WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ["success" => true, "message" => "Product deleted successfully!"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}
?>
