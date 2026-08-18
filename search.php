<?php
require_once __DIR__ . '/bootstrap.php';

$q       = trim($_GET['q'] ?? '');
$cart    = new CartService();
$cartCount = $cart->getCount();
$currentPage = 'search';

$products = [];
if ($q !== '') {
    $repo     = new ProductRepository();
    $products = $repo->search($q);
}

$pageTitle   = $q !== '' ? 'Search: ' . e($q) . ' — EcoCommerce' : 'Search — EcoCommerce';
include 'includes/header.php';
include 'includes/navbar.php';
?>
<main style="margin-top:85px; min-height:60vh; padding:40px 15px;">
    <div class="container">
        <div class="search-page-header">
            <?php if ($q !== ''): ?>
                <h1 class="h2 section-title" style="text-align:left">
                    Search results for <span class="span">"<?= e($q) ?>"</span>
                </h1>
                <p style="color:var(--spanish-gray); margin-bottom:30px; font-size:1.5rem">
                    <?= count($products) ?> product<?= count($products) !== 1 ? 's' : '' ?> found
                </p>
            <?php else: ?>
                <h1 class="h2 section-title" style="text-align:left"><span class="span">Search</span> Products</h1>
                <form action="search.php" method="GET" class="search-page-form" role="search">
                    <input type="search" name="q" placeholder="Search for eco-friendly products..."
                           class="search-page-input" aria-label="Search products">
                    <button type="submit" class="btn">Search</button>
                </form>
            <?php endif; ?>
        </div>

        <?php if ($q !== '' && empty($products)): ?>
        <div class="search-empty">
            <ion-icon name="search-outline" style="font-size:64px; color:var(--silver-chalice); display:block; margin:0 auto 20px;"></ion-icon>
            <p style="font-size:1.8rem; font-weight:700; margin-bottom:10px;">No products found for "<?= e($q) ?>"</p>
            <p style="color:var(--spanish-gray); margin-bottom:30px;">Try a different keyword or browse our collections below.</p>
            <a href="index.php#shop" class="btn">Browse All Products</a>
        </div>
        <?php elseif (!empty($products)): ?>
        <ul class="grid-list">
            <?php foreach ($products as $product): ?>
                <?php include 'includes/product-card.php'; ?>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
