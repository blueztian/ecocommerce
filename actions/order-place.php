<?php
require_once __DIR__ . '/../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../cart.php');
}

if (!csrf_verify()) {
    redirect('../cart.php');
}

$orderService = new OrderService();
$orderId = $orderService->placeOrder();

if ($orderId) {
    redirect('../checkout.php?order_id=' . $orderId);
} else {
    // Cart was empty or DB error
    $_SESSION['order_error'] = 'Unable to place order. Your cart may be empty.';
    redirect('../cart.php');
}
