// EcoCommerce — animations.js
// IntersectionObserver-based scroll reveal

(function () {
    var classes = ['reveal', 'reveal-left', 'reveal-right', 'reveal-scale'];

    // Respect reduced-motion preference
    var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced) return;

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    function init() {
        classes.forEach(function (cls) {
            document.querySelectorAll('.' + cls).forEach(function (el) {
                observer.observe(el);
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
