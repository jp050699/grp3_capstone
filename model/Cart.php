<?php
// model/Cart.php
require_once 'db.php';

class Cart {
    private $db;

    public function __construct() {
        $this->db = (new DB())->connect();
    }

    // Add a product to the cart
    public function addToCart($userId, $productId, $quantity) {
        try {
            // Check if product already exists in cart
            $sql = "SELECT id, quantity FROM cart WHERE user_id = :user_id AND product_id = :product_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId, ':product_id' => $productId]);
            $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cartItem) {
                // Update quantity if product already in cart
                $newQuantity = $cartItem['quantity'] + $quantity;
                $sql = "UPDATE cart SET quantity = :quantity WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':quantity' => $newQuantity, ':id' => $cartItem['id']]);
            } else {
                // Insert new product into cart
                $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':user_id' => $userId, ':product_id' => $productId, ':quantity' => $quantity]);
            }
            return ["success" => true, "message" => "Product added to cart successfully!"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Get cart items for a user
    public function getCartItems($userId) {
        try {
            $sql = "SELECT c.id, c.quantity, c.product_id, p.name, p.price, p.image
                    FROM cart c
                    INNER JOIN product p ON c.product_id = p.id
                    WHERE c.user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ["success" => true, "cartItems" => $cartItems];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Update cart item quantity
    public function updateCartItem($cartId, $quantity) {
        try {
            $sql = "UPDATE cart SET quantity = :quantity WHERE id = :cart_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':quantity' => $quantity, ':cart_id' => $cartId]);
            return ["success" => true, "message" => "Cart updated successfully!"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Remove an item from the cart
    public function removeCartItem($cartId) {
        try {
            $sql = "DELETE FROM cart WHERE id = :cart_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':cart_id' => $cartId]);
            return ["success" => true, "message" => "Cart item removed successfully!"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Clear the cart for a user
    public function clearCart($userId) {
        try {
            $sql = "DELETE FROM cart WHERE user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return ["success" => true, "message" => "Cart cleared successfully!"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}
?>