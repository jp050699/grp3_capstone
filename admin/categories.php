<?php include('header.php'); ?>

<div class="container mt-5">
    <h1>Products</h1>
    <a href="category.php" class="btn btn-primary mb-3">Add New Category</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody id="categoryTableBody">
            <!-- Products will be dynamically inserted here -->
        </tbody>
    </table>
</div>

<script>
    // Function to fetch and display Categories
    async function fetchCategories() {
        try {
            const response = await fetch('../api/category.php'); // Call the API
            const data = await response.json();

            if (data.success) {
                const tableBody = document.getElementById('categoryTableBody');
                tableBody.innerHTML = ''; // Clear the table body

                data.categories.forEach(category => {
                    const row = `
                        <tr>
                            <td>${category.id}</td>
                            <td>${category.name}</td>
                            <td>
                                <a href="category.php?action=edit&category_id=${category.id}" class="btn btn-warning btn-sm">Edit</a>
                                <button class="btn btn-danger btn-sm" onclick="deleteCategory(${category.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                alert(data.message || 'Failed to fetch categories.');
            }
        } catch (error) {
            console.error('Error fetching categories:', error);
            alert('An error occurred while fetching categories.');
        }
    }

    // Function to delete a category
    async function deleteCategory(categoryId) {
        const confirmDelete = confirm('Are you sure you want to delete this category?');
        if (confirmDelete) {
            try {
                const response = await fetch('../api/category.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `category_id=${categoryId}`,
                });

                const data = await response.json();
                if (data.success) {
                    alert(data.message); // Display success message
                    fetchCategories(); // Refresh the category list
                } else {
                    alert(data.message || 'Failed to delete category.');
                }
            } catch (error) {
                console.error('Error deleting category:', error);
                alert('An error occurred while deleting the category.');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', fetchCategories);
</script>

<?php include('footer.php'); ?>
