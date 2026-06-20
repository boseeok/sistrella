<?php $__env->startSection('title', $product->meta_title ?: $product->name); ?>
<?php $__env->startSection('meta_description', $product->meta_description ?: $product->short_description); ?>

<?php
    $waNumber = preg_replace('/\D+/', '', setting('whatsapp_number', '977-9761612457'));
    $waMsg = "Hello, I want this product:\n{$product->name}\n".route('products.show', $product->slug);
    $waLink = 'https://wa.me/'.$waNumber.'?text='.rawurlencode($waMsg);
    $gallery = $product->images->count() ? $product->images : collect();
?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('shop')); ?>">Shop</a></li>
            <?php if($product->category): ?><li class="breadcrumb-item"><a href="<?php echo e(route('categories.show', $product->category->slug)); ?>"><?php echo e($product->category->name); ?></a></li><?php endif; ?>
            <li class="breadcrumb-item active"><?php echo e(\Illuminate\Support\Str::limit($product->name, 30)); ?></li>
        </ol>
    </nav>

    <div class="row g-4 mb-5">
        
        <div class="col-md-6">
            <div class="card p-2">
                <div class="zoom-wrap">
                    <img id="mainImage" src="<?php echo e($product->thumbnail); ?>" alt="<?php echo e($product->name); ?>" class="zoom-img img-fluid rounded-3 w-100" style="aspect-ratio:1/1;object-fit:cover;background:#DCE3CE">
                    <div class="zoom-controls">
                        <button type="button" id="zoomIn" title="Zoom in"><i class="bi bi-plus-lg"></i></button>
                        <button type="button" id="zoomOut" title="Zoom out"><i class="bi bi-dash-lg"></i></button>
                        <button type="button" id="zoomReset" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></button>
                    </div>
                </div>
            </div>
            <?php if($gallery->count() > 1): ?>
                <div class="d-flex gap-2 mt-2 overflow-auto">
                    <?php $__currentLoopData = $gallery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img src="<?php echo e(asset('storage/'.$img->path)); ?>" class="thumb-img rounded-2 border" style="width:72px;height:72px;object-fit:cover;cursor:pointer" onclick="document.getElementById('mainImage').src=this.src">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="col-md-6">
            <?php if($product->category): ?><small class="text-muted text-uppercase"><?php echo e($product->category->name); ?></small><?php endif; ?>
            <h1 class="section-title h2"><?php echo e($product->name); ?></h1>

            <?php if($product->rating_count > 0): ?>
                <div class="rating mb-2">
                    <?php for($i=1;$i<=5;$i++): ?><i class="bi <?php echo e($i <= round($product->rating_avg) ? 'bi-star-fill' : 'bi-star'); ?>"></i><?php endfor; ?>
                    <span class="text-muted small"><?php echo e(number_format($product->rating_avg,1)); ?> (<?php echo e($product->rating_count); ?> reviews)</span>
                </div>
            <?php endif; ?>

            <div class="my-3">
                <span class="price fs-3"><?php echo e(money($product->current_price)); ?></span>
                <?php if($product->old_price): ?><span class="old-price ms-2 fs-5"><?php echo e(money($product->old_price)); ?></span>
                    <span class="badge badge-sale ms-2">-<?php echo e($product->discount_percent); ?>%</span><?php endif; ?>
            </div>

            <?php if($product->short_description): ?><p class="text-muted"><?php echo e($product->short_description); ?></p><?php endif; ?>

            <div class="mb-3">
                <?php if($product->in_stock): ?>
                    <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle"></i> In Stock</span>
                    <?php if($product->is_low_stock): ?><span class="badge bg-warning-subtle text-warning ms-1">Only <?php echo e($product->stock); ?> left!</span><?php endif; ?>
                <?php else: ?>
                    <span class="badge bg-danger-subtle text-danger"><i class="bi bi-x-circle"></i> Out of Stock</span>
                <?php endif; ?>
            </div>

            
            <div class="prepay-note p-3 mb-3 small">
                <i class="bi bi-info-circle text-brand me-1"></i><?php echo e(prepayment_notice()); ?>

            </div>

            <?php if($product->in_stock): ?>
                <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="js-add-to-cart"><?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">

                    <?php if($product->variants->count()): ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Options</label>
                            <select name="variant_id" class="form-select">
                                <?php $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($variant->id); ?>"><?php echo e($variant->label ?: $variant->sku); ?> — <?php echo e(money($variant->effective_price)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2 align-items-end mb-3">
                        <div style="width:110px">
                            <label class="form-label fw-semibold small">Quantity</label>
                            <input type="number" name="quantity" value="1" min="1" max="999" class="form-control">
                        </div>
                        <button class="btn btn-brand btn-lg flex-grow-1"><i class="bi bi-bag-plus me-1"></i>Add to Cart</button>
                    </div>
                </form>
            <?php endif; ?>

            <div class="d-flex gap-2 flex-wrap">
                <a href="<?php echo e($waLink); ?>" target="_blank" rel="noopener" class="btn btn-success"><i class="bi bi-whatsapp me-1"></i>Ask on WhatsApp</a>
                <?php if(auth()->guard()->check()): ?>
                    <form action="<?php echo e(route('wishlist.toggle', $product)); ?>" method="POST"><?php echo csrf_field(); ?>
                        <button class="btn btn-outline-brand"><i class="bi bi-heart me-1"></i>Wishlist</button>
                    </form>
                <?php endif; ?>
                <?php if($product->is_customizable): ?>
                    <a href="<?php echo e(route('custom.create')); ?>" class="btn btn-outline-secondary"><i class="bi bi-stars me-1"></i>Customize</a>
                <?php endif; ?>
            </div>

            <ul class="list-unstyled small text-muted mt-3 mb-0">
                <li><i class="bi bi-upc me-1"></i>SKU: <?php echo e($product->sku); ?></li>
                <li><i class="bi bi-truck me-1"></i>Cash on Delivery available</li>
                <li><i class="bi bi-hand-thumbs-up me-1"></i>100% handmade with premium yarn</li>
            </ul>
        </div>
    </div>

    
    <div class="card p-4 mb-5">
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#desc">Description</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#reviews">Reviews (<?php echo e($product->approvedReviews->count()); ?>)</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="desc">
                <?php echo $product->description ?: '<p class="text-muted">No description available.</p>'; ?>

            </div>
            <div class="tab-pane fade" id="reviews">
                <?php $__empty_1 = true; $__currentLoopData = $product->approvedReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <strong><?php echo e($review->user->name ?? 'Customer'); ?></strong>
                            <span class="rating small"><?php for($i=1;$i<=5;$i++): ?><i class="bi <?php echo e($i <= $review->rating ? 'bi-star-fill' : 'bi-star'); ?>"></i><?php endfor; ?></span>
                        </div>
                        <?php if($review->is_verified_purchase): ?><span class="badge bg-success-subtle text-success small">Verified Purchase</span><?php endif; ?>
                        <?php if($review->title): ?><div class="fw-semibold mt-1"><?php echo e($review->title); ?></div><?php endif; ?>
                        <p class="text-muted small mb-0"><?php echo e($review->body); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted">No reviews yet. Be the first to review!</p>
                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>
                    <?php if($canReview): ?>
                        <h6 class="fw-bold mt-4">Write a review</h6>
                        <form action="<?php echo e(route('products.review', $product->slug)); ?>" method="POST"><?php echo csrf_field(); ?>
                            <div class="mb-2">
                                <label class="form-label small">Rating</label>
                                <select name="rating" class="form-select form-select-sm" style="width:auto">
                                    <?php for($i=5;$i>=1;$i--): ?><option value="<?php echo e($i); ?>"><?php echo e($i); ?> star<?php echo e($i>1?'s':''); ?></option><?php endfor; ?>
                                </select>
                            </div>
                            <div class="mb-2"><input type="text" name="title" class="form-control form-control-sm" placeholder="Title (optional)"></div>
                            <div class="mb-2"><textarea name="body" rows="3" class="form-control form-control-sm" placeholder="Share your thoughts..."></textarea></div>
                            <button class="btn btn-brand btn-sm">Submit Review</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="small mt-3"><a href="<?php echo e(route('login')); ?>">Login</a> to write a review.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php if($related->isNotEmpty()): ?>
        <section class="mb-5">
            <h4 class="section-title mb-3">You may also like</h4>
            <div class="row g-3 g-md-4">
                <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-6 col-md-3"><?php echo $__env->make('partials.product-card', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .zoom-wrap{position:relative;overflow:hidden;border-radius:.75rem;}
    .zoom-img{transition:transform .15s ease;transform-origin:center;cursor:zoom-in;display:block;}
    .zoom-img.zoomed{cursor:move;}
    .zoom-controls{position:absolute;bottom:12px;right:12px;display:flex;flex-direction:column;gap:6px;}
    .zoom-controls button{width:38px;height:38px;border:none;border-radius:50%;background:rgba(255,255,255,.92);
        box-shadow:0 2px 8px rgba(0,0,0,.2);color:#333;display:flex;align-items:center;justify-content:center;font-size:1rem;}
    .zoom-controls button:hover{background:var(--brand);color:#fff;}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
    const img = document.getElementById('mainImage');
    if (!img) return;
    let scale = 1;
    const min = 1, max = 4, step = 0.5;

    function apply(){
        img.style.transform = 'scale(' + scale + ')';
        img.classList.toggle('zoomed', scale > 1);
        if (scale === 1) img.style.transformOrigin = 'center';
    }
    function reset(){ scale = 1; img.style.transformOrigin = 'center'; apply(); }

    document.getElementById('zoomIn').addEventListener('click', () => { scale = Math.min(max, scale + step); apply(); });
    document.getElementById('zoomOut').addEventListener('click', () => { scale = Math.max(min, scale - step); apply(); });
    document.getElementById('zoomReset').addEventListener('click', reset);

    // Click image to toggle zoom; mouse wheel to zoom in/out.
    img.addEventListener('click', () => { scale = scale >= max ? min : scale + step; apply(); });
    img.addEventListener('wheel', (e) => {
        e.preventDefault();
        scale = Math.min(max, Math.max(min, scale + (e.deltaY < 0 ? step : -step)));
        apply();
    }, { passive: false });

    // Pan toward the cursor while zoomed.
    img.addEventListener('mousemove', (e) => {
        if (scale <= 1) return;
        const r = img.getBoundingClientRect();
        const x = ((e.clientX - r.left) / r.width) * 100;
        const y = ((e.clientY - r.top) / r.height) * 100;
        img.style.transformOrigin = x + '% ' + y + '%';
    });

    // Reset zoom when a thumbnail is selected.
    document.querySelectorAll('.thumb-img').forEach(t => t.addEventListener('click', reset));
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/shop/products/show.blade.php ENDPATH**/ ?>