<?php
require_once __DIR__ . '/bootstrap.php';

$orderId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$orderId) {
    redirect('profile.php');
}

$orderRepo = new OrderRepository();
$sid       = session_id();
$order     = $orderRepo->getByIdForSession($orderId, $sid);

if (!$order) {
    // Do not reveal whether order exists or belongs to another session
    $pageTitle   = 'Order Not Found — EcoCommerce';
    $cart        = new CartService();
    $cartCount   = $cart->getCount();
    include 'includes/header.php';
    include 'includes/navbar.php';
    ?>
    <main style="margin-top:85px; min-height:60vh; padding:60px 15px; text-align:center;">
        <div class="container">
            <ion-icon name="alert-circle-outline" style="font-size:72px; color:var(--silver-chalice); display:block; margin:0 auto 20px;"></ion-icon>
            <h1 class="h2">Order not found.</h1>
            <p style="color:var(--spanish-gray); margin:16px 0 30px">This order doesn't exist or doesn't belong to your session.</p>
            <a href="profile.php" class="btn">← Back to My Profile</a>
        </div>
    </main>
    <?php
    include 'includes/footer.php';
    exit;
}

$items     = $orderRepo->getItems($orderId);
$cart      = new CartService();
$cartCount = $cart->getCount();
$pageTitle = 'Order #' . $orderId . ' — EcoCommerce';

include 'includes/header.php';
include 'includes/navbar.php';
?>
<main style="margin-top:85px; min-height:60vh; padding:40px 15px;">
    <div class="container" style="max-width:900px; margin:0 auto;">
        <nav class="page-breadcrumb" aria-label="breadcrumb">
            <a href="index.php">Home</a>
            <span aria-hidden="true"> / </span>
            <a href="profile.php">My Profile</a>
            <span aria-hidden="true"> / </span>
            <span>Order #<?= (int)$order['id'] ?></span>
        </nav>

        <div class="order-detail-header">
            <div>
                <h1 class="h2">Order <span class="span">#<?= (int)$order['id'] ?></span></h1>
                <div class="order-detail-meta">
                    <span><ion-icon name="calendar-outline" aria-hidden="true"></ion-icon> <?= date('F j, Y g:i A', strtotime($order['created_at'])) ?></span>
                    <span class="order-status-badge status-<?= e($order['status']) ?>"><?= e(ucfirst($order['status'])) ?></span>
                </div>
            </div>
        </div>

        <div class="cart-table-wrap" style="margin-top:30px">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Product</th>
                        <th>Unit Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="cart-item-image">
                            <img src="<?= e($item['image']) ?>"
                                 alt="<?= e($item['name']) ?>"
                                 width="70" height="70" loading="lazy"
                                 style="object-fit:cover; border-radius:4px; border:1px solid var(--platinum)">
                        </td>
                        <td class="cart-item-name"><?= e($item['name']) ?><br>
                            <small style="color:var(--spanish-gray); font-size:1.3rem"><?= e($item['category']) ?></small>
                        </td>
                        <td class="cart-item-price"><?= formatPrice((float)$item['price']) ?></td>
                        <td style="text-align:center; font-weight:700"><?= (int)$item['quantity'] ?></td>
                        <td class="cart-item-subtotal"><?= formatPrice((float)$item['price'] * (int)$item['quantity']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right; font-weight:700; padding:14px 16px; border-top:2px solid var(--platinum)">Order Total:</td>
                        <td style="font-weight:700; color:var(--olive-classic); font-size:1.8rem; padding:14px 16px; border-top:2px solid var(--platinum)">
                            <?= formatPrice((float)$order['total_amount']) ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div style="margin-top:30px; display:flex; gap:12px; flex-wrap:wrap">
            <a href="profile.php" class="btn btn-outline">← Back to My Profile</a>
            <a href="index.php#shop" class="btn">Continue Shopping</a>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
