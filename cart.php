<?php
require_once __DIR__ . '/bootstrap.php';

$pageTitle = 'Your Cart - EcoCommerce';
$cart      = new CartService();
$items     = $cart->getItems();
$total     = $cart->getTotal();
$cartCount = $cart->getCount();

$orderError = $_SESSION['order_error'] ?? null;
unset($_SESSION['order_error']);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<main style="margin-top: 85px; min-height: 60vh; padding: 40px 15px;">
    <div class="container">
        <h1 class="h2 section-title" style="text-align:left; margin-bottom:30px;">
            <span class="span">Your</span> Cart
        </h1>

        <?php if ($orderError): ?>
        <div class="cart-notice cart-error"><?= e($orderError) ?></div>
        <?php endif; ?>

        <?php if (empty($items)): ?>
        <div class="cart-empty">
            <p>Your cart is empty.</p>
            <a href="index.php#shop" class="btn" style="margin-top:20px;">Continue Shopping</a>
        </div>
        <?php else: ?>

        <div class="cart-notice" id="cart-notice" style="display:none;"></div>

        <div class="cart-table-wrap">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="cart-tbody">
                    <?php foreach ($items as $item): ?>
                        <?php include 'includes/cart-item.php'; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="cart-actions">
            <form method="POST" action="actions/cart-clear.php"
                  onsubmit="return confirm('Clear your entire cart?')">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <button type="submit" class="btn btn-outline">Clear Cart</button>
            </form>
            <a href="index.php#shop" class="btn btn-outline">Continue Shopping</a>
        </div>

        <div class="cart-summary">
            <div class="cart-summary-inner">
                <div class="cart-total-row">
                    <span>Total:</span>
                    <span class="cart-total-amount" id="cart-total"><?= formatPrice($total) ?></span>
                </div>
                <form method="POST" action="actions/order-place.php">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <button type="submit" class="btn btn-place-order">Place Order</button>
                </form>
            </div>
        </div>

        <?php endif; ?>
    </div>
</main>

<script>
// Cart CSRF token for AJAX calls
const CSRF_TOKEN = <?= json_encode(csrf_token()) ?>;

// AJAX quantity update for cart items
document.querySelectorAll('.qty-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const cartItemId = form.dataset.cartItemId;
        const qty = form.querySelector('.qty-input').value;

        fetch('actions/cart-update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                csrf_token: CSRF_TOKEN,
                cart_item_id: cartItemId,
                quantity: qty
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Update badge and total, then reload to refresh subtotals
                document.getElementById('cart-badge').textContent = data.cartCount;
                document.getElementById('cart-total').textContent = data.cartTotal;
                location.reload();
            } else {
                showNotice(data.message, 'error');
            }
        })
        .catch(() => showNotice('Update failed. Please try again.', 'error'));
    });
});

function showNotice(msg, type) {
    const el = document.getElementById('cart-notice');
    if (el) {
        el.textContent = msg;
        el.className = 'cart-notice ' + (type === 'error' ? 'cart-error' : 'cart-success');
        el.style.display = 'block';
        setTimeout(() => { el.style.display = 'none'; }, 4000);
    }
}
</script>

<?php include 'includes/footer.php'; ?>
