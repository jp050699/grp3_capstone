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
