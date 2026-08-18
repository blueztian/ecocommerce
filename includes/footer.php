    <footer class="footer" style="background-image: url('images/footer-bg.jpg')">
        <div class="footer-top section">
            <div class="container">
                <div class="footer-brand">
                    <a href="index.php" class="logo-1"><img src="images/footer-logo.png" alt="EcoCommerce"></a>
                    <p class="footer-text">
                        If you have any question, please contact us at <a href="mailto:eco_commerce@gmail.com" class="link">eco_commerce@gmail.com</a>
                    </p>
                    <ul class="contact-list">
                        <li class="contact-item">
                            <ion-icon name="location-outline" aria-hidden="true"></ion-icon>
                            <address class="address">Brgy. Butong, Cabuyao City, Laguna, PH</address>
                        </li>
                        <li class="contact-item">
                            <ion-icon name="call-outline" aria-hidden="true"></ion-icon>
                            <a href="tel:+639876543210" class="contact-link">(+63) 987 6543 210</a>
                        </li>
                    </ul>
                    <ul class="social-list">
                        <li><a href="https://facebook.com/ecocommerce" target="_blank" rel="noopener" class="social-link" aria-label="Facebook"><ion-icon name="logo-facebook"></ion-icon></a></li>
                        <li><a href="https://twitter.com/ecocommerce" target="_blank" rel="noopener" class="social-link" aria-label="Twitter"><ion-icon name="logo-twitter"></ion-icon></a></li>
                        <li><a href="https://pinterest.com/ecocommerce" target="_blank" rel="noopener" class="social-link" aria-label="Pinterest"><ion-icon name="logo-pinterest"></ion-icon></a></li>
                        <li><a href="https://instagram.com/ecocommerce" target="_blank" rel="noopener" class="social-link" aria-label="Instagram"><ion-icon name="logo-instagram"></ion-icon></a></li>
                    </ul>
                </div>
                <ul class="footer-list">
                    <li><p class="footer-list-title">Corporate</p></li>
                    <li><a href="page.php?slug=careers" class="footer-link">Careers</a></li>
                    <li><a href="page.php?slug=about-us" class="footer-link">About Us</a></li>
                    <li><a href="page.php?slug=contact-us" class="footer-link">Contact Us</a></li>
                    <li><a href="page.php?slug=faqs" class="footer-link">FAQs</a></li>
                    <li><a href="page.php?slug=vendors" class="footer-link">Vendors</a></li>
                    <li><a href="page.php?slug=affiliate-program" class="footer-link">Affiliate Program</a></li>
                </ul>
                <ul class="footer-list">
                    <li><p class="footer-list-title">Information</p></li>
                    <li><a href="page.php?slug=online-store" class="footer-link">Online Store</a></li>
                    <li><a href="page.php?slug=privacy-policy" class="footer-link">Privacy Policy</a></li>
                    <li><a href="page.php?slug=refund-policy" class="footer-link">Refund Policy</a></li>
                    <li><a href="page.php?slug=shipping-policy" class="footer-link">Shipping Policy</a></li>
                    <li><a href="page.php?slug=terms-of-service" class="footer-link">Terms of Service</a></li>
                    <li><a href="page.php?slug=track-order" class="footer-link">Track Order</a></li>
                </ul>
                <ul class="footer-list">
                    <li><p class="footer-list-title">Services</p></li>
                    <li><a href="page.php?slug=eco-product-guides" class="footer-link">Eco Product Guides</a></li>
                    <li><a href="page.php?slug=sustainable-workshops" class="footer-link">Sustainable Workshops</a></li>
                    <li><a href="page.php?slug=green-home-consultations" class="footer-link">Green Home Consultations</a></li>
                    <li><a href="page.php?slug=zero-waste-coaching" class="footer-link">Zero-Waste Coaching</a></li>
                    <li><a href="page.php?slug=ethical-fashion-consults" class="footer-link">Ethical Fashion Consults</a></li>
                    <li><a href="page.php?slug=resource-center" class="footer-link">Resource Center</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <p class="copyright">&copy; 2024. <a href="index.php" class="copyright-link">EcoCommerce</a>. All Rights Reserved.</p>
                <img src="images/payment.png" width="397" height="32" alt="payment method" class="img">
            </div>
        </div>
    </footer>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/search.js"></script>
    <script src="assets/js/animations.js"></script>
    <?php if (!empty($extraJs)): ?>
    <?php foreach ($extraJs as $js): ?>
    <script src="<?= e($js) ?>"></script>
    <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
