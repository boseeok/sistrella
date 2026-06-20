<?php $__env->startSection('title', 'Inventory'); ?>
<?php $__env->startSection('heading', 'Inventory'); ?>

<?php $__env->startSection('content'); ?>
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4"><label class="form-label small">Search</label><input type="text" name="search" value="<?php echo e($filters['search'] ?? ''); ?>" class="form-control form-control-sm" placeholder="Name or SKU"></div>
        <div class="col-md-3">
            <label class="form-label small">Filter</label>
            <select name="filter" class="form-select form-select-sm">
                <option value="">All tracked</option>
                <option value="low" <?php echo e(($filters['filter'] ?? '')==='low' ? 'selected':''); ?>>Low stock</option>
                <option value="out" <?php echo e(($filters['filter'] ?? '')==='out' ? 'selected':''); ?>>Out of stock</option>
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-brand btn-sm w-100">Filter</button></div>
    </form>
</div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Product</th><th>SKU</th><th>Category</th><th>Current stock</th><th>Update</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="small fw-semibold"><?php echo e(\Illuminate\Support\Str::limit($p->name, 40)); ?></td>
                        <td class="small text-muted"><?php echo e($p->sku); ?></td>
                        <td class="small"><?php echo e($p->category->name ?? '—'); ?></td>
                        <td><span class="badge bg-<?php echo e($p->stock <= 0 ? 'danger' : ($p->is_low_stock ? 'warning text-dark' : 'success')); ?>"><?php echo e($p->stock); ?></span></td>
                        <td>
                            <form action="<?php echo e(route('admin.inventory.update', $p)); ?>" method="POST" class="d-flex gap-1"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <input type="number" name="stock" value="<?php echo e($p->stock); ?>" class="form-control form-control-sm" style="width:80px">
                                <input type="number" name="low_stock_threshold" value="<?php echo e($p->low_stock_threshold); ?>" class="form-control form-control-sm" style="width:80px" title="Low stock threshold">
                                <button class="btn btn-sm btn-brand">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No products found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3"><?php echo e($products->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/inventory/index.blade.php ENDPATH**/ ?>