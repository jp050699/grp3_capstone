<?php
include('header.php');

// Initialize variables
$error = null;
$success = null;
$product = null;  // To hold the product data for edit

// Fetch categories from the API
$categoriesApiUrl = "http://localhost/capstone/api/category.php"; 
$categories = [];
$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET',
    ],
];
$categoriesContext = stream_context_create($options);

// Send the request and handle the response
$categoriesResponse = @file_get_contents($categoriesApiUrl, false, $categoriesContext);

if ($categoriesResponse) {
    $categoriesData = json_decode($categoriesResponse, true);
    if ($categoriesData && $categoriesData['success']) {
        $categories = $categoriesData['categories'];
    }
}

// Check if the action is to edit an existing product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $image = $_FILES['image'];
    $productId = $_POST['product_id'] ?? null;

    // Prepare the API URL based on action
    $apiUrl = "http://localhost/capstone/api/product.php";

    // Handle image upload
    if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../img/products/';
        $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); // Get the original file extension
        $fileName = uniqid() . '.' . $fileExtension; // Generate a unique filename
        $uploadFile = $uploadDir . $fileName;
        if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)){
            $imagePath = 'img/products/' . $fileName;
        } else {
            $error = 'Failed to upload image.';
        }
    } else {
        // If no new image is uploaded, retain the old image path (if editing)
        $imagePath = $_POST['existing_image'] ?? null;
    }

    if ($productId) {
        // If productId is set, update the product
        $postData = [
            'product_id' => $productId,
            'name' => $name,
            'price' => $price,
            'category_id' => $category,
            'imagePath' => $imagePath,
        ];

        $method = 'POST'; // Use POST for both add and update
    } else {
        // If no productId is set, create a new product
        $postData = [
            'name' => $name,
            'price' => $price,
            'category_id' => $category,
            'imagePath' => $imagePath,
        ];

        $method = 'POST';
    }

    $postData = http_build_query($postData);
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => $method,
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
}

// If action is 'edit' and product_id is set, fetch product details
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    $productApiUrl = "http://localhost/capstone/api/product.php?product_id=" . $productId;
    
    $productResponse = @file_get_contents($productApiUrl);
    $productData = json_decode($productResponse, true);

    if ($productData && $productData['success']) {
        $product = $productData['product'];
    }
}
?>

<div class="container mt-5">
    <h1><?php echo isset($product) ? 'Edit Product' : 'Add New Product'; ?></h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <?php if (isset($product)): ?>
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="existing_image" value="<?php echo $product['image']; ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-control" id="category" name="category" required>
                <option value="">Select a Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo isset($product) && $product['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" class="form-control" id="image" name="image">
            <?php if (isset($product) && $product['image']): ?>
                <div class="mt-2">
                    <img src="../<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="100">
                </div>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo isset($product) ? 'Update Product' : 'Add Product'; ?></button>
    </form>
</div>

<?php include('footer.php'); ?>
