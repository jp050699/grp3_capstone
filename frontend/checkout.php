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
            $cartItems = $data['cartItems'];
            foreach ($data['cartItems'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }
    } else {
        alert('Can not fetch cart!');
    }
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Checkout</h1>

    <form id="checkoutForm">
        <div class="mb-3">
            <label for="address" class="form-label">Shipping Address</label>
            <input type="text" class="form-control" id="address" required>
        </div>
        
        <div class="mb-3">
            <label for="paymentMethod" class="form-label">Payment Method</label>
            <select class="form-control" id="paymentMethod" required>
                <option value="Credit Card">Credit Card</option>
                <option value="PayPal">PayPal</option>
                <option value="Cash on Delivery">Cash on Delivery</option>
            </select>
        </div>

        <h4 class="mt-4">Order Summary</h4>
        <ul id="orderSummary" class="list-group mb-3"></ul>
        
        <h4 class="mt-2">Total: $<span id="totalPrice"><?php echo number_format($total, 2); ?></span></h4>
        
        <button type="submit" class="btn btn-success w-100 mt-3">Place Order</button>
    </form>
</div>

<script>
document.getElementById('checkoutForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const userId = <?php echo json_encode($userId); ?>;
    const cartItems = <?php echo json_encode($cartItems); ?>;

        const address = document.getElementById('address').value.trim();
        const paymentMethod = document.getElementById('paymentMethod').value;
        const totalPrice = parseFloat(document.getElementById('totalPrice').textContent);

        if (!address) {
            alert('Please enter your shipping address.');
            return;
        }

        const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('cart', JSON.stringify(cartItems));
            formData.append('total_price', totalPrice);
            formData.append('address', address);
            formData.append('payment_method', paymentMethod);

        try {
            const response = await fetch('../api/checkout.php', {
                method: 'POST',
                body: formData,
            });
            const data = await response.json();

            if (data.success) {
                alert('Order placed successfully!');
                window.location.href = 'orders.php';
            } else {
                alert(data.message || 'Order processing failed.');
            }
        } catch (error) {
            console.error('Error processing order:', error);
        }
    });

</script>

<?php include('footer.php'); ?>
