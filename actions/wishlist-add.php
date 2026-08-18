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

$repo = new ProductRepository();
$product = $repo->getById($productId);

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit;
}

$wishlist = new WishlistService();
$wishlist->add($productId);

echo json_encode([
    'success' => true,
    'message' => 'Added to wishlist.',
    'wishlistCount' => $wishlist->getCount()
]);
