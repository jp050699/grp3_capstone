<?php
header("Content-Type: application/json");
require_once '../model/Checkout.php';

$checkoutModel = new Checkout();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['user_id'], $_POST['cart'], $_POST['total_price'], $_POST['address'], $_POST['payment_method'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit();
    }

    $response = $checkoutModel->createOrder($_POST['user_id'], json_decode($_POST['cart'],true), $_POST['total_price'], $_POST['address'], $_POST['payment_method']);
    echo json_encode($response);
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
?>
