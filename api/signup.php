<?php
// api/signup.php
require_once '../model/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $email && $phone && $password) {
        $user = new User();
        $response = $user->signup($username, $email, $phone, $password);
        echo json_encode($response);
    } else {
        echo json_encode(["success" => false, "message" => "All fields are required!"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method!"]);
}
?>
