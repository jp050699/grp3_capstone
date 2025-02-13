<?php
header("Content-Type: application/json");
require_once '../model/Product.php';

$productModel = new Product();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_id'])) {
    // Fetch all products
    $response = $productModel->getProductById($_GET['product_id']);
    echo json_encode($response);
    exit();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all products
    $response = $productModel->getAllProducts();
    echo json_encode($response);
    exit();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if product_id is provided (update existing product)
    if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
        // Update the product
        $productId = $_POST['product_id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];
        $imagePath = $_POST['imagePath'];  // New image or existing image

        $response = $productModel->updateProduct($productId, $name, $price, $imagePath, $category_id );
    } else {
        // Add a new product
        $name = $_POST['name'];
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];
        $imagePath = $_POST['imagePath'];

        $response = $productModel->addProduct($name, $price, $imagePath, $category_id);
    }

    echo json_encode($response);
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    if (isset($_DELETE['product_id'])) {
        $response = $productModel->deleteProduct($_DELETE['product_id']);
    } else {
        $response = [
            'success' => false,
            'message' => 'Product ID is required.'
        ];
    }

    echo json_encode($response);
    exit();
}

echo json_encode([
    'success' => false,
    'message' => 'Invalid request.',
]);

?>


