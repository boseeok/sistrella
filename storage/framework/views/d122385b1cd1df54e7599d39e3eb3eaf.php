
<style>
    :root{
        /* Primary — forest green (headings, buttons, links) */
        --brand:#3D4B33; --brand-dark:#2E3A27; --brand-light:#DCE3CE; --brand-bg:#EAEFE0;
        /* Palette tokens */
        --forest:#3D4B33;        /* deep forest green */
        --sage:#8C9A6E;          /* sage green — soft accents */
        --sage-light:#DCE3CE;    /* light sage — decorative */
        --terracotta:#B26B3C;    /* terracotta — highlight accent */
        --terracotta-dark:#9A5A30;
        --cream:#F6F3ED;         /* cream white — background */
        --ink:#2F3A2A;           /* primary text (dark green) */
        --taupe:#797D6A;         /* muted secondary text */
        /* Accent (terracotta) */
        --accent:#B26B3C; --accent-dark:#9A5A30; --accent-soft:#EAD9C8;
    }
    body{font-family:'Poppins',system-ui,sans-serif;background:var(--cream);color:var(--ink);}
    .brand{font-family:'Quicksand',sans-serif;color:var(--forest)!important;letter-spacing:.3px;}
    a{color:var(--forest);text-decoration:none;}
    a:hover{color:var(--terracotta);}
    .footer a{color:rgba(255,255,255,.8);} .footer a:hover{color:#fff;}
    .text-brand{color:var(--forest)!important;}
    .bg-brand{background:var(--forest)!important;}
    /* Buttons — forest green primary */
    .btn-brand{background:var(--forest);border-color:var(--forest);color:var(--cream);font-weight:600;}
    .btn-brand:hover{background:var(--brand-dark);border-color:var(--brand-dark);color:#fff;}
    .btn-outline-brand{color:var(--forest);border-color:var(--forest);}
    .btn-outline-brand:hover{background:var(--forest);color:var(--cream);}
    /* Terracotta accent button (e.g. Join / Sign Up) */
    .text-accent{color:var(--terracotta)!important;}
    .bg-accent{background:var(--terracotta)!important;}
    .btn-accent{background:var(--terracotta);border-color:var(--terracotta);color:#fff;font-weight:600;}
    .btn-accent:hover{background:var(--terracotta-dark);border-color:var(--terracotta-dark);color:#fff;}
    .topbar{background:var(--forest);overflow:hidden;}
    .marquee{display:flex;width:100%;overflow:hidden;}
    .marquee-track{display:flex;flex-shrink:0;align-items:center;white-space:nowrap;padding:.35rem 0;animation:marquee 28s linear infinite;}
    .marquee-item{padding:0 2.5rem;}
    .marquee:hover .marquee-track{animation-play-state:paused;}
    @keyframes marquee{from{transform:translateX(0);}to{transform:translateX(-100%);}}
    @media(prefers-reduced-motion:reduce){.marquee-track{animation:none;}}
    .navbar-brand{font-size:1.4rem;}
    .nav-link{color:#4a513f;font-weight:500;}
    .nav-link:hover{color:var(--forest);}
    .category-bar{background:#fff;overflow:visible;} .category-bar .nav-link{font-size:.9rem;white-space:nowrap;color:#4a513f;font-weight:500;}
    .category-bar .nav-link:hover{color:var(--forest);}
    .category-bar .dropdown-menu{z-index:1055;}
    .browse-all .btn{border-radius:0;font-weight:600;white-space:nowrap;}
    .browse-menu{min-width:300px;max-height:70vh;overflow:auto;border:none;border-radius:0 0 .6rem .6rem;padding:.5rem 0;z-index:1055;}
    .browse-menu .dropdown-item{padding:.45rem 1rem;}
    .search-form{max-width:480px;width:100%;}
    .footer{background:var(--brand-dark);}
    .card{border:none;border-radius:1rem;background:#fff;box-shadow:0 4px 18px rgba(47,58,42,.08);}
    .product-card{transition:transform .15s ease,box-shadow .15s ease;overflow:hidden;height:100%;}
    .product-card:hover{transform:translateY(-4px);box-shadow:0 10px 28px rgba(47,58,42,.16);}
    .product-card .pimg{aspect-ratio:1/1;object-fit:cover;width:100%;background:var(--sage-light);}
    .badge-sale{background:var(--terracotta);color:#fff;font-weight:700;}
    .hero{background:linear-gradient(120deg,var(--sage-light),var(--brand-bg) 55%,var(--cream));border-radius:1.5rem;}
    .section-title{font-family:'Quicksand',sans-serif;font-weight:700;color:var(--forest);}
    .price{color:var(--forest);font-weight:700;}
    .old-price{color:var(--taupe);text-decoration:line-through;font-size:.9em;}
    .whatsapp-float{position:fixed;bottom:24px;right:24px;width:56px;height:56px;background:#25D366;color:#fff;
        border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.7rem;
        box-shadow:0 6px 20px rgba(37,211,102,.5);z-index:1050;transition:transform .15s;}
    .whatsapp-float:hover{transform:scale(1.08);color:#fff;}
    .rating i{color:var(--terracotta);}
    .prepay-note{background:var(--brand-bg);border-left:4px solid var(--sage);border-radius:.5rem;}
    .dropdown-menu{border:none;box-shadow:0 8px 24px rgba(47,58,42,.12);border-radius:.75rem;}
    .notif-menu{min-width:340px;max-width:360px;max-height:75vh;overflow:auto;}
    .object-fit-cover{object-fit:cover;}
</style>
<?php /**PATH C:\Users\User\crochet-store\resources\views/partials/theme.blade.php ENDPATH**/ ?>