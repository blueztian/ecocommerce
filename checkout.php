<?php
require_once __DIR__ . '/bootstrap.php';

$orderId = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);

if (!$orderId) {
    redirect('index.php');
}

$orderService = new OrderService();
$order        = $orderService->getOrderById($orderId);
$orderItems   = $orderService->getOrderItems($orderId);

if (!$order) {
    redirect('index.php');
}

$cart      = new CartService();
$cartCount = $cart->getCount();

$pageTitle = 'Order Confirmed - EcoCommerce';

include 'includes/header.php';
include 'includes/navbar.php';
?>

<main style="margin-top: 85px; min-height: 60vh; padding: 60px 15px;">
    <div class="container">
        <div class="order-confirmation">
            <div class="order-icon">
                <ion-icon name="checkmark-circle-outline"></ion-icon>
            </div>
            <h1 class="h2 section-title">Order Placed!</h1>
            <p class="order-msg">Thank you for your purchase. Your order has been received.</p>

            <div class="order-details">
                <div class="order-meta">
                    <span>Order #<?= (int)$order['id'] ?></span>
                    <span>Status: <strong><?= e(ucfirst($order['status'])) ?></strong></span>
                    <span>Date: <?= date('F j, Y', strtotime($order['created_at'])) ?></span>
                </div>

                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderItems as $item): ?>
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <img src="<?= e($item['image']) ?>" width="50" height="50"
                                         alt="<?= e($item['name']) ?>"
                                         style="object-fit:cover; border-radius:4px;">
                                    <?= e($item['name']) ?>
                                </div>
                            </td>
                            <td><?= (int)$item['quantity'] ?></td>
                            <td><?= formatPrice((float)$item['price']) ?></td>
                            <td><?= formatPrice((float)$item['price'] * (int)$item['quantity']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align:right; font-weight:700; padding: 12px 16px;">
                                Total:
                            </td>
                            <td style="font-weight:700; color:var(--olive-classic); padding: 12px 16px;">
                                <?= formatPrice((float)$order['total_amount']) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div style="margin-top: 30px;">
                <a href="index.php" class="btn">Continue Shopping</a>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
