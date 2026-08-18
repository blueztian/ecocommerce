<?php
require_once __DIR__ . '/../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../cart.php');
}

if (!csrf_verify()) {
    redirect('../cart.php');
}

$cart = new CartService();
$cart->clearCart();

redirect('../cart.php');
