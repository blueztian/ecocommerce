<?php
require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

$csrfToken = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'] ?? '', $csrfToken)) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token.']);
    exit;
}

$productId = (int)($_POST['product_id'] ?? 0);

$wishlist = new WishlistService();
$wishlist->remove($productId);

echo json_encode([
    'success' => true,
    'message' => 'Removed from wishlist.',
    'wishlistCount' => $wishlist->getCount()
]);
