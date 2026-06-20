<?php $__env->startSection('title', 'Categories'); ?>
<?php $__env->startSection('heading', 'Categories'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-end mb-3"><a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>New Category</a></div>
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Name</th><th>Parent</th><th>Products</th><th>Featured</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><i class="bi <?php echo e($c->icon ?: 'bi-tag'); ?> me-1 text-brand"></i><?php echo e($c->name); ?></td>
                        <td class="small text-muted"><?php echo e($c->parent->name ?? '—'); ?></td>
                        <td><?php echo e($c->products_count); ?></td>
                        <td><?php echo $c->is_featured ? '<i class="bi bi-star-fill text-warning"></i>' : '—'; ?></td>
                        <td><?php echo $c->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Hidden</span>'; ?></td>
                        <td class="text-end">
                            <a href="<?php echo e(route('admin.categories.edit', $c)); ?>" class="btn btn-sm btn-outline-brand"><i class="bi bi-pencil"></i></a>
                            <form action="<?php echo e(route('admin.categories.destroy', $c)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">No categories yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3"><?php echo e($categories->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>