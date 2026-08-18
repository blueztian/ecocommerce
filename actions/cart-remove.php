<?php
require_once __DIR__ . '/../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../cart.php');
}

if (!csrf_verify()) {
    redirect('../cart.php');
}

$cartItemId = filter_input(INPUT_POST, 'cart_item_id', FILTER_VALIDATE_INT);

if ($cartItemId && $cartItemId > 0) {
    $cart = new CartService();
    $cart->removeItem($cartItemId);
}

redirect('../cart.php');
