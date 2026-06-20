<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('heading', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $cards = [
        ['Total Sales', money($widgets['total_sales']), 'bi-currency-dollar', 'success'],
        ['Collected', money($widgets['collected']), 'bi-wallet2', 'primary'],
        ['Orders', $widgets['orders_total'], 'bi-receipt', 'info'],
        ['Today', $widgets['orders_today'], 'bi-calendar-day', 'secondary'],
        ['Pending Payment', $widgets['orders_pending'], 'bi-hourglass-split', 'warning'],
        ['Verify Queue', $widgets['verification_queue'], 'bi-patch-check', 'danger'],
        ['Customers', $widgets['customers'], 'bi-people', 'primary'],
        ['Low Stock', $widgets['low_stock'], 'bi-box', 'warning'],
    ];
    $bg = ['success'=>'#e7f6ec','primary'=>'#e7eefc','info'=>'#e7f4f8','secondary'=>'#eceef1','warning'=>'#fdf3e2','danger'=>'#fbeaec'];
    $fg = ['success'=>'#1b7a3d','primary'=>'#1c4fd8','info'=>'#0c7a99','secondary'=>'#5a6270','warning'=>'#b5780b','danger'=>'#c62331'];
?>

<div class="row g-3 mb-4">
    <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $icon, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon" style="background:<?php echo e($bg[$color]); ?>;color:<?php echo e($fg[$color]); ?>"><i class="bi <?php echo e($icon); ?>"></i></div>
                    <div>
                        <div class="fs-5 fw-bold"><?php echo e($value); ?></div>
                        <small class="text-muted"><?php echo e($label); ?></small>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card p-3 h-100">
            <h6 class="fw-bold mb-3">Sales — last 14 days</h6>
            <canvas id="salesChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card p-3 h-100">
            <h6 class="fw-bold mb-3">Verification Queue</h6>
            <?php $__empty_1 = true; $__currentLoopData = $verificationQueue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('admin.orders.show', $order->order_number)); ?>" class="d-flex justify-content-between align-items-center py-2 border-bottom text-decoration-none text-dark small">
                    <span><?php echo e($order->order_number); ?><br><span class="text-muted"><?php echo e($order->customer_name); ?></span></span>
                    <span class="badge bg-warning text-dark"><?php echo e(money($order->advance_amount)); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted small mb-0">Nothing awaiting verification. 🎉</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between mb-3"><h6 class="fw-bold mb-0">Recent Orders</h6><a href="<?php echo e(route('admin.orders.index')); ?>" class="small">All orders</a></div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><a href="<?php echo e(route('admin.orders.show', $order->order_number)); ?>"><?php echo e($order->order_number); ?></a></td>
                                <td class="small"><?php echo e($order->customer_name); ?></td>
                                <td><?php echo e(money($order->grand_total)); ?></td>
                                <td><span class="badge bg-<?php echo e($order->status_color); ?>"><?php echo e($order->status_label); ?></span></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card p-3 h-100">
            <h6 class="fw-bold mb-3">Low Stock Alert</h6>
            <?php $__empty_1 = true; $__currentLoopData = $lowStock; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex justify-content-between py-2 border-bottom small">
                    <span><?php echo e($p->name); ?></span>
                    <span class="badge bg-<?php echo e($p->stock <= 0 ? 'danger' : 'warning text-dark'); ?>"><?php echo e($p->stock); ?> left</span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted small mb-0">All products well stocked.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
fetch("<?php echo e(route('admin.dashboard.chart', 'sales')); ?>").then(r => r.json()).then(d => {
    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: { labels: d.labels, datasets: [{ label: 'Sales', data: d.values, borderColor: '#0D9488', backgroundColor: 'rgba(13,148,136,.1)', fill: true, tension: .35 }] },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>