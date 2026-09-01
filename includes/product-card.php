<?php
// $product must be set before including this file.
// Quick View data is stored as data-* attributes — the global modal in index.php populates from these.
// e() and renderStars() are from bootstrap.php
?>
<li class="reveal-scale">
    <div class="product-card"
         data-product-id="<?= (int)$product['id'] ?>"
         data-product-name="<?= e($product['name']) ?>"
         data-product-category="<?= e($product['category']) ?>"
         data-product-desc="<?= e($product['description']) ?>"
         data-product-price="<?= formatPrice((float)$product['price']) ?>"
         data-product-raw-price="<?= e($product['price']) ?>"
         data-product-image="<?= e($product['image']) ?>">

        <div class="card-banner img-holder" style="--width: 360; --height: 360;">
            <img src="<?= e($product['image']) ?>" width="360" height="360"
                 alt="<?= e($product['name']) ?>" class="img-cover default" loading="lazy">
            <img src="<?= e($product['hover_image']) ?>" width="360" height="360"
                 alt="<?= e($product['name']) ?>" class="img-cover hover" loading="lazy">
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
                <button type="button" class="card-title popup-btn">
                    <?= e($product['name']) ?>
                </button>
            </h3>
            <data class="card-price" value="<?= e($product['price']) ?>">
                <?= formatPrice((float)$product['price']) ?>
            </data>
        </div>
    </div>
</li>
