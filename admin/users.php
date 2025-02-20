<?php include('header.php'); ?>

<div class="container mt-5">
    <h1>Users</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Username</th>
                <th>Phone</th>
                <th>isAdmin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            <!-- Users will be dynamically inserted here -->
        </tbody>
    </table>
</div>

<script>
    // Function to fetch and display Users
    async function fetchUsers() {
        try {
            const response = await fetch('../api/user.php'); // Call the API
            const data = await response.json();

            if (data.success) {
                const tableBody = document.getElementById('userTableBody');
                tableBody.innerHTML = ''; // Clear the table body

                data.users.forEach(user => {
                    const isAdminChecked = user.isAdmin == 1 ? 'checked' : '';
                    const deleteButton = user.isAdmin == 0 ? `<button class="btn btn-danger btn-sm" onclick="deleteUser(${user.userId})">Delete</button>` : '';
                    
                    const row = `
                        <tr>
                            <td>${user.userId}</td>
                            <td>${user.email}</td>
                            <td>${user.username}</td>
                            <td>${user.phone}</td>
                            <td><input type="checkbox" disabled ${isAdminChecked}></td>
                            <td>${deleteButton}</td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                alert(data.message || 'Failed to fetch users.');
            }
        } catch (error) {
            console.error('Error fetching users:', error);
            alert('An error occurred while fetching users.');
        }
    }

    // Function to delete a user
    async function deleteUser(userId) {
        const confirmDelete = confirm('Are you sure you want to delete this user?');
        if (confirmDelete) {
            try {
                const response = await fetch('../api/user.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${userId}`,
                });

                const data = await response.json();
                if (data.success) {
                    alert(data.message); // Display success message
                    fetchUsers(); // Refresh the user list
                } else {
                    alert(data.message || 'Failed to delete user.');
                }
            } catch (error) {
                console.error('Error deleting user:', error);
                alert('An error occurred while deleting the user.');
            }
        }
    }

    // Fetch users on page load
    document.addEventListener('DOMContentLoaded', fetchUsers);
</script>

<?php include('footer.php'); ?>
