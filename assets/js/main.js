// EcoCommerce — main.js
// Preserves all original JS behaviour + AJAX cart integration

document.addEventListener('DOMContentLoaded', function () {

    // --- Header scroll state ---
    const header = document.querySelector('header');
    if (header) {
        const onScroll = function () {
            header.classList.toggle('scrolled', window.scrollY > 20);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll(); // run once on load
    }

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

    // --- Global Quick View modal ---
    const globalQV       = document.getElementById('global-quick-view');
    const qvClose        = document.getElementById('qv-close');
    const qvImg          = document.getElementById('qv-img');
    const qvTitle        = document.getElementById('qv-title');
    const qvCategory     = document.getElementById('qv-category');
    const qvDesc         = document.getElementById('qv-desc');
    const qvPrice        = document.getElementById('qv-price');
    const qvCartBtn      = document.getElementById('qv-cart-btn');
    const qvWishlistBtn  = document.getElementById('qv-wishlist-btn');

    function openGlobalQV(card) {
        if (!globalQV || !card) return;
        const d = card.dataset;
        qvImg.src          = d.productImage  || '';
        qvImg.alt          = d.productName   || '';
        qvTitle.textContent     = d.productName     || '';
        qvCategory.textContent  = d.productCategory || '';
        qvDesc.textContent      = d.productDesc     || '';
        qvPrice.textContent     = d.productPrice    || '';
        qvCartBtn.dataset.productId    = d.productId || '';
        qvWishlistBtn.dataset.productId = d.productId || '';
        globalQV.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeGlobalQV() {
        if (!globalQV) return;
        globalQV.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Open on .popup-btn click
    document.querySelectorAll('.popup-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const card = btn.closest('.product-card');
            openGlobalQV(card);
        });
    });

    // Close button
    if (qvClose) qvClose.addEventListener('click', closeGlobalQV);

    // Click-outside to close
    if (globalQV) {
        globalQV.addEventListener('click', function (e) {
            if (e.target === globalQV) closeGlobalQV();
        });
    }

    // Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeGlobalQV();
    });

    // About modal (preserved)
    const aboutBtn  = document.getElementById('about');
    if (aboutBtn) {
        aboutBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const av = document.getElementById('about-view');
            if (av) av.classList.add('active');
        });
    }
    document.querySelectorAll('.about-view .close-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const av = btn.closest('.about-view');
            if (av) av.classList.remove('active');
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

    // "Add to Cart" inside the global Quick View modal
    if (qvCartBtn) {
        qvCartBtn.addEventListener('click', function (e) {
            e.preventDefault();
            addToCart(qvCartBtn.dataset.productId);
            closeGlobalQV();
        });
    }

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

    // Wishlist: card-level buttons (outside modal)
    document.querySelectorAll('.add-to-wishlist:not(#qv-wishlist-btn)').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            addToWishlist(btn.dataset.productId);
        });
    });

    // Wishlist: inside global Quick View modal
    if (qvWishlistBtn) {
        qvWishlistBtn.addEventListener('click', function (e) {
            e.preventDefault();
            addToWishlist(qvWishlistBtn.dataset.productId);
            closeGlobalQV();
        });
    }
});
