<?php
include('header.php');
?>
    <h2>Welcome to Dashboard</h2>
            <div class="dashboard-cards">
                <div class="card">
                    <h3><i class="fa fa-box"></i> Products</h3>
                    <p id="productCount">0</p>
                </div>
                <div class="card">
                    <h3><i class="fa fa-list"></i> Categories</h3>
                    <p id="categoryCount">0</p>
                </div>
                <div class="card">
                    <h3><i class="fa fa-users"></i> Users</h3>
                    <p id="userCount">0</p>
                </div>
                <div class="card">
                    <h3><i class="fa fa-box-open"></i> Orders</h3>
                    <p id="orderCount">0</p>
                </div>
            </div>

<script>
    // Function to fetch and display Categories
    async function fetchCategoriesCount() {
        try {
            const response = await fetch('../api/category.php?action=count'); // Call the API
            const data = await response.json();

            if (data.success) {
                const categoryCount = document.getElementById('categoryCount');
                categoryCount.innerHTML = data.total_categories; // Clear the table body
            } else {
                alert(data.message || 'Failed to fetch categories.');
            }
        } catch (error) {
            console.error('Error fetching categories:', error);
            alert('An error occurred while fetching categories.');
        }
    }
    async function fetchProductsCount() {
        try {
            const response = await fetch('../api/product.php?action=count'); // Call the API
            const data = await response.json();

            if (data.success) {
                const productCount = document.getElementById('productCount');
                productCount.innerHTML = data.total_products; // Clear the table body
            } else {
                alert(data.message || 'Failed to fetch products.');
            }
        } catch (error) {
            console.error('Error fetching products:', error);
            alert('An error occurred while fetching products.');
        }
    }
    async function fetchUsersCount() {
        try {
            const response = await fetch('../api/user.php?action=count'); // Call the API
            const data = await response.json();

            if (data.success) {
                const userCount = document.getElementById('userCount');
                userCount.innerHTML = data.total_users; // Clear the table body
            } else {
                alert(data.message || 'Failed to fetch users.');
            }
        } catch (error) {
            console.error('Error fetching users:', error);
            alert('An error occurred while fetching users.');
        }
    }
    async function fetchOrdersCount() {
        try {
            const response = await fetch('../api/order.php?action=count'); // Call the API
            const data = await response.json();

            if (data.success) {
                const orderCount = document.getElementById('orderCount');
                orderCount.innerHTML = data.total_orders; // Clear the table body
            } else {
                alert(data.message || 'Failed to fetch orders.');
            }
        } catch (error) {
            console.error('Error fetching orders:', error);
            alert('An error occurred while fetching orders.');
        }
    }
    document.addEventListener('DOMContentLoaded', fetchCategoriesCount);
    document.addEventListener('DOMContentLoaded', fetchProductsCount);
    document.addEventListener('DOMContentLoaded', fetchUsersCount);
    document.addEventListener('DOMContentLoaded', fetchOrdersCount);
</script>
<?php
include('footer.php');
?>
