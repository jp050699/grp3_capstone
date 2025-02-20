<?php include('header.php'); ?>

<?php
// Fetch product details
$product = null;
$error = null;
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $apiUrl = "http://localhost/capstone/api/product.php?product_id=" . $productId;
    $response = @file_get_contents($apiUrl);
    $data = json_decode($response, true);

    if ($data && $data['success']) {
        $product = $data['product'];
    } else {
        $error = 'Product not found.';
    }
} else {
    $error = 'No product selected.';
}
?>

<div class="container mt-5">
    <?php if ($error): ?>
        <div class="alert alert-danger text-center"> <?php echo htmlspecialchars($error); ?> </div>
    <?php else: ?>
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="row g-0">
                <div class="col-md-6">
                    <img src="../<?php echo $product['image']; ?>" class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="col-md-6 d-flex flex-column justify-content-center p-4">
                    <h1 class="text-primary"> <?php echo htmlspecialchars($product['name']); ?> </h1>
                    <h3 class="text-danger">$<?php echo number_format($product['price'], 2); ?></h3>
                    <p class="text-muted">Category: <?php echo htmlspecialchars($product['category']); ?></p>
                    <a href="cart.php?action=add&product_id=<?php echo $product['id']; ?>" class="btn btn-success btn-lg rounded-pill mt-3">Add to Cart</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        max-width: 800px;
        margin: auto;
    }
</style>

<?php include('footer.php'); ?>
