<?php $__env->startSection('title', 'Customers'); ?>
<?php $__env->startSection('heading', 'Customers'); ?>

<?php $__env->startSection('content'); ?>
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4"><input type="text" name="search" value="<?php echo e($filters['search'] ?? ''); ?>" class="form-control form-control-sm" placeholder="Name, email or phone"></div>
        <div class="col-md-2"><button class="btn btn-brand btn-sm w-100">Search</button></div>
    </form>
</div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Customer</th><th>Contact</th><th>Orders</th><th>Lifetime value</th><th>Status</th><th>Joined</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><div class="d-flex align-items-center gap-2"><img src="<?php echo e($u->avatar_url); ?>" width="34" height="34" class="rounded-circle"><span class="small fw-semibold"><?php echo e($u->name); ?></span></div></td>
                        <td class="small text-muted"><?php echo e($u->email); ?><br><?php echo e($u->phone); ?></td>
                        <td><?php echo e($u->orders_count); ?></td>
                        <td><?php echo e(money($u->orders_sum_grand_total ?? 0)); ?></td>
                        <td><?php echo $u->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'; ?></td>
                        <td class="small text-muted"><?php echo e($u->created_at->format('M d, Y')); ?></td>
                        <td class="text-end"><a href="<?php echo e(route('admin.customers.show', $u)); ?>" class="btn btn-sm btn-outline-brand">View</a></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No customers found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3"><?php echo e($customers->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/customers/index.blade.php ENDPATH**/ ?>