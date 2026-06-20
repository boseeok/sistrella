<?php $__env->startSection('title', 'Verification Queue'); ?>
<?php $__env->startSection('heading', 'Payment Verification Queue'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-end mb-3"><a href="<?php echo e(route('admin.payments.index')); ?>" class="btn btn-sm btn-light">All payments</a></div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Order</th><th>Customer</th><th>Amount</th><th>Method</th><th>Ref</th><th>Proof</th><th>Submitted</th><th class="text-end">Action</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><a href="<?php echo e(route('admin.orders.show', $p->order->order_number)); ?>"><?php echo e($p->order->order_number); ?></a></td>
                        <td class="small"><?php echo e($p->order->customer_name); ?></td>
                        <td class="fw-semibold"><?php echo e(money($p->amount)); ?></td>
                        <td class="small"><?php echo e(str_replace('_',' ',$p->method)); ?></td>
                        <td class="small"><?php echo e($p->reference ?: '—'); ?></td>
                        <td><?php if($p->proof_url): ?><a href="<?php echo e($p->proof_url); ?>" target="_blank">View</a><?php else: ?> <span class="text-muted">—</span><?php endif; ?></td>
                        <td class="small text-muted"><?php echo e($p->created_at->diffForHumans()); ?></td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <form action="<?php echo e(route('admin.payments.verify', $p)); ?>" method="POST"><?php echo csrf_field(); ?><button class="btn btn-success btn-sm">Verify</button></form>
                                <form action="<?php echo e(route('admin.payments.reject', $p)); ?>" method="POST"><?php echo csrf_field(); ?><button class="btn btn-outline-danger btn-sm">Reject</button></form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Nothing awaiting verification. 🎉</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3"><?php echo e($payments->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/payments/queue.blade.php ENDPATH**/ ?>