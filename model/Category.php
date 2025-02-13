<?php
require_once 'db.php';

class Category {
    private $db;

    public function __construct() {
        $this->db = (new DB())->connect();
    }

    // Add a new category
    public function addCategory($name) {
        try {
            $sql = "INSERT INTO category (name) VALUES (:name)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':name' => $name]);
            return ['success' => true, 'message' => 'Category added successfully.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Update an existing category
    public function updateCategory($categoryId, $name) {
        try {
            // SQL query to update the category in the database
            $sql = "UPDATE category SET name = :name WHERE id = :category_id";
            
            // Prepare and execute the query
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':category_id' => $categoryId
            ]);
            
            // Return success message if update was successful
            return ["success" => true, "message" => "category updated successfully!"];
        } catch (PDOException $e) {
            // Return error message if the update fails
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Fetch a single category by ID
    public function getCategoryById($id) {
        try {
            $sql = "SELECT id, name
                    FROM category 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            return ["success" => true, "category" => $category];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Fetch all categories
    public function getAllCategories() {
        try {
            $sql = "SELECT * FROM category";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getCategoriesCount() {
        try {
            $sql = "SELECT COUNT(*) as total_categories
                    FROM category";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ["success" => true, "total_categories" => $result['total_categories']];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Delete a category by ID
    public function deleteCategory($id) {
        try {
            $sql = "DELETE FROM category WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ["success" => true, "message" => "Category deleted successfully!"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}
