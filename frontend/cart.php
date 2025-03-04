<?php include('header.php'); ?>

<?php
$cartItems = []; // Default empty cart
$total = 0;

    $userId = $_SESSION['userId'];
    $apiUrl = "http://localhost/capstone/api/cart.php?user_id=" . $userId;
    $response = @file_get_contents($apiUrl);
    $data = json_decode($response, true);

    if ($data && $data['success'] ){
        if(empty( $data['cartItems'])){
            $emptyCartMessage = true;
        } else {
            $emptyCartMessage = false;
            foreach ($data['cartItems'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }
    } else {
        alert('Can not fetch cart!');
    }
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Your Cart</h1>

    <?php if ($emptyCartMessage): ?>
        <div class="text-center mt-4">
            <h4>Your cart is empty.</h4>
        </div>
    <?php else: ?>
        <div class="row" id="cartContainer">
            <?php foreach ($data['cartItems'] as $item): ?>
                <div class="col-md-12 mb-3 cart-item">
                    <div class="card p-3 shadow-sm d-flex flex-row align-items-center">
                        <img src="../<?php echo $item['image']; ?>" class="cart-img me-3" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 80px; height: 80px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h5 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h5>
                            <p class="text-danger mb-1">$<?php echo number_format($item['price'], 2); ?> x <?php echo $item['quantity']; ?></p>
                            <button class="btn btn-warning btn-lg rounded-pill mt-3" onclick="removeFromCart(<?php echo $item['id']; ?>)">Remove</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4" id="cartSummary">
            <h4>Total: $<?php echo number_format($total, 2); ?></h4>
            <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<script>

    async function removeFromCart(cartId) {
        try {
            const response = await fetch(`../api/cart.php`, {
                method: 'DELETE',
                body: JSON.stringify({ cart_id: cartId }),
                headers: { 'Content-Type': 'application/json' }
            });
            const data = await response.json();
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to remove item.');
            }
        } catch (error) {
            console.error('Error removing item:', error);
        }
    }

</script>

<?php include('footer.php'); ?>
