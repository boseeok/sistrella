<?php $p = $product ?? null; ?>
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Details</h6>
            <div class="mb-2"><label class="form-label small">Name *</label><input type="text" name="name" value="<?php echo e(old('name', $p->name ?? '')); ?>" class="form-control" required></div>
            <div class="row g-2">
                <div class="col-md-6"><label class="form-label small">SKU</label><input type="text" name="sku" value="<?php echo e(old('sku', $p->sku ?? '')); ?>" class="form-control" placeholder="Auto-generated if blank"></div>
                <div class="col-md-6">
                    <label class="form-label small">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">— None —</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($c->id); ?>" <?php echo e(old('category_id', $p->category_id ?? '')==$c->id ? 'selected':''); ?>><?php echo e($c->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="mt-2"><label class="form-label small">Short description</label><textarea name="short_description" rows="2" class="form-control"><?php echo e(old('short_description', $p->short_description ?? '')); ?></textarea></div>
            <div class="mt-2"><label class="form-label small">Full description (HTML allowed)</label><textarea name="description" rows="6" class="form-control"><?php echo e(old('description', $p->description ?? '')); ?></textarea></div>
        </div>

        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Pricing</h6>
            <div class="row g-2">
                <div class="col-md-4"><label class="form-label small">Price *</label><input type="number" step="0.01" name="price" value="<?php echo e(old('price', $p->price ?? '')); ?>" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label small">Compare-at price</label><input type="number" step="0.01" name="compare_at_price" value="<?php echo e(old('compare_at_price', $p->compare_at_price ?? '')); ?>" class="form-control"></div>
                <div class="col-md-4"><label class="form-label small">Cost price</label><input type="number" step="0.01" name="cost_price" value="<?php echo e(old('cost_price', $p->cost_price ?? '')); ?>" class="form-control"></div>
            </div>
            <hr>
            <h6 class="fw-bold mb-2">Flash Sale</h6>
            <div class="row g-2">
                <div class="col-md-4"><label class="form-label small">Sale price</label><input type="number" step="0.01" name="flash_sale_price" value="<?php echo e(old('flash_sale_price', $p->flash_sale_price ?? '')); ?>" class="form-control"></div>
                <div class="col-md-4"><label class="form-label small">Starts</label><input type="datetime-local" name="flash_sale_starts_at" value="<?php echo e(old('flash_sale_starts_at', optional($p->flash_sale_starts_at ?? null)->format('Y-m-d\TH:i'))); ?>" class="form-control"></div>
                <div class="col-md-4"><label class="form-label small">Ends</label><input type="datetime-local" name="flash_sale_ends_at" value="<?php echo e(old('flash_sale_ends_at', optional($p->flash_sale_ends_at ?? null)->format('Y-m-d\TH:i'))); ?>" class="form-control"></div>
            </div>
        </div>

        <div class="card p-3">
            <h6 class="fw-bold mb-3">Images</h6>
            <?php if($p && $p->images->count()): ?>
                <div class="d-flex gap-2 flex-wrap mb-2">
                    <?php $__currentLoopData = $p->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img src="<?php echo e(asset('storage/'.$img->path)); ?>" width="70" height="70" class="rounded border <?php echo e($img->is_primary ? 'border-3 border-brand' : ''); ?>" style="object-fit:cover">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
            <input type="file" name="images[]" accept="image/*" multiple class="form-control">
            <small class="text-muted">Upload one or more images. First image becomes primary.</small>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Inventory</h6>
            <div class="form-check mb-2"><input type="checkbox" name="track_inventory" value="1" id="ti" class="form-check-input" <?php echo e(old('track_inventory', $p->track_inventory ?? true) ? 'checked':''); ?>><label for="ti" class="form-check-label small">Track inventory</label></div>
            <div class="mb-2"><label class="form-label small">Stock *</label><input type="number" name="stock" value="<?php echo e(old('stock', $p->stock ?? 0)); ?>" class="form-control" required></div>
            <div class="mb-2"><label class="form-label small">Low stock threshold *</label><input type="number" name="low_stock_threshold" value="<?php echo e(old('low_stock_threshold', $p->low_stock_threshold ?? 5)); ?>" class="form-control" required></div>
            <div class="mb-2"><label class="form-label small">Weight (g)</label><input type="number" step="0.01" name="weight" value="<?php echo e(old('weight', $p->weight ?? '')); ?>" class="form-control"></div>
            <div><label class="form-label small">Type *</label>
                <select name="type" class="form-select">
                    <?php $__currentLoopData = ['simple'=>'Simple','variable'=>'Variable','bundle'=>'Bundle','custom'=>'Custom']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($k); ?>" <?php echo e(old('type', $p->type ?? 'simple')===$k ? 'selected':''); ?>><?php echo e($v); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Visibility & Flags</h6>
            <?php $__currentLoopData = ['is_active'=>'Active','is_featured'=>'Featured','is_trending'=>'Trending','is_best_seller'=>'Best Seller','is_new_arrival'=>'New Arrival','is_customizable'=>'Customizable']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="form-check"><input type="checkbox" name="<?php echo e($field); ?>" value="1" id="<?php echo e($field); ?>" class="form-check-input" <?php echo e(old($field, $p->$field ?? ($field==='is_active')) ? 'checked':''); ?>><label for="<?php echo e($field); ?>" class="form-check-label small"><?php echo e($label); ?></label></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="card p-3">
            <h6 class="fw-bold mb-3">SEO</h6>
            <div class="mb-2"><label class="form-label small">Meta title</label><input type="text" name="meta_title" value="<?php echo e(old('meta_title', $p->meta_title ?? '')); ?>" class="form-control"></div>
            <div><label class="form-label small">Meta description</label><textarea name="meta_description" rows="2" class="form-control"><?php echo e(old('meta_description', $p->meta_description ?? '')); ?></textarea></div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-3">
    <button class="btn btn-brand"><i class="bi bi-check-lg me-1"></i><?php echo e($p ? 'Save Changes' : 'Create Product'); ?></button>
    <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-light">Cancel</a>
</div>
<?php /**PATH C:\Users\User\crochet-store\resources\views/admin/products/_form.blade.php ENDPATH**/ ?>