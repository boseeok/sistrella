{{-- AJAX add-to-cart + live cart badge --}}
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
        const el = document.createElement('div');
        el.className = 'toast-msg ' + (ok ? 'ok' : 'err');
        el.textContent = message;
        Object.assign(el.style, {
            position: 'fixed', bottom: '90px', right: '24px', zIndex: 1100,
            background: ok ? '#2e7d32' : '#c62828', color: '#fff',
            padding: '.7rem 1.1rem', borderRadius: '.6rem',
            boxShadow: '0 6px 20px rgba(0,0,0,.25)', maxWidth: '300px', fontSize: '.9rem',
        });
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 2800);
    }

    document.addEventListener('submit', async function (e) {
        const form = e.target.closest('form.js-add-to-cart');
        if (!form) return;
        e.preventDefault();
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
        }
    });
})();
</script>
