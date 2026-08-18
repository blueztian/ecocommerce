<?php
require_once __DIR__ . '/bootstrap.php';

$pageTitle       = 'My Wishlist - EcoCommerce';
$metaDescription = 'View your saved eco-friendly products.';
$currentPage     = 'wishlist';

$wishlistService = new WishlistService();
$wishlistIds     = $wishlistService->getIds();

$productRepo = new ProductRepository();
$products    = [];
if (!empty($wishlistIds)) {
    foreach ($wishlistIds as $id) {
        $p = $productRepo->getById($id);
        if ($p) {
            $products[] = $p;
        }
    }
}

$cart      = new CartService();
$cartCount = $cart->getCount();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<main>
    <section class="section" style="padding-top: 150px; min-height: 70vh;">
        <div class="container">
            <h2 class="h2 section-title" style="margin-bottom: 40px;"><span class="span">My</span> Wishlist</h2>

            <?php if (empty($products)): ?>
                <div style="text-align: center; max-width: 500px; margin: 0 auto; background: var(--cultured); padding: 40px; border-radius: 12px;">
                    <i class="ri-heart-line" style="font-size: 4rem; color: var(--emerald); margin-bottom: 20px; display: block;"></i>
                    <h3 class="h3" style="margin-bottom: 15px;">Your wishlist is empty.</h3>
                    <p style="color: var(--onyx); margin-bottom: 25px;">Save products you like and find them here.</p>
                    <a href="index.php#shop" class="btn">Browse Products</a>
                </div>
            <?php else: ?>
                <ul class="grid-list">
                    <?php foreach ($products as $product): ?>
                        <li class="reveal-scale" id="wishlist-item-<?= $product['id'] ?>">
                            <div class="product-card">
                                <div class="card-banner img-holder" style="--width: 360; --height: 360;">
                                    <img src="<?= e($product['image']) ?>" width="360" height="360"
                                         alt="<?= e($product['name']) ?>" class="img-cover default">
                                    <img src="<?= e($product['hover_image']) ?>" width="360" height="360"
                                         alt="<?= e($product['name']) ?>" class="img-cover hover">
                                    <button class="card-action-btn remove-wishlist-btn"
                                            data-product-id="<?= (int)$product['id'] ?>"
                                            aria-label="Remove from Wishlist" title="Remove from Wishlist" style="background:var(--emerald); color:white;">
                                        <ion-icon name="trash-outline" aria-hidden="true"></ion-icon>
                                    </button>
                                </div>
                                <div class="card-content">
                                    <div class="wrapper">
                                        <div class="rating-wrapper">
                                            <?= renderStars((float)$product['rating']) ?>
                                        </div>
                                        <span class="span">(<?= (int)$product['review_count'] ?>)</span>
                                    </div>
                                    <h3 class="h3">
                                        <span class="card-title" style="cursor:default; display:inline-block;"><?= e($product['name']) ?></span>
                                    </h3>
                                    <data class="card-price" value="<?= e($product['price']) ?>">
                                        <?= formatPrice((float)$product['price']) ?>
                                    </data>
                                    <button type="button" class="btn popup-add-to-cart" data-product-id="<?= (int)$product['id'] ?>" style="width: 100%; margin-top: 15px; padding: 10px;">Add to Cart</button>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const CSRF     = csrfMeta ? csrfMeta.getAttribute('content') : '';

    function showToast(msg, isError) {
        let toast = document.getElementById('eco-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'eco-toast';
            document.body.appendChild(toast);
        }
        toast.textContent = msg;
        toast.className   = 'eco-toast ' + (isError ? 'eco-toast-error' : 'eco-toast-success');
        toast.style.opacity = '1';
        clearTimeout(toast._timer);
        toast._timer = setTimeout(function () { toast.style.opacity = '0'; }, 3000);
    }

    document.querySelectorAll('.remove-wishlist-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = btn.dataset.productId;
            
            fetch('actions/wishlist-remove.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ csrf_token: CSRF, product_id: productId })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const item = document.getElementById('wishlist-item-' + productId);
                    if (item) {
                        item.remove();
                    }
                    const badge = document.getElementById('wishlist-badge');
                    if (badge) {
                        badge.textContent = data.wishlistCount;
                        badge.style.display = data.wishlistCount > 0 ? 'inline-block' : 'none';
                    }
                    if (data.wishlistCount === 0) {
                        window.location.reload(); // Reload to show empty state
                    }
                    showToast(data.message, false);
                } else {
                    showToast(data.message || 'Error removing from wishlist.', true);
                }
            });
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
