<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../repositories/ProductRepository.php';

class CartService {

    private PDO $db;
    private ProductRepository $products;

    public function __construct() {
        $this->db = getDB();
        $this->products = new ProductRepository();
    }

    private function sessionId(): string {
        return session_id();
    }

    // Add a product to cart; increments quantity if already present
    public function addItem(int $productId, int $quantity = 1): bool {
        if ($quantity < 1) return false;

        $product = $this->products->getById($productId);
        if (!$product) return false;

        $sid = $this->sessionId();

        // Check if already in cart
        $stmt = $this->db->prepare(
            'SELECT id, quantity FROM cart_items WHERE session_id = ? AND product_id = ?'
        );
        $stmt->execute([$sid, $productId]);
        $existing = $stmt->fetch();

        if ($existing) {
            $stmt = $this->db->prepare(
                'UPDATE cart_items SET quantity = quantity + ? WHERE id = ?'
            );
            $stmt->execute([$quantity, $existing['id']]);
        } else {
            $stmt = $this->db->prepare(
                'INSERT INTO cart_items (session_id, product_id, quantity) VALUES (?, ?, ?)'
            );
            $stmt->execute([$sid, $productId, $quantity]);
        }
        return true;
    }

    // Update quantity of a cart item (must belong to current session)
    public function updateItem(int $cartItemId, int $quantity): bool {
        if ($quantity < 1) return false;

        $stmt = $this->db->prepare(
            'UPDATE cart_items SET quantity = ? WHERE id = ? AND session_id = ?'
        );
        $stmt->execute([$quantity, $cartItemId, $this->sessionId()]);
        return $stmt->rowCount() > 0;
    }

    // Remove a single cart item (checks session ownership)
    public function removeItem(int $cartItemId): bool {
        $stmt = $this->db->prepare(
            'DELETE FROM cart_items WHERE id = ? AND session_id = ?'
        );
        $stmt->execute([$cartItemId, $this->sessionId()]);
        return $stmt->rowCount() > 0;
    }

    // Clear all items in the current session's cart
    public function clearCart(): void {
        $stmt = $this->db->prepare('DELETE FROM cart_items WHERE session_id = ?');
        $stmt->execute([$this->sessionId()]);
    }

    // Get all cart items with product details for the current session
    public function getItems(): array {
        $stmt = $this->db->prepare(
            'SELECT ci.id as cart_item_id, ci.quantity, p.id as product_id,
                    p.name, p.price, p.image, p.category
             FROM cart_items ci
             JOIN products p ON p.id = ci.product_id
             WHERE ci.session_id = ?
             ORDER BY ci.created_at ASC'
        );
        $stmt->execute([$this->sessionId()]);
        return $stmt->fetchAll();
    }

    // Total number of items (sum of quantities)
    public function getCount(): int {
        $stmt = $this->db->prepare(
            'SELECT COALESCE(SUM(quantity), 0) FROM cart_items WHERE session_id = ?'
        );
        $stmt->execute([$this->sessionId()]);
        return (int) $stmt->fetchColumn();
    }

    // Server-side cart total calculation
    public function getTotal(): float {
        $stmt = $this->db->prepare(
            'SELECT COALESCE(SUM(ci.quantity * p.price), 0)
             FROM cart_items ci
             JOIN products p ON p.id = ci.product_id
             WHERE ci.session_id = ?'
        );
        $stmt->execute([$this->sessionId()]);
        return (float) $stmt->fetchColumn();
    }
}
