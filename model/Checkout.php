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

    public function getOrderByUserId($userid){
        $query = "SELECT orders.id, orders.user_id, orders.total_price, orders.payment_method, 
                         orders.address, orders.status, orders.created_at
                  FROM orders
                  WHERE orders.user_id = $userid
                  ORDER BY orders.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$orders) {
            return [];
        }

        // Fetch order items for each order
        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems($order['id']);
        }

        return $orders;
    }

    public function getAllOrders() {
        $query = "SELECT orders.id, orders.user_id, user.username AS customer_name, orders.total_price, orders.payment_method, 
                         orders.address, orders.status, orders.created_at
                  FROM orders
                  JOIN user ON orders.user_id = user.userId
                  ORDER BY orders.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$orders) {
            return [];
        }

        // Fetch order items for each order
        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems($order['id']);
        }

        return $orders;
    }

    private function getOrderItems($orderId) {
        $query = "SELECT order_items.product_id, product.name AS product_name, order_items.quantity, order_items.price
                  FROM order_items
                  JOIN product ON order_items.product_id = product.id
                  WHERE order_items.order_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function shipOrder($orderId) {
        try {
            $sql = "UPDATE orders SET status = 'Shipped' WHERE id = :order_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':order_id' => $orderId]);
            return ["success" => true, "message" => "Order shipped successfully!"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function cancelOrder($orderId) {
        try {
            $sql = "UPDATE orders SET status = 'Cancelled' WHERE id = :order_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':order_id' => $orderId]);
            return ["success" => true, "message" => "Order cancel successfully!"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function getOrdersCount() {
        try {
            $sql = "SELECT COUNT(*) as total_orders 
                    FROM orders WHERE status!='Cancelled'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ["success" => true, "total_orders" => $result['total_orders']];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}
?>
