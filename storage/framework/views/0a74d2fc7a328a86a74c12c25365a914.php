<?php $__env->startSection('title', 'Orders'); ?>
<?php $__env->startSection('heading', 'Orders'); ?>

<?php $__env->startSection('content'); ?>
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small">Search</label>
            <input type="text" name="search" value="<?php echo e($filters['search'] ?? ''); ?>" class="form-control form-control-sm" placeholder="Order #, name, phone">
        </div>
        <div class="col-md-3">
            <label class="form-label small">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All statuses</option>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" <?php echo e(($filters['status'] ?? '') === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2"><label class="form-label small">From</label><input type="date" name="from" value="<?php echo e($filters['from'] ?? ''); ?>" class="form-control form-control-sm"></div>
        <div class="col-md-2"><label class="form-label small">To</label><input type="date" name="to" value="<?php echo e($filters['to'] ?? ''); ?>" class="form-control form-control-sm"></div>
        <div class="col-md-2"><button class="btn btn-brand btn-sm w-100">Filter</button></div>
    </form>
</div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Order</th><th>Customer</th><th>Items</th><th>Total</th><th>Paid</th><th>Payment</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><a href="<?php echo e(route('admin.orders.show', $order->order_number)); ?>" class="fw-semibold"><?php echo e($order->order_number); ?></a></td>
                        <td class="small"><?php echo e($order->customer_name); ?><br><span class="text-muted"><?php echo e($order->customer_phone); ?></span></td>
                        <td><?php echo e($order->items_count); ?></td>
                        <td><?php echo e(money($order->grand_total)); ?></td>
                        <td class="small text-success"><?php echo e(money($order->amount_paid)); ?></td>
                        <td><span class="badge bg-light text-dark"><?php echo e($order->requires_prepayment ? 'Prepay' : 'COD'); ?></span></td>
                        <td><span class="badge bg-<?php echo e($order->status_color); ?>"><?php echo e($order->status_label); ?></span></td>
                        <td class="small text-muted"><?php echo e($order->created_at->format('M d, Y')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">No orders found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3"><?php echo e($orders->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>