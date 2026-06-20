
<div class="card product-card">
    <div class="position-relative">
        <a href="<?php echo e(route('products.show', $product->slug)); ?>">
            <img src="<?php echo e($product->thumbnail); ?>" alt="<?php echo e($product->name); ?>" class="pimg" loading="lazy">
        </a>
        <?php if($product->discount_percent > 0): ?>
            <span class="badge badge-sale position-absolute top-0 start-0 m-2">-<?php echo e($product->discount_percent); ?>%</span>
        <?php endif; ?>
        <?php if($product->isOnFlashSale()): ?>
            <span class="badge bg-danger position-absolute top-0 end-0 m-2"><i class="bi bi-lightning-fill"></i> Flash</span>
        <?php endif; ?>
        <?php if (! ($product->in_stock)): ?>
            <span class="badge bg-secondary position-absolute bottom-0 start-0 m-2">Out of stock</span>
        <?php endif; ?>
    </div>
    <div class="card-body d-flex flex-column p-3">
        <?php if($product->category): ?>
            <small class="text-muted text-uppercase" style="font-size:.7rem;letter-spacing:.5px"><?php echo e($product->category->name); ?></small>
        <?php endif; ?>
        <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="text-dark fw-semibold mb-1 text-decoration-none" style="min-height:2.6em"><?php echo e(\Illuminate\Support\Str::limit($product->name, 48)); ?></a>

        <?php if($product->rating_count > 0): ?>
            <div class="rating small mb-1">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="bi <?php echo e($i <= round($product->rating_avg) ? 'bi-star-fill' : 'bi-star'); ?>"></i>
                <?php endfor; ?>
                <span class="text-muted">(<?php echo e($product->rating_count); ?>)</span>
            </div>
        <?php endif; ?>

        <div class="mt-auto">
            <div class="mb-2">
                <span class="price"><?php echo e(money($product->current_price)); ?></span>
                <?php if($product->old_price): ?><span class="old-price ms-1"><?php echo e(money($product->old_price)); ?></span><?php endif; ?>
            </div>
            <div class="d-flex gap-2">
                <?php if($product->in_stock): ?>
                    <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="js-add-to-cart flex-grow-1"><?php echo csrf_field(); ?>
                        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                        <button class="btn btn-brand btn-sm w-100"><i class="bi bi-bag-plus me-1"></i>Add</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="btn btn-outline-secondary btn-sm flex-grow-1">View</a>
                <?php endif; ?>
                <?php if(auth()->guard()->check()): ?>
                    <form action="<?php echo e(route('wishlist.toggle', $product)); ?>" method="POST"><?php echo csrf_field(); ?>
                        <button class="btn btn-outline-brand btn-sm" title="Wishlist"><i class="bi bi-heart"></i></button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\User\crochet-store\resources\views/partials/product-card.blade.php ENDPATH**/ ?>