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
                <button type="button" class="card-title popup-btn" style="background:none; border:none; text-align:left; font:inherit; cursor:pointer; padding:0; display:inline; color:inherit;"><?= e($product['name']) ?></button>
            </h3>
            <data class="card-price" value="<?= e($product['price']) ?>">
                <?= formatPrice((float)$product['price']) ?>
            </data>
        </div>
    </div>
    <div class="popup-view">
        <div class="popup-card">
            <button type="button" class="close-btn" aria-label="Close product details" style="background:none; border:none; font:inherit; cursor:pointer; padding:0; display:inline-block;"><i class="ri-close-circle-fill"></i></button>
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
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <button type="button" class="add-cart-btn popup-add-to-cart"
                           data-product-id="<?= (int)$product['id'] ?>" style="cursor:pointer; font-family: inherit; font-size: inherit; margin: 0;">Add to Cart</button>
                        <button type="button" class="add-to-wishlist" data-product-id="<?= (int)$product['id'] ?>" style="background:none; border:none; font:inherit; cursor:pointer; color:inherit; text-decoration: underline;">Add to Wishlist</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>
