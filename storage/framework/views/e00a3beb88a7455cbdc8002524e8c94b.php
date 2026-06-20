<?php $__env->startSection('title', 'Edit Product'); ?>
<?php $__env->startSection('heading', 'Edit Product'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between mb-3">
    <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-sm btn-light"><i class="bi bi-chevron-left"></i> Back</a>
    <a href="<?php echo e(route('products.show', $product->slug)); ?>" target="_blank" class="btn btn-sm btn-outline-brand"><i class="bi bi-eye me-1"></i>View on store</a>
</div>
<form action="<?php echo e(route('admin.products.update', $product)); ?>" method="POST" enctype="multipart/form-data"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <?php echo $__env->make('admin.products._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/products/edit.blade.php ENDPATH**/ ?>