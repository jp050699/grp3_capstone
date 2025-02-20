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
                alert(data.message || 'Failed to fetch categories.');
            }
        } catch (error) {
            console.error('Error fetching categories:', error);
            alert('An error occurred while fetching categories.');
        }
    }
    async function fetchProductsCount() {
        try {
            const response = await fetch('../api/user.php?action=count'); // Call the API
            const data = await response.json();

            if (data.success) {
                const userCount = document.getElementById('userCount');
                userCount.innerHTML = data.total_users; // Clear the table body
            } else {
                alert(data.message || 'Failed to fetch categories.');
            }
        } catch (error) {
            console.error('Error fetching categories:', error);
            alert('An error occurred while fetching categories.');
        }
    }
    document.addEventListener('DOMContentLoaded', fetchCategoriesCount);
    document.addEventListener('DOMContentLoaded', fetchProductsCount);
</script>
<?php
include('footer.php');
?>
