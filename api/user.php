<?php
header("Content-Type: application/json");
require_once '../model/User.php';

$userModel = new User();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'count') {
    $response = $userModel->getUsersCount();
    echo json_encode($response);
    exit();
}else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all users
    $response = $userModel->getAllUsers();
    echo json_encode($response);
    exit();
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    if (isset($_DELETE['user_id'])) {
        $response = $userModel->deleteUser($_DELETE['user_id']);
    } else {
        $response = [
            'success' => false,
            'message' => 'User ID is required.'
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
