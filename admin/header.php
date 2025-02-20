<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== 1) {
    header("Location: ../frontend/logout.php"); // Redirect to login page if not logged in
    exit();
}

$username = $_SESSION['username']; // Get the username from the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body class="admin">
    <!-- Navbar -->
    
    <nav class="navbar">
        <div>
            <button class="btn btn-dark sidebar-toggle" onclick="toggleSidebar()">☰</button>
        </div>
        <div class="user-menu">
            <button class="btn btn-light">
                <i class="fa fa-user"></i> Admin <i class="fa fa-caret-down"></i>
            </button>
            <img src="../img/logo.png" alt="Site Logo" class="logo" width="50" height="50" class="d-inline-block align-text-top">
            <div class="dropdown-menu">
                <a href="profile.php" class="dropdown-item">Profile</a>
                <a href="../frontend/logout.php" class="dropdown-item">Logout</a>
            </div>
        </div>
    </nav>
    <div class="d-flex">
        <div class="sidebar" id="sidebar">
            
            
            <a href="dashboard.php"><i class="fa fa-box"></i>Dashboard</a>
            <a href="products.php"><i class="fa fa-box"></i> Products</a>
            <a href="categories.php"><i class="fas fa-shopping-bag"></i> Category</a>
            <a href="#"><i class="fa fa-users"></i> User</a>
        </div>
        <div class="content">
            
        