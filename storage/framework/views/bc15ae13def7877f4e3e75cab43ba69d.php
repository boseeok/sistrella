<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> — Admin</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo.png')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --brand:#3D4B33;--brand-dark:#2E3A27;
            --forest:#3D4B33;--terracotta:#B26B3C;--terracotta-dark:#9A5A30;
            --accent:#B26B3C;--accent-dark:#9A5A30;--ink:#2F3A2A;
            --sidebar:#2E3A27;          /* deep forest sidebar */
        }
        body{font-family:'Inter',system-ui,sans-serif;background:#F6F3ED;color:var(--ink);}
        .text-brand{color:var(--forest)!important;} .bg-brand{background:var(--forest)!important;}
        .text-accent{color:var(--terracotta)!important;} .bg-accent{background:var(--accent)!important;}
        .btn-brand{background:var(--forest);border-color:var(--forest);color:#F6F3ED;font-weight:600;}
        .btn-brand:hover{background:var(--brand-dark);border-color:var(--brand-dark);color:#fff;}
        .btn-outline-brand{color:var(--forest);border-color:var(--forest);}
        .btn-outline-brand:hover{background:var(--forest);color:#F6F3ED;}
        .btn-accent{background:var(--accent);border-color:var(--accent);color:var(--ink);font-weight:600;}
        .btn-accent:hover{background:var(--accent-dark);border-color:var(--accent-dark);color:var(--ink);}
        .admin-wrap{display:flex;min-height:100vh;}
        .sidebar{width:250px;background:var(--sidebar);color:#cfd2e0;flex-shrink:0;position:fixed;top:0;bottom:0;left:0;overflow-y:auto;transition:.2s;z-index:1040;}
        .sidebar .brand{color:#fff;font-weight:700;font-size:1.2rem;}
        .sidebar a{color:#b8bcce;text-decoration:none;display:flex;align-items:center;gap:.6rem;padding:.6rem 1.2rem;border-radius:.5rem;margin:.1rem .6rem;font-size:.92rem;}
        .sidebar a:hover{background:rgba(255,255,255,.07);color:#fff;}
        .sidebar a.active{background:var(--terracotta);color:#fff;font-weight:600;}
        .sidebar .nav-section{font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:#6b6f85;padding:.9rem 1.4rem .3rem;}
        .content{flex:1;margin-left:250px;min-width:0;}
        .topbar{background:#fff;box-shadow:0 1px 8px rgba(0,0,0,.05);}
        .card{border:none;border-radius:.9rem;box-shadow:0 2px 12px rgba(0,0,0,.05);}
        .stat-card .icon{width:48px;height:48px;border-radius:.7rem;display:flex;align-items:center;justify-content:center;font-size:1.3rem;}
        .table>:not(caption)>*>*{padding:.65rem .75rem;}
        @media(max-width:991px){.sidebar{left:-260px;} .sidebar.show{left:0;} .content{margin-left:0;}}
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<?php $u = auth()->user(); ?>
<div class="admin-wrap">
    <aside class="sidebar" id="adminSidebar">
        <div class="p-3 d-flex align-items-center justify-content-between">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="brand d-inline-flex align-items-center gap-2">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="<?php echo e(setting('store_name', 'Sistrella')); ?>" class="bg-white rounded-2 p-1" style="height:40px;width:auto;">
                <span><?php echo e(setting('store_name', 'Sistrella')); ?></span>
            </a>
            <button class="btn btn-sm text-light d-lg-none" onclick="document.getElementById('adminSidebar').classList.remove('show')"><i class="bi bi-x-lg"></i></button>
        </div>

        <?php
            $is = fn($pattern) => request()->routeIs($pattern) ? 'active' : '';
        ?>

        <a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo e($is('admin.dashboard')); ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>

        <div class="nav-section">Sales</div>
        <?php if($u->hasPermission('orders.view')): ?>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="<?php echo e($is('admin.orders.*')); ?>"><i class="bi bi-receipt"></i> Orders</a>
        <?php endif; ?>
        <?php if($u->hasPermission('payments.manage')): ?>
            <a href="<?php echo e(route('admin.payments.queue')); ?>" class="<?php echo e($is('admin.payments.*')); ?>"><i class="bi bi-cash-coin"></i> Payments
                <?php $vq = \App\Models\Payment::where('status','submitted')->count(); ?>
                <?php if($vq): ?><span class="badge bg-warning text-dark ms-auto"><?php echo e($vq); ?></span><?php endif; ?>
            </a>
        <?php endif; ?>
        <?php if($u->hasPermission('custom.manage')): ?>
            <a href="<?php echo e(route('admin.custom.index')); ?>" class="<?php echo e($is('admin.custom.*')); ?>"><i class="bi bi-stars"></i> Custom Requests</a>
        <?php endif; ?>

        <div class="nav-section">Catalogue</div>
        <?php if($u->hasPermission('products.manage')): ?>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="<?php echo e($is('admin.products.*')); ?>"><i class="bi bi-box-seam"></i> Products</a>
        <?php endif; ?>
        <?php if($u->hasPermission('categories.manage')): ?>
            <a href="<?php echo e(route('admin.categories.index')); ?>" class="<?php echo e($is('admin.categories.*')); ?>"><i class="bi bi-diagram-3"></i> Categories</a>
        <?php endif; ?>
        <?php if($u->hasPermission('inventory.manage')): ?>
            <a href="<?php echo e(route('admin.inventory.index')); ?>" class="<?php echo e($is('admin.inventory.*')); ?>"><i class="bi bi-clipboard-data"></i> Inventory</a>
        <?php endif; ?>

        <div class="nav-section">Marketing</div>
        <?php if($u->hasPermission('coupons.manage')): ?>
            <a href="<?php echo e(route('admin.coupons.index')); ?>" class="<?php echo e($is('admin.coupons.*')); ?>"><i class="bi bi-ticket-perforated"></i> Coupons</a>
        <?php endif; ?>
        <?php if($u->hasPermission('marketing.manage')): ?>
            <a href="<?php echo e(route('admin.banners.index')); ?>" class="<?php echo e($is('admin.banners.*')); ?>"><i class="bi bi-images"></i> Banners</a>
            <a href="<?php echo e(route('admin.marketing.index')); ?>" class="<?php echo e($is('admin.marketing.*')); ?>"><i class="bi bi-megaphone"></i> Newsletter & Messages</a>
        <?php endif; ?>

        <div class="nav-section">People</div>
        <?php if($u->hasPermission('customers.view')): ?>
            <a href="<?php echo e(route('admin.customers.index')); ?>" class="<?php echo e($is('admin.customers.*')); ?>"><i class="bi bi-people"></i> Customers</a>
        <?php endif; ?>
        <?php if($u->hasPermission('roles.manage')): ?>
            <a href="<?php echo e(route('admin.staff.index')); ?>" class="<?php echo e($is('admin.staff.*')); ?>"><i class="bi bi-person-badge"></i> Staff</a>
            <a href="<?php echo e(route('admin.roles.index')); ?>" class="<?php echo e($is('admin.roles.*')); ?>"><i class="bi bi-shield-lock"></i> Roles</a>
        <?php endif; ?>

        <div class="nav-section">System</div>
        <?php if($u->hasPermission('reports.view')): ?>
            <a href="<?php echo e(route('admin.reports.index')); ?>" class="<?php echo e($is('admin.reports.*')); ?>"><i class="bi bi-graph-up"></i> Reports</a>
        <?php endif; ?>
        <?php if($u->hasPermission('settings.manage')): ?>
            <a href="<?php echo e(route('admin.settings.index')); ?>" class="<?php echo e($is('admin.settings.*')); ?>"><i class="bi bi-gear"></i> Settings</a>
        <?php endif; ?>
        <a href="<?php echo e(route('home')); ?>" target="_blank"><i class="bi bi-shop"></i> View Store</a>
    </aside>

    <div class="content">
        <nav class="topbar d-flex align-items-center justify-content-between px-3 py-2 mb-4">
            <button class="btn btn-light d-lg-none" onclick="document.getElementById('adminSidebar').classList.add('show')"><i class="bi bi-list"></i></button>
            <h5 class="mb-0 fw-semibold"><?php echo $__env->yieldContent('heading', 'Dashboard'); ?></h5>

            <?php
                $adminUnread = $u->unreadNotifications()->count();
                $adminNotifs = $u->notifications()->latest()->limit(8)->get();
            ?>

            <div class="d-flex align-items-center gap-2 ms-auto">
                <div class="dropdown">
                    <a href="#" class="position-relative d-flex align-items-center text-dark px-2" data-bs-toggle="dropdown" title="Notifications">
                        <i class="bi bi-bell fs-5"></i>
                        <?php if($adminUnread > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php echo e($adminUnread > 9 ? '9+' : $adminUnread); ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow p-0" style="min-width:340px;max-height:75vh;overflow:auto">
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <strong class="small">Notifications</strong>
                            <?php if($adminUnread > 0): ?>
                                <form action="<?php echo e(route('admin.notifications.readAll')); ?>" method="POST"><?php echo csrf_field(); ?>
                                    <button class="btn btn-link btn-sm p-0 small">Mark all read</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <?php $__empty_1 = true; $__currentLoopData = $adminNotifs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e(route('admin.notifications.read', $n->id)); ?>" class="dropdown-item d-flex gap-2 py-2 <?php echo e($n->read_at ? '' : 'bg-light'); ?>" style="white-space:normal">
                                <i class="bi <?php echo e($n->data['icon'] ?? 'bi-bell'); ?> text-brand mt-1"></i>
                                <span class="small">
                                    <span class="fw-semibold d-block"><?php echo e($n->data['title'] ?? 'Update'); ?></span>
                                    <span class="text-muted"><?php echo e(\Illuminate\Support\Str::limit($n->data['message'] ?? '', 70)); ?></span>
                                    <span class="text-muted d-block" style="font-size:.7rem"><?php echo e($n->created_at->diffForHumans()); ?></span>
                                </span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center text-muted small py-4">You're all caught up! 🎉</div>
                        <?php endif; ?>
                        <a href="<?php echo e(route('admin.notifications.index')); ?>" class="dropdown-item text-center small border-top py-2 text-brand">View all</a>
                    </div>
                </div>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="<?php echo e($u->avatar_url); ?>" class="rounded-circle me-2" width="34" height="34" alt="">
                    <span class="d-none d-sm-inline"><?php echo e($u->name); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><span class="dropdown-item-text small text-muted"><?php echo e($u->roles->pluck('display_name')->join(', ')); ?></span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('account.dashboard')); ?>">My Account</a></li>
                    <li>
                        <form action="<?php echo e(route('admin.logout')); ?>" method="POST"><?php echo csrf_field(); ?>
                            <button class="dropdown-item text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
            </div>
        </nav>

        <div class="px-3 px-md-4 pb-5">
            <?php echo $__env->make('partials.admin-flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\User\crochet-store\resources\views/layouts/admin.blade.php ENDPATH**/ ?>