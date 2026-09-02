// EcoCommerce — main.js
// Batch 3: Responsiveness, Accessibility, Interactions, Focus Management

document.addEventListener('DOMContentLoaded', function () {

    // ═══════════════════════════════════════════
    // HEADER SCROLL STATE
    // ═══════════════════════════════════════════
    const header = document.querySelector('header');
    if (header) {
        const onScroll = function () {
            header.classList.toggle('scrolled', window.scrollY > 20);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // ═══════════════════════════════════════════
    // MOBILE MENU — Accessible, scroll-locked, keyboard-navigable
    // ═══════════════════════════════════════════
    const menuIcon = document.getElementById('menu-icon');
    const closeMenu = document.getElementById('close-menu');
    const navlist = document.querySelector('.navlist');

    function openMobileMenu() {
        if (!navlist) return;
        navlist.style.left = '0';
        document.body.classList.add('menu-open');
        if (menuIcon) menuIcon.setAttribute('aria-expanded', 'true');
        // Move focus into menu
        const firstLink = navlist.querySelector('a, button');
        if (firstLink) firstLink.focus();
    }

    function closeMobileMenu() {
        if (!navlist) return;
        navlist.style.left = '-100%';
        document.body.classList.remove('menu-open');
        if (menuIcon) {
            menuIcon.setAttribute('aria-expanded', 'false');
            menuIcon.focus();
        }
    }

    if (menuIcon) menuIcon.addEventListener('click', openMobileMenu);
    if (closeMenu) closeMenu.addEventListener('click', closeMobileMenu);

    // Close when a nav link is clicked
    if (navlist) {
        navlist.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', closeMobileMenu);
        });
    }

    // Close on Escape when menu is open
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && document.body.classList.contains('menu-open')) {
            closeMobileMenu();
        }
    });

    // ═══════════════════════════════════════════
    // KEYBOARD-ACCESSIBLE DROPDOWNS
    // ═══════════════════════════════════════════
    document.querySelectorAll('.nav-has-dropdown').forEach(function (item) {
        const trigger = item.querySelector('a');
        const dropdown = item.querySelector('.nav-dropdown');
        if (!trigger || !dropdown) return;

        // Enter/Space opens; Escape closes
        trigger.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const isOpen = item.classList.contains('kb-open');
                // Close all others
                document.querySelectorAll('.nav-has-dropdown.kb-open').forEach(function (o) {
                    o.classList.remove('kb-open');
                    o.querySelector('a').setAttribute('aria-expanded', 'false');
                });
                if (!isOpen) {
                    item.classList.add('kb-open');
                    trigger.setAttribute('aria-expanded', 'true');
                    const firstItem = dropdown.querySelector('a');
                    if (firstItem) firstItem.focus();
                }
            }
        });

        // Escape inside dropdown returns focus to trigger
        dropdown.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                item.classList.remove('kb-open');
                trigger.setAttribute('aria-expanded', 'false');
                trigger.focus();
            }
        });

        // Close kb-open on click outside
        document.addEventListener('click', function (e) {
            if (!item.contains(e.target)) {
                item.classList.remove('kb-open');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // ═══════════════════════════════════════════
    // SEARCH TOGGLE
    // ═══════════════════════════════════════════
    const searchToggle = document.getElementById('search-toggle');
    const searchWrap = document.getElementById('search-wrap');
    const searchInput = document.getElementById('search-input');

    if (searchToggle && searchWrap) {
        searchToggle.addEventListener('click', function () {
            const isOpen = searchWrap.classList.toggle('open');
            searchToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            if (isOpen && searchInput) searchInput.focus();
        });

        // Close search on Escape
        if (searchInput) {
            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    searchWrap.classList.remove('open');
                    searchToggle.setAttribute('aria-expanded', 'false');
                    searchToggle.focus();
                }
            });
        }

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            if (!searchWrap.contains(e.target) && e.target !== searchToggle) {
                searchWrap.classList.remove('open');
                searchToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // ═══════════════════════════════════════════
    // GLOBAL QUICK VIEW MODAL — Focus trap, Escape, ARIA
    // ═══════════════════════════════════════════
    const globalQV      = document.getElementById('global-quick-view');
    const qvClose       = document.getElementById('qv-close');
    const qvImg         = document.getElementById('qv-img');
    const qvTitle       = document.getElementById('qv-title');
    const qvCategory    = document.getElementById('qv-category');
    const qvDesc        = document.getElementById('qv-desc');
    const qvPrice       = document.getElementById('qv-price');
    const qvCartBtn     = document.getElementById('qv-cart-btn');
    const qvWishlistBtn = document.getElementById('qv-wishlist-btn');

    let qvLastFocused = null;

    function getFocusable(container) {
        return Array.from(container.querySelectorAll(
            'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])'
        )).filter(function (el) { return !el.closest('[hidden]'); });
    }

    function trapFocus(e, container) {
        const focusable = getFocusable(container);
        if (!focusable.length) return;
        const first = focusable[0];
        const last  = focusable[focusable.length - 1];
        if (e.key === 'Tab') {
            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault(); last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault(); first.focus();
            }
        }
    }

    function openGlobalQV(card) {
        if (!globalQV || !card) return;
        const d = card.dataset;
        qvImg.src = d.productImage || '';
        qvImg.alt = d.productName  || '';
        qvTitle.textContent    = d.productName     || '';
        qvCategory.textContent = d.productCategory || '';
        qvDesc.textContent     = d.productDesc     || '';
        qvPrice.textContent    = d.productPrice    || '';
        if (qvCartBtn)     qvCartBtn.dataset.productId     = d.productId || '';
        if (qvWishlistBtn) qvWishlistBtn.dataset.productId = d.productId || '';

        qvLastFocused = document.activeElement;
        globalQV.classList.add('active');
        document.body.classList.add('modal-open');

        // Move focus to close button
        if (qvClose) setTimeout(function () { qvClose.focus(); }, 50);

        globalQV.addEventListener('keydown', qvTrapHandler);
    }

    function closeGlobalQV() {
        if (!globalQV) return;
        globalQV.classList.remove('active');
        document.body.classList.remove('modal-open');
        globalQV.removeEventListener('keydown', qvTrapHandler);
        if (qvLastFocused) qvLastFocused.focus();
    }

    function qvTrapHandler(e) {
        if (e.key === 'Escape') { closeGlobalQV(); return; }
        trapFocus(e, globalQV);
    }

    // Open on .popup-btn click
    document.querySelectorAll('.popup-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            openGlobalQV(btn.closest('.product-card'));
        });
    });

    if (qvClose) qvClose.addEventListener('click', closeGlobalQV);

    if (globalQV) {
        globalQV.addEventListener('click', function (e) {
            if (e.target === globalQV) closeGlobalQV();
        });
    }

    // ═══════════════════════════════════════════
    // ABOUT MODAL (preserved)
    // ═══════════════════════════════════════════
    const aboutBtn = document.getElementById('about');
    if (aboutBtn) {
        aboutBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const av = document.getElementById('about-view');
            if (av) {
                av.classList.add('active');
                document.body.classList.add('modal-open');
                const firstFocusable = av.querySelector('button, a');
                if (firstFocusable) setTimeout(function () { firstFocusable.focus(); }, 50);
            }
        });
    }
    document.querySelectorAll('.about-view .close-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const av = btn.closest('.about-view');
            if (av) { av.classList.remove('active'); document.body.classList.remove('modal-open'); }
        });
    });

    // ═══════════════════════════════════════════
    // AJAX — CSRF + Toast
    // ═══════════════════════════════════════════
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const CSRF = csrfMeta ? csrfMeta.getAttribute('content') : '';

    function updateBadge(id, count) {
        const badge = document.getElementById(id);
        if (!badge) return;
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }

    // Toast: supports success / error / loading states
    function showToast(msg, type) {
        // type: 'success' | 'error' | 'loading'
        let toast = document.getElementById('eco-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'eco-toast';
            toast.setAttribute('role', 'status');
            toast.setAttribute('aria-live', 'polite');
            document.body.appendChild(toast);
        }
        toast.textContent = msg;
        toast.className = 'eco-toast eco-toast-' + (type || 'success');
        toast.style.opacity = '1';
        clearTimeout(toast._timer);
        if (type !== 'loading') {
            toast._timer = setTimeout(function () { toast.style.opacity = '0'; }, 3200);
        }
    }

    // ═══════════════════════════════════════════
    // AJAX ADD TO CART
    // ═══════════════════════════════════════════
    function addToCart(productId, btn) {
        if (btn) { btn.disabled = true; btn.classList.add('is-loading'); }
        showToast('Adding to cart…', 'loading');

        fetch('actions/cart-add.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ csrf_token: CSRF, product_id: productId, quantity: 1 })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                updateBadge('cart-badge', data.cartCount);
                showToast(data.message || 'Added to cart!', 'success');
            } else {
                showToast(data.message || 'Could not add to cart.', 'error');
            }
        })
        .catch(function () { showToast('Network error. Please try again.', 'error'); })
        .finally(function () {
            if (btn) { btn.disabled = false; btn.classList.remove('is-loading'); }
        });
    }

    // Card quick-add button
    document.querySelectorAll('.add-to-cart-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            addToCart(btn.dataset.productId, btn);
        });
    });

    // Quick View modal cart button
    if (qvCartBtn) {
        qvCartBtn.addEventListener('click', function (e) {
            e.preventDefault();
            addToCart(qvCartBtn.dataset.productId, qvCartBtn);
            closeGlobalQV();
        });
    }

    // ═══════════════════════════════════════════
    // AJAX ADD TO WISHLIST
    // ═══════════════════════════════════════════
    function addToWishlist(productId, btn) {
        if (btn) { btn.disabled = true; }
        showToast('Saving…', 'loading');

        fetch('actions/wishlist-add.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ csrf_token: CSRF, product_id: productId })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                updateBadge('wishlist-badge', data.wishlistCount);
                showToast(data.message || 'Saved to wishlist!', 'success');
            } else {
                showToast(data.message || 'Already saved or unavailable.', 'error');
            }
        })
        .catch(function () { showToast('Network error. Please try again.', 'error'); })
        .finally(function () { if (btn) btn.disabled = false; });
    }

    // Card-level wishlist buttons
    document.querySelectorAll('.add-to-wishlist:not(#qv-wishlist-btn)').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            addToWishlist(btn.dataset.productId, btn);
        });
    });

    // Quick View wishlist button
    if (qvWishlistBtn) {
        qvWishlistBtn.addEventListener('click', function (e) {
            e.preventDefault();
            addToWishlist(qvWishlistBtn.dataset.productId, qvWishlistBtn);
            closeGlobalQV();
        });
    }

    // ═══════════════════════════════════════════
    // GLOBAL ESCAPE — close any open overlay
    // ═══════════════════════════════════════════
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        if (globalQV && globalQV.classList.contains('active')) closeGlobalQV();
        const av = document.getElementById('about-view');
        if (av && av.classList.contains('active')) {
            av.classList.remove('active');
            document.body.classList.remove('modal-open');
        }
    });

});
