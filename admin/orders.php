<?php include('header.php'); ?>

<div class="container mt-5">
    <h1>Orders</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total Price</th>
                <th>Payment</th>
                <th>Address</th>
                <th>Status</th>
                <th>Ordered At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="orderTableBody">
            <!-- Orders will be dynamically inserted here -->
        </tbody>
    </table>
</div>

<script>
    // Function to fetch and display Orders
    async function fetchOrders() {
        try {
            const response = await fetch('../api/order.php'); // Call the API
            const data = await response.json();

            if (data.success) {
                const tableBody = document.getElementById('orderTableBody');
                tableBody.innerHTML = ''; // Clear the table body

                data.orders.forEach(order => {
                    const row = `
                        <tr>
                            <td>${order.id}</td>
                            <td>${order.customer_name}</td>
                            <td>$${order.total_price}</td>
                            <td>${order.payment_method}</td>
                            <td>${order.address}</td>
                            <td>${order.status}</td>
                            <td>${order.created_at}</td>
                            <td>
                                <a href="order.php?action=edit&order_id=${order.id}" class="btn btn-warning btn-sm">Shipped</a>
                                <button class="btn btn-danger btn-sm" onclick="cancelOrder(${order.id})">Cancel</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                alert(data.message || 'Failed to fetch orders.');
            }
        } catch (error) {
            console.error('Error fetching orders:', error);
            alert('An error occurred while fetching orders.');
        }
    }

    // Function to delete a order
    async function cancelOrder(orderId) {
        const confirmDelete = confirm('Are you sure you want to cancel this order?');
        if (confirmDelete) {
            try {
                const response = await fetch('../api/order.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `order_id=${orderId}`,
                });

                const data = await response.json();
                if (data.success) {
                    alert(data.message); // Display success message
                    fetchOrders(); // Refresh the order list
                } else {
                    alert(data.message || 'Failed to cancel order.');
                }
            } catch (error) {
                console.error('Error deleting order:', error);
                alert('An error occurred while deleting the order.');
            }
        }
    }

    // Fetch orders on page load
    document.addEventListener('DOMContentLoaded', fetchOrders);
</script>

<?php include('footer.php'); ?>
