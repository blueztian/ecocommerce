<?php
require_once __DIR__ . '/bootstrap.php';

$pageTitle   = 'EcoCommerce - Sustainable Solutions for Online Shopping';
$metaDescription = 'Shop eco-friendly products at EcoCommerce. Sustainable home essentials, beauty, fashion, and gourmet goods sourced responsibly.';
$currentPage = 'home';

$productRepo = new ProductRepository();

// Optional category filter
$filterCat = trim($_GET['category'] ?? '');
if ($filterCat !== '') {
    $products = $productRepo->getByCategory($filterCat);
} else {
    $products = $productRepo->getAll();
}

$cart      = new CartService();
$cartCount = $cart->getCount();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<main>
    <article>

        <!-- ══════════════════════════════════════════
             HERO
        ══════════════════════════════════════════ -->
        <section class="section hero has-bg-image" id="home" aria-label="home"
                 style="background-image: url('images/hero-banner (1).png')">
            <div class="hero-container">
                <a href="#shop" class="hero-btn">Shop Now</a>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             TOP CATEGORIES
        ══════════════════════════════════════════ -->
        <section class="section category" id="category">
            <div class="container">
                <h2 class="h2 section-title reveal"><span class="span">Top</span> Categories</h2>
                <ul class="has-scrollbar">
                    <li class="scrollbar-item reveal-scale">
                        <div class="category-card">
                            <figure class="card-banner img-holder" style="--width: 330; --height: 300;">
                                <a href="index.php?category=EcoHome+Essentials" aria-label="Shop EcoHome Essentials">
                                    <img src="images/category-1.png" width="330" height="300" alt="EcoHome Essentials" class="img-cover" loading="lazy">
                                </a>
                            </figure>
                            <h3 class="h3"><a href="index.php?category=EcoHome+Essentials" class="card-title">EcoHome Essentials</a></h3>
                        </div>
                    </li>
                    <li class="scrollbar-item reveal-scale">
                        <div class="category-card">
                            <figure class="card-banner img-holder" style="--width: 330; --height: 300;">
                                <a href="index.php?category=EcoFashion+Finds" aria-label="Shop EcoFashion Finds">
                                    <img src="images/category-2.png" width="330" height="300" alt="EcoFashion Finds" class="img-cover" loading="lazy">
                                </a>
                            </figure>
                            <h3 class="h3"><a href="index.php?category=EcoFashion+Finds" class="card-title">EcoFashion Finds</a></h3>
                        </div>
                    </li>
                    <li class="scrollbar-item reveal-scale">
                        <div class="category-card">
                            <figure class="card-banner img-holder" style="--width: 330; --height: 300;">
                                <a href="index.php?category=EcoBeauty+Basics" aria-label="Shop EcoBeauty Basics">
                                    <img src="images/category-3.png" width="330" height="300" alt="EcoBeauty Basics" class="img-cover" loading="lazy">
                                </a>
                            </figure>
                            <h3 class="h3"><a href="index.php?category=EcoBeauty+Basics" class="card-title">EcoBeauty Basics</a></h3>
                        </div>
                    </li>
                    <li class="scrollbar-item reveal-scale">
                        <div class="category-card">
                            <figure class="card-banner img-holder" style="--width: 330; --height: 300;">
                                <a href="index.php?category=EcoGourmet+Goods" aria-label="Shop EcoGourmet Goods">
                                    <img src="images/category-4.png" width="330" height="300" alt="EcoGourmet Goods" class="img-cover" loading="lazy">
                                </a>
                            </figure>
                            <h3 class="h3"><a href="index.php?category=EcoGourmet+Goods" class="card-title">EcoGourmet Goods</a></h3>
                        </div>
                    </li>
                </ul>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             PROMOTIONAL CARDS (BLOGS)
        ══════════════════════════════════════════ -->
        <section class="section blogs" id="blogs" aria-label="blogs">
            <div class="container">
                <ul class="grid-list">
                    <li class="reveal-left">
                        <div class="blog-card has-bg-image img-holder"
                             style="background-image: url('images/blog-banner-1.png'); --width: 540; --height: 374;">
                            <p class="card-subtitle">PRACTICAL ADVICE FOR GREENER LIVING.</p>
                            <h3 class="h3 card-title">Eco-Friendly Tips</h3>
                            <a href="page.php?slug=eco-friendly-tips" class="btn">Read More</a>
                        </div>
                    </li>
                    <li class="reveal">
                        <div class="blog-card has-bg-image img-holder"
                             style="background-image: url('images/blog-banner-2.png'); --width: 540; --height: 374;">
                            <p class="card-subtitle">ECO-FRIENDLY SOLUTIONS FOR EVERYDAY LIFE.</p>
                            <h3 class="h3 card-title">Sustainable Living</h3>
                            <a href="page.php?slug=sustainable-living" class="btn">Read More</a>
                        </div>
                    </li>
                    <li class="reveal-right">
                        <div class="blog-card has-bg-image img-holder"
                             style="background-image: url('images/blog-banner-3.png'); --width: 540; --height: 374;">
                            <p class="card-subtitle">DISCOVER THE LATEST IN ECO TRENDS.</p>
                            <h3 class="h3 card-title">Green Innovations</h3>
                            <a href="page.php?slug=green-innovations" class="btn">Read More</a>
                        </div>
                    </li>
                </ul>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             BEST SELLERS — Products from DB
             Quick View uses one global modal (see below)
        ══════════════════════════════════════════ -->
        <section class="section product" id="shop" aria-label="product">
            <div class="container">
                <?php if ($filterCat !== ''): ?>
                    <h2 class="h2 section-title">
                        <span class="span">Showing:</span> <?= e($filterCat) ?>
                    </h2>
                    <p class="section-filter-reset">
                        <a href="index.php#shop">View All Products</a>
                    </p>
                <?php else: ?>
                    <h2 class="h2 section-title"><span class="span">Best</span> Sellers</h2>
                <?php endif; ?>

                <ul class="grid-list">
                    <?php if (empty($products)): ?>
                        <li class="product-empty">No products found in this category.</li>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <?php include 'includes/product-card.php'; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </section>

        <!-- Global Quick View Modal — ONE instance, populated by JS -->
        <div class="popup-view" id="global-quick-view" role="dialog" aria-modal="true" aria-label="Product Quick View">
            <div class="popup-card">
                <button type="button" class="close-btn" id="qv-close" aria-label="Close product details">
                    <i class="ri-close-circle-fill"></i>
                </button>
                <div class="product-img" id="qv-img-wrap">
                    <img src="" alt="" id="qv-img">
                </div>
                <div class="product-info">
                    <div>
                        <h3 class="h3" id="qv-title"></h3>
                        <span class="qv-category" id="qv-category"></span>
                        <p id="qv-desc"></p>
                        <span class="price" id="qv-price"></span>
                        <div class="qv-actions">
                            <button type="button" class="add-cart-btn popup-add-to-cart" id="qv-cart-btn" data-product-id="">Add to Cart</button>
                            <button type="button" class="add-to-wishlist" id="qv-wishlist-btn" data-product-id="">Add to Wishlist</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════
             SERVICE BENEFITS
        ══════════════════════════════════════════ -->
        <section class="section service" aria-label="service">
            <div class="container">
                <img src="images/service-image.png" width="122" height="136" alt="" class="img">
                <h2 class="h2 section-title">
                    <span class="span">Everything You Need for a Greener,</span> Sustainable Lifestyle.
                </h2>
                <ul class="grid-list">
                    <li>
                        <div class="service-card">
                            <figure class="card-icon">
                                <img src="images/service-icon-1.png" width="70" height="70" alt="Recycled packaging icon">
                            </figure>
                            <h3 class="h3 card-title">Recycled Packaging</h3>
                            <p class="card-text">Our commitment to the planet: all orders are shipped in 100% recycled and recyclable packaging.</p>
                        </div>
                    </li>
                    <li>
                        <div class="service-card">
                            <figure class="card-icon">
                                <img src="images/service-icon-2.png" width="70" height="70" alt="Fast shipping icon">
                            </figure>
                            <h3 class="h3 card-title">Fast &amp; Free Shipping</h3>
                            <p class="card-text">Order by 2PM local time to get free shipping on orders with a min. spend of ₱499. Speedy delivery, sustainably.</p>
                        </div>
                    </li>
                    <li>
                        <div class="service-card">
                            <figure class="card-icon">
                                <img src="images/service-icon-3.png" width="70" height="70" alt="Secure payment icon">
                            </figure>
                            <h3 class="h3 card-title">Secure Eco-Payment</h3>
                            <p class="card-text">Shop with peace of mind. Enjoy secure payment options on all purchases, plus 20% off for orders over ₱999.</p>
                        </div>
                    </li>
                    <li>
                        <div class="service-card">
                            <figure class="card-icon">
                                <img src="images/service-icon-4.png" width="70" height="70" alt="Customer support icon">
                            </figure>
                            <h3 class="h3 card-title">24/7 Customer Support</h3>
                            <p class="card-text">Our eco-support team is available 24/7 to assist you with any questions or concerns.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             SUSTAINABILITY BANNER (CTA)
        ══════════════════════════════════════════ -->
        <section class="cta has-bg-image" aria-label="cta" style="background-image: url('images/cta-bg.png')">
            <div class="container">
                <figure class="cta-banner">
                    <img src="images/cta-banner.png" width="900" height="660" alt="Sustainable lifestyle" class="w-100">
                </figure>
                <div class="cta-content">
                    <img src="images/cta-icon.png" width="120" height="35" alt="Green Guarantee badge" class="img">
                    <h2 class="h2 section-title">Explore Sustainable Finds and Enjoy <br>Our Green Guarantee!</h2>
                    <p class="section-text">At EcoCommerce, we're committed to giving you curated collection that nourish our planet and your soul. If you're looking for something different, we'll help you find the perfect fit that truly supports your sustainable lifestyle – that's our eco-promise!</p>
                    <a href="page.php?slug=about-us" class="btn">Find out more</a>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             CUSTOMER REVIEWS
        ══════════════════════════════════════════ -->
        <section class="section testimonials" aria-label="Customer reviews">
            <div class="container">
                <h2 class="h2 section-title"><span class="span">Customer</span> Reviews</h2>
                <div class="testimonials-border"></div>
                <div class="testimonials-grid">
                    <div class="testimonial reveal-scale">
                        <img src="images/p1.png" alt="Christian B." class="testimonial-avatar">
                        <div class="testimonial-name">Christian B.</div>
                        <div class="wrapper">
                            <div class="rating-wrapper">
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star-half" aria-hidden="true"></ion-icon>
                            </div>
                        </div>
                        <p>I recently tried EcoCommerce for the first time and I'm impressed! I purchased a set of reusable shopping bags and they've been incredibly useful. The quality is top-notch, and I appreciate their commitment to sustainability. I'd give them 4.5 stars!</p>
                    </div>
                    <div class="testimonial reveal-scale">
                        <img src="images/p2.png" alt="Carissa M." class="testimonial-avatar">
                        <div class="testimonial-name">Carissa M.</div>
                        <div class="wrapper">
                            <div class="rating-wrapper">
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                            </div>
                        </div>
                        <p>EcoCommerce is my go-to for eco-friendly products. I recently bought a bamboo toothbrush and I couldn't be happier. The ordering process was smooth, the product arrived quickly, and it exceeded my expectations. 5 stars!</p>
                    </div>
                    <div class="testimonial reveal-scale">
                        <img src="images/p3.png" alt="Mary Joy H." class="testimonial-avatar">
                        <div class="testimonial-name">Mary Joy H.</div>
                        <div class="wrapper">
                            <div class="rating-wrapper">
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star-outline" aria-hidden="true"></ion-icon>
                            </div>
                        </div>
                        <p>I've been a loyal customer of EcoCommerce for a while now and they never disappoint. I recently purchased a pack of biodegradable cleaning wipes and they work wonders. There's always room for improvement, hence 4 stars!</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             POPULAR BRANDS
        ══════════════════════════════════════════ -->
        <section class="section brand" aria-label="Popular brands">
            <div class="container">
                <h2 class="h2 section-title"><span class="span">Popular</span> Brands</h2>
                <ul class="brands-grid">
                    <li>
                        <div class="brand-card img-holder" style="--width: 150; --height: 150;">
                            <a href="https://www.thebamboocompany.ph/" target="_blank" rel="noopener" aria-label="The Bamboo Company">
                                <img src="images/brand-1.png" width="150" height="150" alt="The Bamboo Company" class="img-cover">
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="brand-card img-holder" style="--width: 150; --height: 150;">
                            <a href="https://echostore.ph/" target="_blank" rel="noopener" aria-label="Echo Store">
                                <img src="images/brand-2.png" width="150" height="150" alt="Echo Store" class="img-cover">
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="brand-card img-holder" style="--width: 150; --height: 150;">
                            <a href="https://arkanaturals.com/" target="_blank" rel="noopener" aria-label="Arka Naturals">
                                <img src="images/brand-3.png" width="150" height="150" alt="Arka Naturals" class="img-cover">
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="brand-card img-holder" style="--width: 150; --height: 150;">
                            <a href="https://www.kulturafilipino.com/collections/sustainably-sourced-bags" target="_blank" rel="noopener" aria-label="Kultura Filipino">
                                <img src="images/brand-4.png" width="150" height="150" alt="Kultura Filipino" class="img-cover">
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="brand-card img-holder" style="--width: 150; --height: 150;">
                            <a href="https://ecoshoppeph.com/collections/figtree-farms" target="_blank" rel="noopener" aria-label="Fig Tree Farms">
                                <img src="images/brand-5.png" width="150" height="150" alt="Fig Tree Farms" class="img-cover">
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </section>

    </article>
</main>

<?php include 'includes/footer.php'; ?>
