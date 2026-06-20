<?php $__env->startSection('title', $customer->name); ?>
<?php $__env->startSection('heading', 'Customer'); ?>

<?php $__env->startSection('content'); ?>
<a href="<?php echo e(route('admin.customers.index')); ?>" class="btn btn-sm btn-light mb-3"><i class="bi bi-chevron-left"></i> Back</a>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card p-4 text-center mb-3">
            <img src="<?php echo e($customer->avatar_url); ?>" width="80" height="80" class="rounded-circle mx-auto mb-2">
            <h5 class="fw-bold mb-0"><?php echo e($customer->name); ?></h5>
            <p class="text-muted small mb-2"><?php echo e($customer->email); ?></p>
            <p class="small mb-1"><?php echo e($customer->phone); ?></p>
            <span class="badge bg-<?php echo e($customer->is_active ? 'success':'danger'); ?>"><?php echo e($customer->is_active ? 'Active' : 'Inactive'); ?></span>
            <form action="<?php echo e(route('admin.customers.toggle', $customer)); ?>" method="POST" class="mt-3"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <button class="btn btn-sm btn-outline-<?php echo e($customer->is_active ? 'danger':'success'); ?> w-100"><?php echo e($customer->is_active ? 'Deactivate' : 'Activate'); ?></button>
            </form>
        </div>

        <?php if($customer->addresses->isNotEmpty()): ?>
            <div class="card p-3">
                <h6 class="fw-bold mb-2">Addresses</h6>
                <?php $__currentLoopData = $customer->addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="small text-muted mb-2"><?php echo e($a->label); ?> — <?php echo e($a->formatted); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-8">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">Orders</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light"><tr><th>Order</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><a href="<?php echo e(route('admin.orders.show', $o->order_number)); ?>"><?php echo e($o->order_number); ?></a></td>
                                <td><?php echo e(money($o->grand_total)); ?></td>
                                <td><span class="badge bg-<?php echo e($o->status_color); ?>"><?php echo e($o->status_label); ?></span></td>
                                <td class="small text-muted"><?php echo e($o->created_at->format('M d, Y')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="4" class="text-center text-muted py-3">No orders.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-2"><?php echo e($orders->links()); ?></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/customers/show.blade.php ENDPATH**/ ?>