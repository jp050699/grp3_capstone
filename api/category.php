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
}

echo json_encode(['success' => false, 'message' => 'Invalid request.']);
