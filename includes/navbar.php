<?php
$cartCount  = $cartCount ?? 0;
$currentPage = $currentPage ?? '';
?>
    <header>
        <a href="index.php" class="logo" aria-label="EcoCommerce Home">
            <img src="images/logooo.png" alt="EcoCommerce" class="logo">
        </a>
        <ul class="navlist" role="navigation" aria-label="Main navigation">
            <li><a href="index.php#home" <?= $currentPage === 'home' ? 'class="nav-active"' : '' ?>>Home</a></li>

            <!-- Shop dropdown -->
            <li class="nav-has-dropdown">
                <a href="index.php#shop" <?= $currentPage === 'shop' ? 'class="nav-active"' : '' ?> aria-haspopup="true" aria-expanded="false">
                    Shop <i class="ri-arrow-down-s-line nav-chevron" aria-hidden="true"></i>
                </a>
                <ul class="nav-dropdown" role="menu">
                    <li><a href="index.php#shop" role="menuitem">All Products</a></li>
                    <li><a href="index.php?category=EcoHome+Essentials" role="menuitem">EcoHome Essentials</a></li>
                    <li><a href="index.php?category=EcoFashion+Finds" role="menuitem">EcoFashion Finds</a></li>
                    <li><a href="index.php?category=EcoBeauty+Basics" role="menuitem">EcoBeauty Basics</a></li>
                    <li><a href="index.php?category=EcoGourmet+Goods" role="menuitem">EcoGourmet Goods</a></li>
                </ul>
            </li>

            <!-- Collections dropdown -->
            <li class="nav-has-dropdown">
                <a href="index.php#category" <?= $currentPage === 'category' ? 'class="nav-active"' : '' ?> aria-haspopup="true" aria-expanded="false">
                    Collections <i class="ri-arrow-down-s-line nav-chevron" aria-hidden="true"></i>
                </a>
                <ul class="nav-dropdown" role="menu">
                    <li><a href="index.php#shop" role="menuitem">Best Sellers</a></li>
                    <li><a href="index.php#category" role="menuitem">New Arrivals</a></li>
                    <li><a href="index.php?category=EcoHome+Essentials" role="menuitem">Plastic-Free</a></li>
                    <li><a href="index.php?category=EcoGourmet+Goods" role="menuitem">Locally Made</a></li>
                </ul>
            </li>

            <li><a href="index.php#blogs" <?= $currentPage === 'blogs' ? 'class="nav-active"' : '' ?>>Blogs</a></li>
            <li><a href="page.php?slug=about-us" <?= $currentPage === 'about-us' ? 'class="nav-active"' : '' ?>>About Us</a></li>
            <li><button type="button" id="close-menu" aria-label="Close menu" style="background:none; border:none; color:inherit; font:inherit; cursor:pointer; padding:0;"><i class="fas fa-times" aria-hidden="true"></i> Close</button></li>
        </ul>
        <div class="nav-right">
            <div class="search-wrap" id="search-wrap">
                <form action="search.php" method="GET" class="search-form" role="search" id="search-form">
                    <label for="search-input" class="sr-only">Search products</label>
                    <input type="search" name="q" id="search-input" class="search-input"
                           placeholder="Search products..." autocomplete="off"
                           value="<?= isset($_GET['q']) ? e($_GET['q']) : '' ?>">
                    <button type="submit" class="search-submit" aria-label="Submit search">
                        <i class="ri-search-line" aria-hidden="true"></i>
                    </button>
                </form>
                <div class="search-suggestions" id="search-suggestions"></div>
            </div>
            <button class="nav-icon-btn" id="search-toggle" aria-label="Toggle search" aria-expanded="false">
                <i class="ri-search-line" aria-hidden="true"></i>
            </button>
            <a href="cart.php" class="cart-icon-link" title="View Cart" aria-label="Shopping cart">
                <i class="ri-shopping-cart-2-line" aria-hidden="true"></i>
                <span class="cart-badge" id="cart-badge" <?= $cartCount === 0 ? 'style="display:none"' : '' ?>><?= $cartCount ?></span>
            </a>
            <a href="wishlist.php" class="cart-icon-link" title="View Wishlist" aria-label="Wishlist" <?= $currentPage === 'wishlist' ? 'class="nav-active"' : '' ?>>
                <i class="ri-heart-line" aria-hidden="true"></i>
                <?php $wishlistCount = (new WishlistService())->getCount(); ?>
                <span class="cart-badge" id="wishlist-badge" <?= $wishlistCount === 0 ? 'style="display:none"' : '' ?>><?= $wishlistCount ?></span>
            </a>
            <a href="profile.php" title="My Profile" aria-label="My profile" <?= $currentPage === 'profile' ? 'class="nav-active"' : '' ?>>
                <i class="ri-user-line" aria-hidden="true"></i>
            </a>
            <div class="bx bx-menu" id="menu-icon" role="button" aria-label="Open menu" aria-expanded="false">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </div>
        </div>
    </header>

    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
