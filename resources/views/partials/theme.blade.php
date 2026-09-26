{{--
    Shared storefront design system on top of Bootstrap 5.
    Palette: forest green (primary), terracotta (accent), sage (decor) on warm cream.
    Type: Fraunces (display) + DM Sans (text). Existing utility class names
    (btn-brand, section-title, price, prepay-note, ...) are kept for all views.
--}}
<style>
    :root{
        --forest:#3D4B33; --forest-dark:#2C3725; --brand:#3D4B33; --brand-dark:#2C3725;
        --sage:#8C9A6E; --sage-light:#E3E8D6; --brand-light:#E3E8D6; --brand-bg:#EDF0E4;
        --terracotta:#9C5530; --terracotta-dark:#84462A; --accent:#9C5530; --accent-dark:#84462A; --accent-soft:#F3E3D6;
        --cream:#F7F4EE; --surface:#FFFFFF; --line:#E7E1D6;
        --ink:#2B2A26; --muted:#655F53; --taupe:#655F53;
        --radius:14px; --radius-sm:10px;
        --shadow-sm:0 1px 2px rgba(43,42,38,.06),0 2px 8px rgba(43,42,38,.05);
        --shadow:0 10px 30px rgba(43,42,38,.10);
        --font-display:'Fraunces',Georgia,'Times New Roman',serif;
        --font-body:'DM Sans',system-ui,-apple-system,'Segoe UI',sans-serif;
        --bs-body-font-family:var(--font-body);
        --bs-link-color-rgb:61,75,51; --bs-link-hover-color-rgb:156,85,48;
    }
    html{scroll-behavior:smooth;}
    body{font-family:var(--font-body);background:var(--cream);color:var(--ink);font-size:.975rem;-webkit-font-smoothing:antialiased;}
    h1,h2,h3,.h1,.h2,.h3,.display-font{font-family:var(--font-display);font-weight:600;letter-spacing:-.01em;color:var(--ink);}
    a{color:var(--forest);text-decoration:none;}
    a:hover{color:var(--terracotta);}
    :focus-visible{outline:3px solid rgba(156,85,48,.45);outline-offset:2px;}
    .skip-link{position:absolute;left:-9999px;top:0;z-index:2000;background:var(--forest);color:#fff;padding:.6rem 1rem;border-radius:0 0 .5rem 0;}
    .skip-link:focus{left:0;color:#fff;}
    .text-muted{color:var(--muted)!important;}
    .text-brand{color:var(--forest)!important;} .bg-brand{background:var(--forest)!important;}
    .text-accent{color:var(--terracotta)!important;} .bg-accent{background:var(--terracotta)!important;}
    .bg-cream{background:var(--cream)!important;} .bg-sage{background:var(--sage-light)!important;}
    .brand{font-family:var(--font-display);color:var(--forest)!important;}
    .eyebrow{font-size:.72rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--terracotta);}
    .section-title{font-family:var(--font-display);font-weight:600;color:var(--ink);letter-spacing:-.01em;}
    .section{padding:3.25rem 0;} @media(max-width:767.98px){.section{padding:2.25rem 0;}}
    .section-head{display:flex;align-items:end;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;}
    .section-head h2{font-size:clamp(1.45rem,1.1rem + 1.2vw,2rem);margin:0;}
    .link-arrow{font-weight:600;white-space:nowrap;} .link-arrow i{transition:transform .15s;} .link-arrow:hover i{transform:translateX(3px);}

    /* Buttons */
    .btn{border-radius:999px;font-weight:600;padding:.55rem 1.15rem;}
    .btn-sm{padding:.35rem .85rem;} .btn-lg{padding:.8rem 1.6rem;font-size:1rem;}
    .btn-brand{background:var(--forest);border-color:var(--forest);color:#fff;}
    .btn-brand:hover,.btn-brand:focus{background:var(--forest-dark);border-color:var(--forest-dark);color:#fff;}
    .btn-outline-brand{color:var(--forest);border-color:var(--forest);background:transparent;}
    .btn-outline-brand:hover{background:var(--forest);color:#fff;}
    .btn-accent{background:var(--terracotta);border-color:var(--terracotta);color:#fff;}
    .btn-accent:hover{background:var(--terracotta-dark);border-color:var(--terracotta-dark);color:#fff;}
    .btn-ghost{background:transparent;border:1px solid var(--line);color:var(--ink);}
    .btn-ghost:hover{border-color:var(--ink);color:var(--ink);}
    .btn-icon{width:42px;height:42px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;}

    /* Forms */
    .form-control,.form-select{border-color:var(--line);border-radius:var(--radius-sm);padding:.6rem .85rem;background-color:#fff;}
    .form-control-sm,.form-select-sm{padding:.35rem .7rem;}
    .form-control:focus,.form-select:focus{border-color:var(--sage);box-shadow:0 0 0 .2rem rgba(140,154,110,.25);}
    .form-check-input:checked{background-color:var(--forest);border-color:var(--forest);}
    .form-label{font-weight:600;font-size:.85rem;color:var(--ink);}

    /* Cards & surfaces */
    .card{border:1px solid var(--line);border-radius:var(--radius);background:var(--surface);box-shadow:var(--shadow-sm);}
    .surface{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);}
    .dropdown-menu{border:1px solid var(--line);box-shadow:var(--shadow);border-radius:var(--radius-sm);}
    .badge{font-weight:600;letter-spacing:.02em;}
    .badge-sale{background:var(--terracotta);color:#fff;}
    .price{color:var(--ink);font-weight:700;}
    .price-sale{color:var(--terracotta);}
    .old-price{color:var(--muted);text-decoration:line-through;font-weight:400;font-size:.88em;}
    .rating i{color:#C98A2B;}
    .prepay-note{background:var(--brand-bg);border:1px solid #D9DFC9;border-radius:var(--radius-sm);}
    .object-fit-cover{object-fit:cover;}
    /* Admin-authored rich text (Page Content editor) */
    .rich-text > :last-child{margin-bottom:0;}
    .rich-text h2,.rich-text h3,.rich-text h4{color:var(--ink);margin:1.4rem 0 .6rem;}
    .rich-text h2{font-size:1.6rem;} .rich-text h3{font-size:1.3rem;} .rich-text h4{font-size:1.1rem;}
    .rich-text > h2:first-child,.rich-text > h3:first-child,.rich-text > h4:first-child{margin-top:0;}
    .rich-text ul,.rich-text ol{padding-left:1.25rem;}
    .rich-text blockquote{border-left:3px solid var(--sage);padding:.25rem 0 .25rem 1rem;color:var(--muted);font-style:italic;margin:1rem 0;}
    .rich-text a{text-decoration:underline;}
    .hero{background:linear-gradient(135deg,#EEF1E4,var(--cream));border:1px solid var(--line);border-radius:calc(var(--radius) * 1.4);}
    .breadcrumb{font-size:.82rem;margin-bottom:1rem;} .breadcrumb a{color:var(--muted);} .breadcrumb-item.active{color:var(--ink);}
    .pagination{--bs-pagination-color:var(--forest);--bs-pagination-active-bg:var(--forest);--bs-pagination-active-border-color:var(--forest);--bs-pagination-border-radius:999px;gap:.25rem;flex-wrap:wrap;}
    .pagination .page-link{border-radius:999px!important;min-width:38px;text-align:center;}

    /* Announcement bar */
    .topbar{background:var(--forest);color:#F1EFE7;font-size:.8rem;overflow:hidden;}
    .topbar a{color:#fff;}
    .marquee{display:flex;width:100%;overflow:hidden;}
    .marquee-track{display:flex;flex-shrink:0;align-items:center;white-space:nowrap;padding:.4rem 0;animation:marquee 32s linear infinite;}
    .marquee-item{padding:0 2.25rem;}
    .marquee:hover .marquee-track{animation-play-state:paused;}
    @keyframes marquee{from{transform:translateX(0);}to{transform:translateX(-100%);}}
    @media(prefers-reduced-motion:reduce){.marquee-track{animation:none;}*{transition:none!important;scroll-behavior:auto!important;}}

    /* Header */
    .site-header{background:rgba(255,255,255,.97);backdrop-filter:saturate(1.4) blur(6px);border-bottom:1px solid var(--line);}
    .header-main{display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:1rem;padding:.7rem 0;}
    .header-logo img{height:56px;width:auto;} @media(max-width:991.98px){.header-logo img{height:44px;}}
    .header-search{max-width:520px;width:100%;margin:0 auto;position:relative;}
    .header-search .form-control{border-radius:999px;padding-left:2.6rem;background:var(--cream);border-color:transparent;}
    .header-search .form-control:focus{background:#fff;border-color:var(--sage);}
    .header-search .bi-search{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--muted);}
    .header-actions{display:flex;align-items:center;gap:.15rem;}
    .header-icon{position:relative;display:inline-flex;flex-direction:column;align-items:center;justify-content:center;min-width:44px;height:44px;padding:0 .4rem;border-radius:12px;color:var(--ink);background:none;border:0;}
    .header-icon:hover{background:var(--cream);color:var(--forest);}
    .header-icon i{font-size:1.3rem;line-height:1;}
    .header-icon .label{font-size:.68rem;font-weight:600;margin-top:2px;}
    .header-icon .count{position:absolute;top:2px;right:2px;min-width:18px;height:18px;padding:0 5px;border-radius:999px;background:var(--terracotta);color:#fff;font-size:.66rem;font-weight:700;display:flex;align-items:center;justify-content:center;}
    .main-nav{border-top:1px solid var(--line);}
    .main-nav .nav-link{color:var(--ink);font-weight:600;font-size:.9rem;padding:.8rem 1rem;position:relative;}
    .main-nav .nav-link:hover,.main-nav .nav-link.active{color:var(--forest);}
    .main-nav .nav-link.active::after{content:"";position:absolute;left:1rem;right:1rem;bottom:0;height:2px;background:var(--terracotta);border-radius:2px;}
    .main-nav .nav-link.nav-sale{color:var(--terracotta);}
    .mega{position:static;}
    .mega .dropdown-menu{width:100%;left:0;right:0;margin-top:0;border-radius:0 0 var(--radius) var(--radius);border-top:0;padding:1.5rem 0;}
    .mega-col h6{font-family:var(--font-display);font-size:1rem;margin:.6rem 0 .35rem;}
    .mega-col img{width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:var(--radius-sm);transition:transform .25s;}
    .mega-col a:hover img{transform:scale(1.03);}
    .mega-col ul a{color:var(--muted);font-size:.88rem;line-height:1.9;} .mega-col ul a:hover{color:var(--terracotta);}
    .notif-menu{min-width:340px;max-width:360px;max-height:75vh;overflow:auto;}
    .offcanvas-nav .list-group-item{border:0;border-bottom:1px solid var(--line);padding:.85rem 0;font-weight:600;background:none;}
    .offcanvas-nav .accordion-button{padding:.85rem 0;font-weight:600;background:none;box-shadow:none;}

    /* Product card */
    .pcard{position:relative;display:flex;flex-direction:column;height:100%;background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);overflow:hidden;transition:box-shadow .2s,transform .2s;}
    .pcard:hover{box-shadow:var(--shadow);transform:translateY(-3px);}
    .pcard-media{position:relative;aspect-ratio:1/1;background:var(--sage-light);overflow:hidden;}
    .pcard-media img{width:100%;height:100%;object-fit:cover;transition:transform .45s ease;}
    .pcard:hover .pcard-media img{transform:scale(1.05);}
    .pcard-badges{position:absolute;top:.6rem;left:.6rem;display:flex;flex-direction:column;gap:.3rem;align-items:flex-start;z-index:2;}
    .pbadge{font-size:.68rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;padding:.28rem .55rem;border-radius:999px;background:#fff;color:var(--ink);box-shadow:var(--shadow-sm);}
    .pbadge-sale{background:var(--terracotta);color:#fff;} .pbadge-new{background:var(--forest);color:#fff;}
    .pbadge-muted{background:#5E5A51;color:#fff;}
    .pcard-wish{position:absolute;top:.55rem;right:.55rem;z-index:2;}
    .pcard-wish .btn{width:36px;height:36px;padding:0;border-radius:50%;background:#fff;border:0;box-shadow:var(--shadow-sm);color:var(--ink);}
    .pcard-wish .btn:hover,.pcard-wish .btn.active{color:var(--terracotta);}
    .pcard-quick{position:absolute;left:.6rem;right:.6rem;bottom:.6rem;z-index:2;opacity:0;transform:translateY(8px);transition:opacity .2s,transform .2s;}
    .pcard:hover .pcard-quick,.pcard:focus-within .pcard-quick{opacity:1;transform:none;}
    @media(hover:none){.pcard-quick{display:none;}}
    .pcard-body{padding:.85rem .95rem 1rem;display:flex;flex-direction:column;gap:.2rem;flex:1;}
    .pcard-cat{font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);font-weight:600;}
    .pcard-title{font-family:var(--font-body);font-size:.95rem;font-weight:600;line-height:1.35;margin:0;}
    .pcard-title a{color:var(--ink);} .pcard-title a::after{content:"";position:absolute;inset:0;z-index:1;}
    .pcard-foot{margin-top:auto;padding-top:.35rem;display:flex;align-items:center;justify-content:space-between;gap:.5rem;}
    .pcard-add{position:relative;z-index:2;}

    /* Horizontal product rail */
    .rail{display:grid;grid-auto-flow:column;grid-auto-columns:calc((100% - 3 * 1.25rem) / 4);gap:1.25rem;overflow-x:auto;scroll-snap-type:x mandatory;scrollbar-width:none;padding:.25rem .1rem 1rem;}
    .rail::-webkit-scrollbar{display:none;}
    .rail > *{scroll-snap-align:start;}
    @media(max-width:991.98px){.rail{grid-auto-columns:calc((100% - 2 * 1rem) / 2.6);gap:1rem;}}
    @media(max-width:575.98px){.rail{grid-auto-columns:68%;}}

    /* Category tiles */
    .cat-tile{position:relative;display:block;border-radius:var(--radius);overflow:hidden;aspect-ratio:4/5;background:var(--sage-light);}
    .cat-tile img{width:100%;height:100%;object-fit:cover;transition:transform .5s ease;}
    .cat-tile:hover img{transform:scale(1.06);}
    .cat-tile::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0) 45%,rgba(28,30,22,.72));}
    .cat-tile-body{position:absolute;left:1rem;right:1rem;bottom:.9rem;z-index:1;color:#fff;}
    .cat-tile-body h3{color:#fff;font-size:1.15rem;margin:0;}
    .cat-tile-body span{font-size:.8rem;opacity:.9;}

    /* Quantity stepper */
    .qty{display:inline-flex;align-items:center;border:1px solid var(--line);border-radius:999px;background:#fff;overflow:hidden;}
    .qty button{width:38px;height:40px;border:0;background:none;color:var(--ink);font-size:1.05rem;}
    .qty button:hover{background:var(--cream);}
    .qty input{width:46px;border:0;text-align:center;font-weight:600;-moz-appearance:textfield;appearance:textfield;background:none;}
    .qty input::-webkit-outer-spin-button,.qty input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0;}
    .qty-sm button{width:32px;height:34px;} .qty-sm input{width:38px;}

    /* Checkout steps */
    .steps{display:flex;align-items:center;gap:.5rem;list-style:none;padding:0;margin:0 0 1.75rem;font-size:.85rem;font-weight:600;color:var(--muted);flex-wrap:wrap;}
    .steps li{display:flex;align-items:center;gap:.45rem;}
    .steps li + li::before{content:"";width:28px;height:1px;background:var(--line);margin-right:.25rem;}
    .steps .num{width:26px;height:26px;border-radius:50%;border:1px solid var(--line);display:inline-flex;align-items:center;justify-content:center;background:#fff;font-size:.78rem;}
    .steps .done .num,.steps .current .num{background:var(--forest);border-color:var(--forest);color:#fff;}
    .steps .current{color:var(--ink);}

    /* Newsletter & footer */
    .newsletter{background:var(--sage-light);border-radius:calc(var(--radius) * 1.4);}
    .footer{background:var(--forest-dark);color:#E9E6DC;}
    .footer h2{font-family:var(--font-body);font-size:.78rem;letter-spacing:.14em;text-transform:uppercase;color:#fff;font-weight:700;margin-bottom:1rem;}
    .footer a{color:#D6D3C8;} .footer a:hover{color:#fff;}
    .footer li{margin-bottom:.45rem;}
    .footer .social a{width:38px;height:38px;border-radius:50%;border:1px solid rgba(255,255,255,.25);display:inline-flex;align-items:center;justify-content:center;}
    .footer .social a:hover{background:rgba(255,255,255,.1);}

    .whatsapp-float{position:fixed;bottom:22px;right:22px;width:54px;height:54px;background:#25D366;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.6rem;box-shadow:0 6px 20px rgba(37,211,102,.45);z-index:1040;transition:transform .15s;}
    .whatsapp-float:hover{transform:scale(1.08);color:#fff;}
    .has-sticky-buy .whatsapp-float{bottom:92px;}

    .toast-msg{position:fixed;bottom:90px;left:50%;transform:translateX(-50%);z-index:1100;color:#fff;padding:.75rem 1.15rem;border-radius:999px;box-shadow:var(--shadow);font-size:.9rem;display:flex;gap:.75rem;align-items:center;max-width:92vw;animation:toastIn .2s ease;}
    .toast-msg.ok{background:var(--forest-dark);} .toast-msg.err{background:#A33A2B;}
    .toast-msg a{color:#fff;text-decoration:underline;font-weight:600;white-space:nowrap;}
    @keyframes toastIn{from{opacity:0;transform:translate(-50%,8px);}to{opacity:1;transform:translate(-50%,0);}}
</style>
