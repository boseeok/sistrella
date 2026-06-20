<?php $__env->startSection('title', 'Edit Banner'); ?>
<?php $__env->startSection('heading', 'Edit Banner'); ?>
<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('admin.banners.update', $banner)); ?>" method="POST" enctype="multipart/form-data"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <?php echo $__env->make('admin.banners._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/banners/edit.blade.php ENDPATH**/ ?>