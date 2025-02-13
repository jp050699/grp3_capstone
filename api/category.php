<?php
require_once '../model/Category.php';

$categoryModel = new Category();
header('Content-Type: application/json');

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $response = $categoryModel->addCategory($_POST['name']);
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'Category name is required.']);
    }
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
