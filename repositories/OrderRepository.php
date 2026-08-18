<?php
require_once __DIR__ . '/../config/database.php';

class OrderRepository {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getBySession(string $sessionId): array {
        $stmt = $this->db->prepare(
            'SELECT o.*,
                    COUNT(oi.id) AS item_count,
                    SUM(oi.quantity) AS total_qty
             FROM orders o
             LEFT JOIN order_items oi ON oi.order_id = o.id
             WHERE o.session_id = ?
             GROUP BY o.id
             ORDER BY o.created_at DESC'
        );
        $stmt->execute([$sessionId]);
        return $stmt->fetchAll();
    }

    public function getByIdForSession(int $orderId, string $sessionId): array|false {
        $stmt = $this->db->prepare(
            'SELECT * FROM orders WHERE id = ? AND session_id = ?'
        );
        $stmt->execute([$orderId, $sessionId]);
        return $stmt->fetch();
    }

    public function getItems(int $orderId): array {
        $stmt = $this->db->prepare(
            'SELECT oi.*, p.name, p.image, p.category
             FROM order_items oi
             JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = ?'
        );
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function getSummaryForSession(string $sessionId): array {
        $stmt = $this->db->prepare(
            'SELECT
                COUNT(DISTINCT o.id) AS total_orders,
                COALESCE(SUM(oi.quantity), 0) AS total_items,
                COALESCE(SUM(oi.quantity * oi.price), 0) AS total_spent
             FROM orders o
             LEFT JOIN order_items oi ON oi.order_id = o.id
             WHERE o.session_id = ?'
        );
        $stmt->execute([$sessionId]);
        return $stmt->fetch();
    }
}
