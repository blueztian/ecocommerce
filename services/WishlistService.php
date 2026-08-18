<?php
/**
 * Handles basic session-based wishlist operations.
 */
class WishlistService
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['wishlist']) || !is_array($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = [];
        }
    }

    public function add(int $productId): bool
    {
        if ($productId <= 0) return false;
        if (!in_array($productId, $_SESSION['wishlist'], true)) {
            $_SESSION['wishlist'][] = $productId;
        }
        return true;
    }

    public function remove(int $productId): bool
    {
        $key = array_search($productId, $_SESSION['wishlist'], true);
        if ($key !== false) {
            unset($_SESSION['wishlist'][$key]);
            $_SESSION['wishlist'] = array_values($_SESSION['wishlist']); // reindex
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
