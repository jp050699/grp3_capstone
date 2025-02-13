<?php include('header.php'); ?>

<div class="container mt-5">
    <h1>Products</h1>
    <a href="product.php" class="btn btn-primary mb-3">Add New Product</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="productTableBody">
            <!-- Products will be dynamically inserted here -->
        </tbody>
    </table>
</div>

<script>
    // Function to fetch and display products
    async function fetchProducts() {
        try {
            const response = await fetch('../api/product.php'); // Call the API
            const data = await response.json();

            if (data.success) {
                const tableBody = document.getElementById('productTableBody');
                tableBody.innerHTML = ''; // Clear the table body

                data.products.forEach(product => {
                    const row = `
                        <tr>
                            <td>${product.id}</td>
                            <td>
                                <img src="../${product.image}" alt="${product.name}" width="50" height="50">
                            </td>
                            <td>${product.name}</td>
                            <td>${product.price}</td>
                            <td>${product.category}</td>
                            <td>
                                <a href="product.php?action=edit&product_id=${product.id}" class="btn btn-warning btn-sm">Edit</a>
                                <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                alert(data.message || 'Failed to fetch products.');
            }
        } catch (error) {
            console.error('Error fetching products:', error);
            alert('An error occurred while fetching products.');
        }
    }

    // Function to delete a product
    async function deleteProduct(productId) {
        const confirmDelete = confirm('Are you sure you want to delete this product?');
        if (confirmDelete) {
            try {
                const response = await fetch('../api/product.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}`,
                });

                const data = await response.json();
                if (data.success) {
                    alert(data.message); // Display success message
                    fetchProducts(); // Refresh the product list
                } else {
                    alert(data.message || 'Failed to delete product.');
                }
            } catch (error) {
                console.error('Error deleting product:', error);
                alert('An error occurred while deleting the product.');
            }
        }
    }

    // Fetch products on page load
    document.addEventListener('DOMContentLoaded', fetchProducts);
</script>

<?php include('footer.php'); ?>
