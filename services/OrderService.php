<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../services/CartService.php';

class OrderService {

    private PDO $db;
    private CartService $cart;

    public function __construct() {
        $this->db = getDB();
        $this->cart = new CartService();
    }

    // Place an order from the current cart; returns order ID on success, false on failure
    public function placeOrder(): int|false {
        $items = $this->cart->getItems();

        if (empty($items)) {
            return false; // Cannot place empty order
        }

        $total = $this->cart->getTotal();
        $sid = session_id();

        try {
            $this->db->beginTransaction();

            // Create order
            $stmt = $this->db->prepare(
                'INSERT INTO orders (session_id, total_amount, status) VALUES (?, ?, ?)'
            );
            $stmt->execute([$sid, $total, 'placed']);
            $orderId = (int) $this->db->lastInsertId();

            // Copy cart items to order_items (price locked at time of order)
            $stmt = $this->db->prepare(
                'INSERT INTO order_items (order_id, product_id, quantity, price)
                 SELECT ?, product_id, quantity, p.price
                 FROM cart_items ci
                 JOIN products p ON p.id = ci.product_id
                 WHERE ci.session_id = ?'
            );
            $stmt->execute([$orderId, $sid]);

            // Clear the cart
            $this->cart->clearCart();

            $this->db->commit();
            return $orderId;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log('Order placement failed: ' . $e->getMessage());
            return false;
        }
    }

    public function getOrderById(int $orderId): array|false {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE id = ?');
        $stmt->execute([$orderId]);
        return $stmt->fetch();
    }

    public function getOrderItems(int $orderId): array {
        $stmt = $this->db->prepare(
            'SELECT oi.*, p.name, p.image
             FROM order_items oi
             JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = ?'
        );
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }
}
