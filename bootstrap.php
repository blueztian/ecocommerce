<?php
// Common bootstrap for all pages

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/repositories/ProductRepository.php';
require_once __DIR__ . '/repositories/OrderRepository.php';
require_once __DIR__ . '/services/WishlistService.php';
require_once __DIR__ . '/services/CartService.php';
require_once __DIR__ . '/services/OrderService.php';

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_token(): string {
    return $_SESSION['csrf_token'];
}

function csrf_verify(): bool {
    $token = $_POST['csrf_token'] ?? '';
    return hash_equals($_SESSION['csrf_token'], $token);
}

// Format PHP peso prices
function formatPrice(float $amount): string {
    return '₱' . number_format($amount, 2);
}

// Safe HTML output
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Redirect helper
function redirect(string $url): never {
    header('Location: ' . $url);
    exit;
}

// Render star rating HTML from a decimal rating (e.g. 4.5)
function renderStars(float $rating): string {
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($rating >= $i) {
            $html .= '<ion-icon name="star" aria-hidden="true"></ion-icon>';
        } elseif ($rating >= $i - 0.5) {
            $html .= '<ion-icon name="star-half" aria-hidden="true"></ion-icon>';
        } else {
            $html .= '<ion-icon name="star-outline" aria-hidden="true"></ion-icon>';
        }
    }
    return $html;
}
