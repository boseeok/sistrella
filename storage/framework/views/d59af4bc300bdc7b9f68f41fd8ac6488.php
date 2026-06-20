<?php $__env->startSection('title', isset($category) ? $category->name : 'Shop'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
            <li class="breadcrumb-item active"><?php echo e($category->name ?? 'Shop'); ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        
        <div class="col-lg-3">
            <div class="card p-3 mb-3">
                <h6 class="fw-bold mb-3">Categories</h6>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-1"><a href="<?php echo e(route('shop')); ?>" class="<?php echo e(!request('category_id') && !isset($category) ? 'fw-bold text-brand' : 'text-dark'); ?>">All Products</a></li>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="mb-1">
                            <a href="<?php echo e(route('categories.show', $cat->slug)); ?>" class="<?php echo e((isset($category) && $category->id === $cat->id) ? 'fw-bold text-brand' : 'text-dark'); ?>"><?php echo e($cat->name); ?></a>
                            <?php if($cat->children->count()): ?>
                                <ul class="list-unstyled ms-3 mt-1">
                                    <?php $__currentLoopData = $cat->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><a href="<?php echo e(route('categories.show', $child->slug)); ?>" class="text-muted"><?php echo e($child->name); ?></a></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>

            <div class="card p-3">
                <h6 class="fw-bold mb-3">Filter</h6>
                <form action="<?php echo e(isset($category) ? route('categories.show', $category->slug) : route('shop')); ?>" method="GET">
                    <?php if(request('search')): ?><input type="hidden" name="search" value="<?php echo e(request('search')); ?>"><?php endif; ?>
                    <div class="mb-2">
                        <label class="form-label small">Min price</label>
                        <input type="number" name="min_price" value="<?php echo e($filters['min_price'] ?? ''); ?>" class="form-control form-control-sm" min="0">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Max price</label>
                        <input type="number" name="max_price" value="<?php echo e($filters['max_price'] ?? ''); ?>" class="form-control form-control-sm" min="0">
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="in_stock" value="1" id="inStock" class="form-check-input" <?php echo e(!empty($filters['in_stock']) ? 'checked' : ''); ?>>
                        <label for="inStock" class="form-check-label small">In stock only</label>
                    </div>
                    <button class="btn btn-brand btn-sm w-100">Apply</button>
                </form>
            </div>
        </div>

        
        <div class="col-lg-9">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="section-title mb-0"><?php echo e($category->name ?? (request('search') ? 'Results for "'.request('search').'"' : 'All Products')); ?></h4>
                    <small class="text-muted"><?php echo e($products->total()); ?> item(s)</small>
                </div>
                <form method="GET" class="d-flex align-items-center gap-2">
                    <?php $__currentLoopData = ($filters ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($k !== 'sort' && $v !== null && $k !== 'category_id'): ?><input type="hidden" name="<?php echo e($k); ?>" value="<?php echo e($v); ?>"><?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <label class="small text-muted">Sort</label>
                    <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()" style="width:auto">
                        <?php $__currentLoopData = ['latest'=>'Newest','price_asc'=>'Price: Low to High','price_desc'=>'Price: High to Low','popular'=>'Most Popular','rating'=>'Top Rated','name'=>'Name']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val); ?>" <?php echo e(($filters['sort'] ?? 'latest') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </form>
            </div>

            <?php if($products->count()): ?>
                <div class="row g-3 g-md-4">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-6 col-md-4"><?php echo $__env->make('partials.product-card', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="mt-4"><?php echo e($products->links()); ?></div>
            <?php else: ?>
                <div class="card p-5 text-center">
                    <i class="bi bi-search text-muted" style="font-size:2.5rem"></i>
                    <p class="mt-3 mb-0 text-muted">No products found. Try adjusting your filters.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/shop/products/index.blade.php ENDPATH**/ ?>