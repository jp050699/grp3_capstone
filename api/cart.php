<?php
header("Content-Type: application/json");
require_once '../model/Cart.php';

$cartModel = new Cart();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id'])) {
    // Fetch all cart items for a user
    $response = $cartModel->getCartItems($_GET['user_id']);
    echo json_encode($response);
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add a product to the cart
    if (isset($_POST['user_id'], $_POST['product_id'], $_POST['quantity'])) {
        $response = $cartModel->addToCart($_POST['user_id'], $_POST['product_id'], $_POST['quantity']);
    } else {
        $response = [
            'success' => false,
            'message' => 'Missing required fields.'
        ];
    }
    echo json_encode($response);
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);
    if (isset($_PUT['cart_id'], $_PUT['quantity'])) {
        $response = $cartModel->updateCartItem($_PUT['cart_id'], $_PUT['quantity']);
    } else {
        $response = [
            'success' => false,
            'message' => 'Cart ID and quantity are required.'
        ];
    }
    echo json_encode($response);
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $inputData = file_get_contents("php://input");
    // Decode the JSON data
    $data = json_decode($inputData, true);
    if (isset($data['cart_id'])) {
        $response = $cartModel->removeCartItem($data['cart_id']);
    } elseif (isset($data['user_id'])) {
        $response = $cartModel->clearCart($data['user_id']);
    } else {
        $response = [
            'success' => false,
            'message' => 'Cart ID or User ID is required.'
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