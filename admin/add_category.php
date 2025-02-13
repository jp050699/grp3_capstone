<?php
include('header.php');

// Initialize variables
$error = null;
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    if (!empty($name)) {
        
    $apiUrl = "http://localhost/capstone/api/category.php"; 

    // Prepare POST data
    $postData = http_build_query([
        'name' => $name
    ]);

    // Create a stream context
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => $postData,
        ],
    ];
    $context = stream_context_create($options);

    // Send the request and handle the response
    $response = @file_get_contents($apiUrl, false, $context);

        $responseDecoded = json_decode($response, true);
        if ($responseDecoded && $responseDecoded['success']) {
            $success = $responseDecoded['message'];
        } else {
            $error = $responseDecoded['message'] ?? 'Something went wrong. Please try again.';
        }
    } else {
        $error = 'Category name is required.';
    }
}
?>

<div class="container mt-5">
    <h1>Add New Category</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Category</button>
    </form>
</div>

<?php include('footer.php'); ?>
