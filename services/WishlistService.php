<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Wishlist service backed by the wishlist_items DB table.
 * Session cache is kept for fast getCount()/contains() checks.
 */
class WishlistService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Hydrate session cache from DB on first load
        if (!isset($_SESSION['wishlist'])) {
            $stmt = $this->db->prepare('SELECT product_id FROM wishlist_items WHERE session_id = ?');
            $stmt->execute([session_id()]);
            $_SESSION['wishlist'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
    }

    public function add(int $productId): bool
    {
        if ($productId <= 0) return false;

        try {
            $stmt = $this->db->prepare(
                'INSERT IGNORE INTO wishlist_items (session_id, product_id) VALUES (?, ?)'
            );
            $stmt->execute([session_id(), $productId]);
        } catch (PDOException $e) {
            error_log('Wishlist add failed: ' . $e->getMessage());
            return false;
        }

        // Sync session cache
        if (!in_array($productId, $_SESSION['wishlist'], true)) {
            $_SESSION['wishlist'][] = $productId;
        }
        return true;
    }

    public function remove(int $productId): bool
    {
        try {
            $stmt = $this->db->prepare(
                'DELETE FROM wishlist_items WHERE session_id = ? AND product_id = ?'
            );
            $stmt->execute([session_id(), $productId]);
        } catch (PDOException $e) {
            error_log('Wishlist remove failed: ' . $e->getMessage());
            return false;
        }

        // Sync session cache
        $key = array_search($productId, $_SESSION['wishlist'], true);
        if ($key !== false) {
            unset($_SESSION['wishlist'][$key]);
            $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
        }
        return true;
    }

    public function contains(int $productId): bool
    {
        return in_array($productId, $_SESSION['wishlist'], true);
    }

    public function getCount(): int
    {
        return count($_SESSION['wishlist']);
    }

    public function getIds(): array
    {
        return $_SESSION['wishlist'];
    }
}
