<?php
include('header.php');

// Initialize variables
$error = null;
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $categoryId = $_POST['category_id'] ?? null;
    if (!empty($name)) {
        
    $apiUrl = "http://localhost/capstone/api/category.php"; 

    if ($categoryId) {
        // If categoryId is set, update the category
        $postData = [
            'category_id' => $categoryId,
            'name' => $name,
        ];
    } else {
        // If no categoryId is set, create a new category
        $postData = [
            'name' => $name,
        ];
    }
    // Prepare POST data
    $postData = http_build_query($postData);

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

// If action is 'edit' and category_id is set, fetch category details
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['category_id'])) {
    $categoryId = $_GET['category_id'];
    $categoryApiUrl = "http://localhost/capstone/api/category.php?category_id=" . $categoryId;
    
    $categoryResponse = @file_get_contents($categoryApiUrl);
    $categoryData = json_decode($categoryResponse, true);

    if ($categoryData && $categoryData['success']) {
        $category = $categoryData['category'];
    }
}
?>

<div class="container mt-5">
<h1><?php echo isset($category) ? 'Edit category' : 'Add New category'; ?></h1>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <form method="POST">
    <?php if (isset($category)): ?>
            <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
        <?php endif; ?>
        <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($category['name'] ?? ''); ?>"required>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo isset($category) ? 'Update Category' : 'Add Category'; ?></button>
    </form>
</div>

<?php include('footer.php'); ?>
