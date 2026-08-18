<?php
// $cartCount should be set before including this file
$cartCount = $cartCount ?? 0;
?>
    <header>
        <a href="index.php" class="logo">
            <img src="images/logooo.png" alt="Logo" class="logo">
        </a>
        <ul class="navlist">
            <li><a href="index.php#home">Home</a></li>
            <li><a href="index.php#shop">Shop</a></li>
            <li><a href="index.php#category">Collections</a></li>
            <li><a href="index.php#blogs">Blogs</a></li>
            <li><a href="#" id="about">About Us</a></li>
            <li><a href="#" id="close-menu"><i class="fas fa-times"></i> Close</a></li>
        </ul>
        <div class="nav-right">
            <a href="#"><i class="ri-search-line"></i></a>
            <a href="cart.php" class="cart-icon-link" title="View Cart">
                <i class="ri-shopping-cart-2-line"></i>
                <?php if ($cartCount > 0): ?>
                <span class="cart-badge" id="cart-badge"><?= $cartCount ?></span>
                <?php else: ?>
                <span class="cart-badge" id="cart-badge" style="display:none">0</span>
                <?php endif; ?>
            </a>
            <a href="#"><i class="ri-user-line"></i></a>
            <div class="bx bx-menu" id="menu-icon"><i class="fas fa-bars"></i></div>
        </div>
        <div class="popup-view" id="about-view">
            <div class="about-card">
                <a href="#" class="close-btn"><i class="ri-close-circle-fill"></i></a>
                <div class="about-img">
                    <img src="images/about.png">
                </div>
                <div class="about-content">
                    <div>
                        <h3 class="h3">About Us<br><span>EcoCommerce</span></h3>
                        <p>Welcome to EcoCommerce, where sustainability meets convenience in the world of online shopping. Founded as an initiative as part of our course requirements in Web Technologies, our mission is to provide a platform that empowers consumers to make eco-conscious choices without compromising on quality or convenience.</p>
                        <p>As champions of sustainability, we're driven by the belief that every purchase should be a positive force for our planet. That's why we've curated a collection of eco-friendly products, sourced responsibly and crafted with care.</p>
                        <p>Join us in shaping a greener future, one mindful purchase at a time!</p>
                    </div>
                </div>
            </div>
        </div>
    </header>
