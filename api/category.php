<?php
require_once '../model/Category.php';

$categoryModel = new Category();
header('Content-Type: application/json');

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if category_id is provided (update existing category)
    if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
        // Update the category
        $categoryId = $_POST['category_id'];
        $name = $_POST['name'];
        $response = $categoryModel->updateCategory($categoryId, $name);
        echo json_encode($response);
    } else if (isset($_POST['name']) && !empty($_POST['name'])) {
        $response = $categoryModel->addCategory($_POST['name']);
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'Category name is required.']);
    }
    exit();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['category_id'])) {
    $response = $categoryModel->getCategoryById($_GET['category_id']);
    echo json_encode($response);
    exit();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'count') {
    $response = $categoryModel->getCategoriesCount();
    echo json_encode($response);
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all categories
    $categories = $categoryModel->getAllCategories();
    echo json_encode(['success' => true, 'categories' => $categories]);
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    if (isset($_DELETE['category_id'])) {
        $response = $categoryModel->deleteCategory($_DELETE['category_id']);
    } else {
        $response = [
            'success' => false,
            'message' => 'Category ID is required.'
        ];
    }

    echo json_encode($response);
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid request.']);
