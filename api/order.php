<?php
header("Content-Type: application/json");
require_once '../model/Checkout.php';

$checkoutModel = new Checkout();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id'])) {
    // Fetch all products
    $orders = $checkoutModel->getOrderByUserId($_GET['user_id']);
    if ($orders) {
        echo json_encode(['success' => true, 'orders' => $orders]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No orders found.']);
    }
    exit();
} if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'count') {
    $response = $checkoutModel->getOrdersCount();
    echo json_encode($response);
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $orders = $checkoutModel->getAllOrders();
    
    if ($orders) {
        echo json_encode(['success' => true, 'orders' => $orders]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No orders found.']);
    }
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'shipped') {
    parse_str(file_get_contents("php://input"), $_POST);
    if (isset($_POST['order_id'])) {
        $response = $checkoutModel->shipOrder($_POST['order_id']);
    } else {
        $response = [
            'success' => false,
            'message' => 'Order ID is required.'
        ];
    }

    echo json_encode($response);
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    if (isset($_DELETE['order_id'])) {
        $response = $checkoutModel->cancelOrder($_DELETE['order_id']);
    } else {
        $response = [
            'success' => false,
            'message' => 'Order ID is required.'
        ];
    }

    echo json_encode($response);
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
exit();
?>
