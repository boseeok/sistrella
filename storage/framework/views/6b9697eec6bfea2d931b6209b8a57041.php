<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', setting('store_name', 'Crochet Store')); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', setting('store_tagline', 'Handmade crochet products')); ?>">
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo.png')); ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    <?php echo $__env->make('partials.theme', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<header class="shadow-sm sticky-top bg-white">
    
    <div class="topbar text-white small">
        <div class="marquee">
            <?php
                $announcements = [
                    '<i class="bi bi-telephone-fill me-1"></i>'.e(setting('store_phone')),
                    '<i class="bi bi-shield-check me-1"></i>'.e(prepayment_notice()),
                    '<i class="bi bi-truck me-1"></i>Cash on Delivery available',
                    '<i class="bi bi-stars me-1"></i>Custom crochet orders welcome',
                ];
            ?>
            
            <?php for($i = 0; $i < 2; $i++): ?>
                <div class="marquee-track" aria-hidden="<?php echo e($i ? 'true' : 'false'); ?>">
                    <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="marquee-item"><?php echo $item; ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('orders.track.form')); ?>" class="marquee-item text-white text-decoration-none"><i class="bi bi-box-seam me-1"></i>Track your order &rarr;</a>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    
    <nav class="navbar navbar-expand-lg bg-white">
        <div class="container">
            <a class="navbar-brand fw-bold brand d-inline-flex align-items-center" href="<?php echo e(route('home')); ?>">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="<?php echo e(setting('store_name', 'Sistrella')); ?>" style="height:52px;width:auto;">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                
                <form action="<?php echo e(route('search')); ?>" method="GET" class="d-flex mx-lg-auto my-2 my-lg-0 search-form">
                    <input type="search" name="search" class="form-control" placeholder="Search handmade crochet..." value="<?php echo e(request('search')); ?>">
                    <button class="btn btn-brand ms-2" type="submit"><i class="bi bi-search"></i></button>
                </form>

                <ul class="navbar-nav align-items-lg-center gap-lg-1">
                    <?php if(auth()->guard()->check()): ?>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" data-bs-toggle="dropdown" title="Notifications">
                                <i class="bi bi-bell fs-5"></i>
                                <?php if(($notifCount ?? 0) > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php echo e($notifCount > 9 ? '9+' : $notifCount); ?></span>
                                <?php endif; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end p-0 notif-menu shadow">
                                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                    <strong class="small">Notifications</strong>
                                    <?php if(($notifCount ?? 0) > 0): ?>
                                        <form action="<?php echo e(route('account.notifications.readAll')); ?>" method="POST"><?php echo csrf_field(); ?>
                                            <button class="btn btn-link btn-sm p-0 small">Mark all read</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                                <?php $__empty_1 = true; $__currentLoopData = ($recentNotifs ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <a href="<?php echo e(route('account.notifications.read', $n->id)); ?>" class="dropdown-item d-flex gap-2 py-2 <?php echo e($n->read_at ? '' : 'bg-light'); ?>" style="white-space:normal">
                                        <i class="bi <?php echo e($n->data['icon'] ?? 'bi-bell'); ?> text-brand mt-1"></i>
                                        <span class="small">
                                            <span class="fw-semibold d-block"><?php echo e($n->data['title'] ?? 'Update'); ?></span>
                                            <span class="text-muted"><?php echo e(\Illuminate\Support\Str::limit($n->data['message'] ?? '', 70)); ?></span>
                                            <span class="text-muted d-block" style="font-size:.7rem"><?php echo e($n->created_at->diffForHumans()); ?></span>
                                        </span>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center text-muted small py-4">No notifications yet.</div>
                                <?php endif; ?>
                                <a href="<?php echo e(route('account.notifications')); ?>" class="dropdown-item text-center small border-top py-2 text-brand">View all</a>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link position-relative" href="<?php echo e(route('wishlist.index')); ?>" title="Wishlist">
                                <i class="bi bi-heart fs-5"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link position-relative" href="<?php echo e(route('cart.index')); ?>" title="Cart">
                            <i class="bi bi-bag fs-5"></i>
                            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-brand" <?php if(($cartCount ?? 0) === 0): ?> style="display:none" <?php endif; ?>><?php echo e($cartCount ?? 0); ?></span>
                        </a>
                    </li>

                    <?php if(auth()->guard()->check()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-5"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo e(route('account.dashboard')); ?>">My Account</a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('account.orders')); ?>">My Orders</a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('account.addresses')); ?>">Addresses</a></li>
                                <?php if(auth()->user()->isAdmin() || auth()->user()->hasPermission('dashboard.access')): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.dashboard')); ?>"><i class="bi bi-speedometer2 me-1"></i>Admin Panel</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="<?php echo e(route('logout')); ?>" method="POST"><?php echo csrf_field(); ?>
                                        <button class="dropdown-item" type="submit">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('login')); ?>">Login</a></li>
                        <li class="nav-item"><a class="btn btn-accent btn-sm ms-lg-2" href="<?php echo e(route('register')); ?>">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    
    <div class="category-bar border-top">
        <div class="container">
            <div class="d-flex align-items-stretch flex-wrap">
                
                <div class="dropdown browse-all">
                    <button class="btn btn-brand dropdown-toggle d-flex align-items-center gap-2 h-100" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-grid-3x3-gap-fill"></i> Browse All Categories
                    </button>
                    <ul class="dropdown-menu browse-menu shadow">
                        <li><a class="dropdown-item fw-semibold" href="<?php echo e(route('shop')); ?>"><i class="bi bi-grid me-2 text-brand"></i>All Products</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php $__currentLoopData = ($menuCategories ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('categories.show', $cat->slug)); ?>">
                                    <?php if($cat->icon): ?><i class="bi <?php echo e($cat->icon); ?> me-2 text-brand"></i><?php endif; ?><span class="fw-semibold"><?php echo e($cat->name); ?></span>
                                </a>
                                <?php if($cat->children->count()): ?>
                                    <div class="px-3 pb-2">
                                        <?php $__currentLoopData = $cat->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a class="d-inline-block small text-muted me-2" href="<?php echo e(route('categories.show', $child->slug)); ?>"><?php echo e($child->name); ?></a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                
                <ul class="nav flex-wrap align-items-center ms-lg-2">
                    <li class="nav-item"><a class="nav-link py-2 px-3" href="<?php echo e(route('home')); ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link py-2 px-3" href="<?php echo e(route('shop')); ?>">Shop</a></li>
                    <li class="nav-item"><a class="nav-link py-2 px-3" href="<?php echo e(route('shop')); ?>?sort=popular">Top Deals</a></li>
                    <li class="nav-item"><a class="nav-link py-2 px-3" href="<?php echo e(route('custom.create')); ?>">Custom Order</a></li>
                    <li class="nav-item"><a class="nav-link py-2 px-3" href="<?php echo e(route('about')); ?>">About</a></li>
                    <li class="nav-item"><a class="nav-link py-2 px-3" href="<?php echo e(route('contact')); ?>">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

<main class="py-4">
    <?php echo $__env->yieldContent('content'); ?>
</main>

<footer class="footer mt-5 pt-5 pb-3 text-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="<?php echo e(setting('store_name', 'Sistrella')); ?>" class="bg-white rounded-3 p-2 mb-2" style="height:64px;width:auto;">
                <h5 class="brand text-white"><?php echo e(setting('store_name', 'Sistrella')); ?></h5>
                <p class="small opacity-75"><?php echo e(setting('store_tagline')); ?></p>
                <p class="small mb-1"><i class="bi bi-geo-alt me-1"></i><?php echo e(setting('store_address')); ?></p>
                <p class="small mb-1"><i class="bi bi-telephone me-1"></i><?php echo e(setting('store_phone')); ?></p>
                <p class="small"><i class="bi bi-envelope me-1"></i><?php echo e(setting('store_email')); ?></p>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="text-white">Shop</h6>
                <ul class="list-unstyled small">
                    <li><a href="<?php echo e(route('shop')); ?>">All Products</a></li>
                    <li><a href="<?php echo e(route('custom.create')); ?>">Custom Orders</a></li>
                    <li><a href="<?php echo e(route('orders.track.form')); ?>">Track Order</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="text-white">Company</h6>
                <ul class="list-unstyled small">
                    <li><a href="<?php echo e(route('about')); ?>">About Us</a></li>
                    <li><a href="<?php echo e(route('contact')); ?>">Contact</a></li>
                    <li><a href="<?php echo e(route('policy.privacy')); ?>">Privacy Policy</a></li>
                    <li><a href="<?php echo e(route('policy.terms')); ?>">Terms</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="text-white">Newsletter</h6>
                <p class="small opacity-75">Subscribe for new arrivals & offers.</p>
                <form action="<?php echo e(route('newsletter.subscribe')); ?>" method="POST" class="d-flex"><?php echo csrf_field(); ?>
                    <input type="email" name="email" class="form-control form-control-sm" placeholder="Your email" required>
                    <button class="btn btn-brand btn-sm ms-2">Join</button>
                </form>
                <div class="mt-3 d-flex gap-3 fs-5">
                    <?php if(setting('facebook_url')): ?><a href="<?php echo e(setting('facebook_url')); ?>" class="text-white"><i class="bi bi-facebook"></i></a><?php endif; ?>
                    <?php if(setting('instagram_url')): ?><a href="<?php echo e(setting('instagram_url')); ?>" class="text-white"><i class="bi bi-instagram"></i></a><?php endif; ?>
                    <?php if(setting('tiktok_url')): ?><a href="<?php echo e(setting('tiktok_url')); ?>" class="text-white"><i class="bi bi-tiktok"></i></a><?php endif; ?>
                </div>
            </div>
        </div>
        <hr class="border-light opacity-25 mt-4">
        <div class="text-center small opacity-75">&copy; <?php echo e(date('Y')); ?> <?php echo e(setting('store_name', 'Crochet Store')); ?>. Handmade with <i class="bi bi-heart-fill text-brand"></i> in Nepal.</div>
    </div>
</footer>


<a href="<?php echo e($whatsappFloat ?? '#'); ?>" target="_blank" rel="noopener" class="whatsapp-float" title="Chat with us on WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php echo $__env->make('partials.cart-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\User\crochet-store\resources\views/layouts/app.blade.php ENDPATH**/ ?>