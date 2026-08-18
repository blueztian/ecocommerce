// EcoCommerce — search.js
// Search toggle + live suggestions

document.addEventListener('DOMContentLoaded', function () {
    var toggle      = document.getElementById('search-toggle');
    var wrap        = document.getElementById('search-wrap');
    var input       = document.getElementById('search-input');
    var suggestions = document.getElementById('search-suggestions');
    var debounceTimer;

    if (!toggle || !wrap) return;

    // Toggle search bar visibility
    toggle.addEventListener('click', function () {
        var open = wrap.classList.toggle('open');
        toggle.setAttribute('aria-expanded', String(open));
        if (open && input) {
            input.focus();
        } else {
            closeSuggestions();
        }
    });

    // Close on click outside
    document.addEventListener('click', function (e) {
        if (!wrap.contains(e.target) && e.target !== toggle) {
            wrap.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
            closeSuggestions();
        }
    });

    if (!input || !suggestions) return;

    // Live suggestions
    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        var q = input.value.trim();
        if (q.length < 2) { closeSuggestions(); return; }

        debounceTimer = setTimeout(function () {
            fetch('actions/search-suggestions.php?q=' + encodeURIComponent(q))
                .then(function (r) { return r.json(); })
                .then(function (items) {
                    if (!items.length) { closeSuggestions(); return; }
                    suggestions.innerHTML = '';
                    items.forEach(function (item) {
                        var a = document.createElement('a');
                        a.className = 'suggestion-item';
                        a.href = item.url;
                        a.innerHTML =
                            '<img src="' + item.image + '" alt="" loading="lazy">' +
                            '<span class="suggestion-name">' + item.name + '</span>' +
                            '<span class="suggestion-price">' + item.price + '</span>';
                        suggestions.appendChild(a);
                    });
                    suggestions.classList.add('has-results');
                })
                .catch(function () { closeSuggestions(); });
        }, 220);
    });

    function closeSuggestions() {
        if (suggestions) {
            suggestions.innerHTML = '';
            suggestions.classList.remove('has-results');
        }
    }
});
