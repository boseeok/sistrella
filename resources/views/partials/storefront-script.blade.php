{{-- Storefront behaviour: AJAX add-to-cart, quantity steppers, product rails, auto-submit selects (no dependencies) --}}
<script>
(function () {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;

    function setCount(n) {
        const badge = document.getElementById('cart-count');
        if (!badge) return;
        badge.textContent = n;
        badge.style.display = n > 0 ? '' : 'none';
    }

    function toast(message, ok = true) {
        document.querySelectorAll('.toast-msg').forEach(t => t.remove());
        const el = document.createElement('div');
        el.className = 'toast-msg ' + (ok ? 'ok' : 'err');
        el.setAttribute('role', 'status');
        el.innerHTML = '<span></span>' + (ok ? '<a href="{{ route('cart.index') }}">View cart</a>' : '');
        el.firstChild.textContent = message;
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 3500);
    }

    // AJAX add-to-cart. "Buy now" buttons (name=buy_now) submit normally to go to checkout.
    document.addEventListener('submit', async function (e) {
        const form = e.target.closest('form.js-add-to-cart');
        if (!form || (e.submitter && e.submitter.name === 'buy_now')) return;
        e.preventDefault();
        const btn = e.submitter || form.querySelector('button');
        btn && (btn.disabled = true);
        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: new FormData(form),
            });
            const data = await res.json();
            if (res.ok && data.ok) {
                setCount(data.count);
                toast(data.message, true);
            } else {
                toast(data.message || 'Something went wrong.', false);
            }
        } catch (err) {
            toast('Network error. Please try again.', false);
        } finally {
            btn && (btn.disabled = false);
        }
    });

    // Quantity steppers: <div class="qty"><button data-qty="-1"> <input> <button data-qty="1"></div>
    let submitTimer;
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-qty]');
        if (!btn) return;
        const input = btn.closest('.qty')?.querySelector('input');
        if (!input) return;
        const min = input.min !== '' ? +input.min : 1;
        const max = input.max !== '' ? +input.max : 999;
        const next = Math.min(max, Math.max(min, (+input.value || 0) + (+btn.dataset.qty)));
        if (next === +input.value) return;
        input.value = next;
        input.dispatchEvent(new Event('change', { bubbles: true }));
    });

    // Auto-submitting controls (cart quantities, sort dropdowns)
    document.addEventListener('change', function (e) {
        const el = e.target.closest('[data-autosubmit]');
        if (!el || !el.form) return;
        clearTimeout(submitTimer);
        submitTimer = setTimeout(() => el.form.requestSubmit ? el.form.requestSubmit() : el.form.submit(), 450);
    });

    // Product rails: prev/next buttons scroll by roughly one screen
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-rail-prev],[data-rail-next]');
        if (!btn) return;
        const rail = document.getElementById(btn.dataset.railPrev || btn.dataset.railNext);
        if (!rail) return;
        rail.scrollBy({ left: (btn.dataset.railPrev ? -1 : 1) * rail.clientWidth * 0.85, behavior: 'smooth' });
    });
})();
</script>
