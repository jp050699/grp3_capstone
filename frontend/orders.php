<?php include('header.php'); ?>

<?php
    $userId = $_SESSION['userId'];
    $apiUrl = "http://localhost/capstone/api/order.php?user_id=" . $userId;
    $response = @file_get_contents($apiUrl);
    $data = json_decode($response, true);
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">My Orders</h1>

    <?php if ($data && $data['success'] && !empty($data['orders'])): ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['orders'] as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                        <td>
                            <span class="badge bg-<?php echo ($order['status'] === 'Shipped') ? 'success' : (($order['status'] === 'Cancelled') ? 'danger' : 'warning'); ?>">
                                <?php echo htmlspecialchars($order['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="order-detail.php?order_id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center">No orders found.</div>
    <?php endif; ?>
</div>

<?php include('footer.php'); ?>
