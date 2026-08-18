<?php
require_once __DIR__ . '/bootstrap.php';

$pages = require __DIR__ . '/config/pages.php';
$slug  = preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['slug'] ?? ''));

if (!$slug || !isset($pages[$slug])) {
    redirect('index.php');
}

$page      = $pages[$slug];
$pageTitle = e($page['title']) . ' — EcoCommerce';
$cart      = new CartService();
$cartCount = $cart->getCount();

include 'includes/header.php';
include 'includes/navbar.php';
?>
<main style="margin-top:85px; min-height:60vh; padding:60px 15px;">
    <div class="container" style="max-width:960px; margin:0 auto;">
        <nav class="page-breadcrumb" aria-label="breadcrumb">
            <a href="index.php">Home</a>
            <span aria-hidden="true"> / </span>
            <span><?= e($page['title']) ?></span>
        </nav>
        <div class="static-page-content">
            <?= $page['content'] ?>
        </div>
        <div style="margin-top:40px">
            <a href="index.php" class="btn btn-outline">← Back to Home</a>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
