<?php
require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

if (!csrf_verify()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid request token.']);
    exit;
}

$productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$quantity  = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT) ?: 1;

if (!$productId || $productId < 1 || $quantity < 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid product or quantity.']);
    exit;
}

$cart = new CartService();
$added = $cart->addItem($productId, $quantity);

if ($added) {
    echo json_encode([
        'success'   => true,
        'message'   => 'Product added to cart.',
        'cartCount' => $cart->getCount(),
    ]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
}
