<?php $__env->startSection('title', 'Marketing'); ?>
<?php $__env->startSection('heading', 'Marketing'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div><i class="bi bi-envelope-heart text-brand fs-2"></i><div class="fs-4 fw-bold mt-2"><?php echo e($subscriberCount); ?></div><small class="text-muted">Active subscribers</small></div>
                <a href="<?php echo e(route('admin.marketing.subscribers')); ?>" class="btn btn-outline-brand btn-sm">View</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div><i class="bi bi-chat-dots text-brand fs-2"></i><div class="fs-4 fw-bold mt-2"><?php echo e($unreadMessages); ?></div><small class="text-muted">Unread messages</small></div>
                <a href="<?php echo e(route('admin.marketing.messages')); ?>" class="btn btn-outline-brand btn-sm">View</a>
            </div>
        </div>
    </div>
</div>

<div class="card p-3">
    <h6 class="fw-bold mb-3">Recent Messages</h6>
    <?php $__empty_1 = true; $__currentLoopData = $recentMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="d-flex justify-content-between py-2 border-bottom">
            <div class="small"><strong><?php echo e($m->name); ?></strong> — <?php echo e($m->subject ?: 'No subject'); ?><br><span class="text-muted"><?php echo e(\Illuminate\Support\Str::limit($m->message, 60)); ?></span></div>
            <?php if (! ($m->is_read)): ?><span class="badge bg-brand align-self-start">New</span><?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-muted small mb-0">No messages yet.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/marketing/index.blade.php ENDPATH**/ ?>