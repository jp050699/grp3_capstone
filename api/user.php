<?php
header("Content-Type: application/json");
require_once '../model/User.php';

$userModel = new User();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'count') {
    $response = $userModel->getUsersCount();
    echo json_encode($response);
    exit();
}

echo json_encode([
    'success' => false,
    'message' => 'Invalid request.',
]);

?>
