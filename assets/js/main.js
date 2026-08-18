// EcoCommerce — main.js
// Preserves all original JS behaviour + AJAX cart integration

document.addEventListener('DOMContentLoaded', function () {

    // --- Mobile menu ---
    const menuIcon    = document.getElementById('menu-icon');
    const closeMenu   = document.getElementById('close-menu');
    const navlist     = document.querySelector('.navlist');

    if (menuIcon) {
        menuIcon.addEventListener('click', function () {
            navlist.style.left = '0';
        });
    }
    if (closeMenu) {
        closeMenu.addEventListener('click', function (e) {
            e.preventDefault();
            navlist.style.left = '-100%';
        });
    }
    if (navlist) {
        navlist.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                navlist.style.left = '-100%';
            });
        });
    }

    // --- Product popup modals ---
    const popupBtns = document.querySelectorAll('.popup-btn');
    const closeBtns = document.querySelectorAll('.close-btn');
    const aboutBtn  = document.getElementById('about');

    function openPopup(popup) { popup && popup.classList.add('active'); }
    function closePopup(popup) { popup && popup.classList.remove('active'); }

    popupBtns.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const popup = btn.closest('li').querySelector('.popup-view');
            openPopup(popup);
        });
    });

    closeBtns.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const popup = btn.closest('.popup-view');
            closePopup(popup);
        });
    });

    if (aboutBtn) {
        aboutBtn.addEventListener('click', function (e) {
            e.preventDefault();
            openPopup(document.getElementById('about-view'));
        });
    }

    // Click-outside to close any popup
    window.addEventListener('click', function (e) {
        document.querySelectorAll('.popup-view.active').forEach(function (popup) {
            if (e.target === popup) closePopup(popup);
        });
    });

    // --- AJAX Add to Cart ---
    // Reads CSRF token from meta tag added by PHP
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const CSRF     = csrfMeta ? csrfMeta.getAttribute('content') : '';

    function updateBadge(count) {
        const badge = document.getElementById('cart-badge');
        if (!badge) return;
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }

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

    function addToCart(productId) {
        fetch('actions/cart-add.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ csrf_token: CSRF, product_id: productId, quantity: 1 })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                updateBadge(data.cartCount);
                showToast(data.message, false);
            } else {
                showToast(data.message || 'Error adding to cart.', true);
            }
        })
        .catch(function () { showToast('Network error. Please try again.', true); });
    }

    // Card action button (quick-add icon, top-right of card)
    document.querySelectorAll('.add-to-cart-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            addToCart(btn.dataset.productId);
        });
    });

    // "Add to Cart" inside the popup modal
    document.querySelectorAll('.popup-add-to-cart').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            addToCart(link.dataset.productId);
            // Close the popup after adding
            const popup = link.closest('.popup-view');
            closePopup(popup);
        });
    });

    // --- AJAX Add to Wishlist ---
    function updateWishlistBadge(count) {
        const badge = document.getElementById('wishlist-badge');
        if (!badge) return;
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }

    function addToWishlist(productId) {
        fetch('actions/wishlist-add.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ csrf_token: CSRF, product_id: productId })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                updateWishlistBadge(data.wishlistCount);
                showToast(data.message, false);
            } else {
                showToast(data.message || 'Error adding to wishlist.', true);
            }
        })
        .catch(function () { showToast('Network error. Please try again.', true); });
    }

    document.querySelectorAll('.add-to-wishlist').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            addToWishlist(btn.dataset.productId);
            const popup = btn.closest('.popup-view');
            if (popup) closePopup(popup);
        });
    });
});
