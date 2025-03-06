<?php
require_once 'db.php';

class Checkout{
    private $db;

    public function __construct() {
        $this->db = (new DB())->connect();
    }

    // Create an order
    public function createOrder($userId, $cartItems, $totalPrice, $address, $paymentMethod) {
        $orderId = null;
        $this->db->beginTransaction();

        try {
            // Insert into orders table
            $stmt = $this->db->prepare("INSERT INTO orders (user_id, total_price, address, payment_method, status) VALUES (?, ?, ?, ?, 'Pending')");
            $stmt->execute([$userId, $totalPrice, $address, $paymentMethod]);
            $orderId = $this->db->lastInsertId();

            // Insert order items
            foreach ($cartItems as $item) {
                $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
            }

            // Clear user cart after order
            $stmt = $this->db->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->execute([$userId]);

            $this->db->commit();
            return ['success' => true, 'order_id' => $orderId];

        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Order processing failed: ' . $e->getMessage()];
        }
    }
}
?>
