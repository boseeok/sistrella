<?php $__env->startSection('title', 'Products'); ?>
<?php $__env->startSection('heading', 'Products'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" class="row g-2 flex-grow-1 me-3">
        <div class="col-md-4"><input type="text" name="search" value="<?php echo e($filters['search'] ?? ''); ?>" class="form-control form-control-sm" placeholder="Search name or SKU"></div>
        <div class="col-md-3">
            <select name="category_id" class="form-select form-select-sm">
                <option value="">All categories</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($c->id); ?>" <?php echo e(($filters['category_id'] ?? '')==$c->id ? 'selected':''); ?>><?php echo e($c->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm">
                <option value="">Any status</option>
                <option value="active" <?php echo e(($filters['status'] ?? '')==='active'?'selected':''); ?>>Active</option>
                <option value="inactive" <?php echo e(($filters['status'] ?? '')==='inactive'?'selected':''); ?>>Inactive</option>
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-brand btn-sm w-100">Filter</button></div>
    </form>
    <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>New</a>
</div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th></th><th>Name</th><th>SKU</th><th>Category</th><th>Price</th><th>Stock</th><th>Flags</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><img src="<?php echo e($p->thumbnail); ?>" width="42" height="42" class="rounded" style="object-fit:cover"></td>
                        <td class="small fw-semibold"><?php echo e(\Illuminate\Support\Str::limit($p->name, 36)); ?></td>
                        <td class="small text-muted"><?php echo e($p->sku); ?></td>
                        <td class="small"><?php echo e($p->category->name ?? '—'); ?></td>
                        <td class="small"><?php echo e(money($p->current_price)); ?></td>
                        <td><span class="badge bg-<?php echo e($p->stock <= 0 ? 'danger' : ($p->is_low_stock ? 'warning text-dark' : 'light text-dark')); ?>"><?php echo e($p->stock); ?></span></td>
                        <td class="small">
                            <?php if($p->is_featured): ?><span class="badge bg-info">F</span><?php endif; ?>
                            <?php if($p->is_trending): ?><span class="badge bg-secondary">T</span><?php endif; ?>
                            <?php if($p->is_best_seller): ?><span class="badge bg-success">B</span><?php endif; ?>
                            <?php if($p->isOnFlashSale()): ?><span class="badge bg-danger">Sale</span><?php endif; ?>
                        </td>
                        <td><?php echo $p->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Hidden</span>'; ?></td>
                        <td class="text-end">
                            <a href="<?php echo e(route('admin.products.edit', $p)); ?>" class="btn btn-sm btn-outline-brand"><i class="bi bi-pencil"></i></a>
                            <form action="<?php echo e(route('admin.products.destroy', $p)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="9" class="text-center text-muted py-4">No products found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3"><?php echo e($products->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/products/index.blade.php ENDPATH**/ ?>