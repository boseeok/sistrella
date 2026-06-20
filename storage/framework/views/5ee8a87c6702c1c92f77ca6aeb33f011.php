<?php $__env->startSection('title', 'Banners'); ?>
<?php $__env->startSection('heading', 'Banners'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-end mb-3"><a href="<?php echo e(route('admin.banners.create')); ?>" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>New Banner</a></div>
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Image</th><th>Title</th><th>Position</th><th>Link</th><th>Order</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><img src="<?php echo e($b->image_url); ?>" width="80" height="40" class="rounded" style="object-fit:cover"></td>
                        <td class="small fw-semibold"><?php echo e($b->title ?: '—'); ?><br><span class="text-muted"><?php echo e($b->subtitle); ?></span></td>
                        <td><span class="badge bg-light text-dark"><?php echo e(ucfirst($b->position)); ?></span></td>
                        <td class="small text-muted"><?php echo e($b->link ?: '—'); ?></td>
                        <td><?php echo e($b->sort_order); ?></td>
                        <td><?php echo $b->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Off</span>'; ?></td>
                        <td class="text-end">
                            <a href="<?php echo e(route('admin.banners.edit', $b)); ?>" class="btn btn-sm btn-outline-brand"><i class="bi bi-pencil"></i></a>
                            <form action="<?php echo e(route('admin.banners.destroy', $b)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete banner?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No banners yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3"><?php echo e($banners->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/banners/index.blade.php ENDPATH**/ ?>