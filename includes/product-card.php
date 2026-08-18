<?php
// $product must be set before including this file
// e() and renderStars() are from bootstrap.php
?>
<li class="reveal-scale">
    <div class="product-card">
        <div class="card-banner img-holder" style="--width: 360; --height: 360;">
            <img src="<?= e($product['image']) ?>" width="360" height="360"
                 alt="<?= e($product['name']) ?>" class="img-cover default">
            <img src="<?= e($product['hover_image']) ?>" width="360" height="360"
                 alt="<?= e($product['name']) ?>" class="img-cover hover">
            <button class="card-action-btn add-to-cart-btn"
                    data-product-id="<?= (int)$product['id'] ?>"
                    aria-label="Add to Cart" title="Add to Cart">
                <ion-icon name="bag-add-outline" aria-hidden="true"></ion-icon>
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
                <a href="#" class="card-title popup-btn"><?= e($product['name']) ?></a>
            </h3>
            <data class="card-price" value="<?= e($product['price']) ?>">
                <?= formatPrice((float)$product['price']) ?>
            </data>
        </div>
    </div>
    <div class="popup-view">
        <div class="popup-card">
            <a href="#" class="close-btn"><i class="ri-close-circle-fill"></i></a>
            <div class="product-img">
                <img src="<?= e($product['image']) ?>">
            </div>
            <div class="product-info">
                <div>
                    <h3 class="h3"><?= e($product['name']) ?><br>
                        <span><?= e($product['category']) ?></span>
                    </h3>
                    <p><?= e($product['description']) ?></p>
                    <span class="price"><?= formatPrice((float)$product['price']) ?></span>
                    <a href="#" class="add-cart-btn popup-add-to-cart"
                       data-product-id="<?= (int)$product['id'] ?>">Add to Cart</a>
                    <a href="javascript:void(0)" class="add-to-wishlist" onclick="alert('Wishlist feature coming soon!'); return false;">Add to Wishlist</a>
                </div>
            </div>
        </div>
    </div>
</li>
