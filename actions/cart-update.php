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

$cartItemId = filter_input(INPUT_POST, 'cart_item_id', FILTER_VALIDATE_INT);
$quantity   = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

if (!$cartItemId || $cartItemId < 1 || !$quantity || $quantity < 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit;
}

$cart = new CartService();
$updated = $cart->updateItem($cartItemId, $quantity);

if ($updated) {
    echo json_encode([
        'success'   => true,
        'message'   => 'Cart updated.',
        'cartCount' => $cart->getCount(),
        'cartTotal' => formatPrice($cart->getTotal()),
    ]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Cart item not found.']);
}
