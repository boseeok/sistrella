<?php $__env->startSection('title', 'Staff'); ?>
<?php $__env->startSection('heading', 'Staff Members'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-end mb-3"><a href="<?php echo e(route('admin.staff.create')); ?>" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Staff</a></div>
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Name</th><th>Email</th><th>Roles</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><div class="d-flex align-items-center gap-2"><img src="<?php echo e($u->avatar_url); ?>" width="34" height="34" class="rounded-circle"><span class="small fw-semibold"><?php echo e($u->name); ?></span></div></td>
                        <td class="small text-muted"><?php echo e($u->email); ?></td>
                        <td class="small"><?php $__currentLoopData = $u->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="badge bg-light text-dark"><?php echo e($role->display_name); ?></span> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
                        <td><?php echo $u->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'; ?></td>
                        <td class="text-end">
                            <a href="<?php echo e(route('admin.staff.edit', $u)); ?>" class="btn btn-sm btn-outline-brand"><i class="bi bi-pencil"></i></a>
                            <?php if($u->id !== auth()->id()): ?>
                                <form action="<?php echo e(route('admin.staff.destroy', $u)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Remove this staff member?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No staff yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3"><?php echo e($staff->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/staff/index.blade.php ENDPATH**/ ?>