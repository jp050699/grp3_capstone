<?php include('header.php'); ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Our Products</h1>
    
    <div class="input-group mb-4">
        <input type="text" id="searchInput" class="form-control" placeholder="Search products..." onkeyup="filterProducts()">
        <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
    </div>
    
    <div class="row" id="productGrid">
        <!-- Products will be dynamically inserted here -->
    </div>
</div>

<script>
    async function fetchProducts() {
        try {
            const response = await fetch('../api/product.php'); // Call the API
            const data = await response.json();

            if (data.success) {
                const productGrid = document.getElementById('productGrid');
                productGrid.innerHTML = ''; 

                data.products.forEach(product => {
                    const productCard = `
                        <div class="col-md-4 mb-4 product-item">
                            <div class="card h-100 shadow-sm">
                                <img src="../${product.image}" class="card-img-top" alt="${product.name}" style="height: 250px; object-fit: cover;">
                                <div class="card-body text-center">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text text-muted">${product.category}</p>
                                    <p class="card-text text-danger font-weight-bold">$${product.price}</p>
                                    <a href="product-detail.php?id=${product.id}" class="btn btn-primary">View Details</a>
                                    <button class="btn btn-success mt-2" onclick="addToCart(${product.id})">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    `;
                    productGrid.innerHTML += productCard;
                });
            } else {
                alert(data.message || 'Failed to fetch products.');
            }
        } catch (error) {
            console.error('Error fetching products:', error);
            alert('An error occurred while fetching products.');
        }
    }

    async function addToCart(productId) {
        try {
            const userId = <?php echo isset($_SESSION['userId']) ? $_SESSION['userId'] : 'null'; ?>;
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('product_id', productId);
            formData.append('quantity', 1);
            const response = await fetch('../api/cart.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                alert('Product added to cart successfully!');
            } else {
                alert(data.message || 'Failed to add product to cart.');
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
        }
    }

    function filterProducts() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let productItems = document.querySelectorAll('.product-item');
        
        productItems.forEach(item => {
            let productName = item.querySelector('.card-title').innerText.toLowerCase();
            item.style.display = productName.includes(input) ? 'block' : 'none';
        });
    }

    document.addEventListener('DOMContentLoaded', fetchProducts);
</script>

<?php include('footer.php'); ?>
