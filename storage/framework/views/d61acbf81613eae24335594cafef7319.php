<?php $__env->startSection('title', 'Roles'); ?>
<?php $__env->startSection('heading', 'Roles & Permissions'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-end mb-3"><a href="<?php echo e(route('admin.roles.create')); ?>" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>New Role</a></div>
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Role</th><th>Users</th><th>Permissions</th><th>Type</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><strong><?php echo e($r->display_name); ?></strong><br><small class="text-muted"><?php echo e($r->description); ?></small></td>
                        <td><?php echo e($r->users_count); ?></td>
                        <td><?php echo e($r->permissions_count); ?></td>
                        <td><?php echo $r->is_system ? '<span class="badge bg-secondary">System</span>' : '<span class="badge bg-light text-dark">Custom</span>'; ?></td>
                        <td class="text-end">
                            <a href="<?php echo e(route('admin.roles.edit', $r)); ?>" class="btn btn-sm btn-outline-brand"><i class="bi bi-pencil"></i></a>
                            <?php if (! ($r->is_system)): ?>
                                <form action="<?php echo e(route('admin.roles.destroy', $r)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete role?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/roles/index.blade.php ENDPATH**/ ?>