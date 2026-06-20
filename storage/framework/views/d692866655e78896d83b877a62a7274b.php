<?php $__env->startSection('title', 'My Account'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2 class="section-title mb-4">My Account</h2>
    <div class="row g-4">
        <div class="col-lg-3"><?php echo $__env->make('partials.account-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
        <div class="col-lg-9">
            <?php if (! (auth()->user()->hasVerifiedEmail())): ?>
                <div class="alert alert-warning d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-exclamation-triangle me-1"></i>Please verify your email address.</span>
                    <a href="<?php echo e(route('verification.notice')); ?>" class="btn btn-sm btn-warning">Verify now</a>
                </div>
            <?php endif; ?>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card p-3 text-center"><div class="fs-3 fw-bold text-brand"><?php echo e($ordersCount); ?></div><small class="text-muted">Total Orders</small></div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 text-center"><div class="fs-3 fw-bold text-warning"><?php echo e($pendingCount); ?></div><small class="text-muted">Pending Payment</small></div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 text-center"><div class="fs-3 fw-bold text-danger"><?php echo e($wishlistCount); ?></div><small class="text-muted">Wishlist Items</small></div>
                </div>
            </div>

            <div class="card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-bold mb-0">Recent Orders</h5>
                    <a href="<?php echo e(route('account.orders')); ?>" class="small">View all</a>
                </div>
                <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('account.orders.show', $order->order_number)); ?>" class="d-flex justify-content-between align-items-center py-2 border-bottom text-decoration-none text-dark">
                        <div>
                            <div class="fw-semibold small"><?php echo e($order->order_number); ?></div>
                            <small class="text-muted"><?php echo e($order->created_at->format('M d, Y')); ?></small>
                        </div>
                        <div class="text-end">
                            <div class="price small"><?php echo e(money($order->grand_total)); ?></div>
                            <span class="badge bg-<?php echo e($order->status_color); ?>"><?php echo e($order->status_label); ?></span>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted text-center py-3 mb-0">No orders yet. <a href="<?php echo e(route('shop')); ?>">Start shopping</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/shop/account/dashboard.blade.php ENDPATH**/ ?>