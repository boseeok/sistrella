<?php $__env->startSection('title', 'Order '.$order->order_number); ?>
<?php $__env->startSection('heading', 'Order '.$order->order_number); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-sm btn-light"><i class="bi bi-chevron-left"></i> Back</a>
    <div class="d-flex gap-2">
        <span class="badge bg-<?php echo e($order->status_color); ?> fs-6 align-self-center"><?php echo e($order->status_label); ?></span>
        <a href="<?php echo e(route('admin.orders.invoice', $order->order_number)); ?>" target="_blank" class="btn btn-sm btn-outline-brand"><i class="bi bi-file-earmark-pdf me-1"></i>Invoice</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        
        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Items</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light"><tr><th>Product</th><th>SKU</th><th>Price</th><th>Qty</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($item->name); ?><?php if($item->options): ?><br><small class="text-muted"><?php echo e(collect($item->options)->map(fn($v,$k)=>is_string($k)?"$k: $v":$v)->join(', ')); ?></small><?php endif; ?></td>
                                <td class="small"><?php echo e($item->sku); ?></td>
                                <td><?php echo e(money($item->unit_price)); ?></td>
                                <td><?php echo e($item->quantity); ?></td>
                                <td class="text-end"><?php echo e(money($item->line_total)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 ms-auto">
                    <div class="d-flex justify-content-between small"><span class="text-muted">Subtotal</span><span><?php echo e(money($order->subtotal)); ?></span></div>
                    <?php if($order->discount_total > 0): ?><div class="d-flex justify-content-between small text-success"><span>Discount <?php if($order->coupon_code): ?>(<?php echo e($order->coupon_code); ?>)<?php endif; ?></span><span>−<?php echo e(money($order->discount_total)); ?></span></div><?php endif; ?>
                    <?php if($order->tax_total > 0): ?><div class="d-flex justify-content-between small"><span class="text-muted">Tax</span><span><?php echo e(money($order->tax_total)); ?></span></div><?php endif; ?>
                    <div class="d-flex justify-content-between small"><span class="text-muted">Shipping</span><span><?php echo e(money($order->shipping_total)); ?></span></div>
                    <div class="d-flex justify-content-between fw-bold"><span>Total</span><span><?php echo e(money($order->grand_total)); ?></span></div>
                    <?php if($order->requires_prepayment): ?>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between small"><span>Advance</span><span><?php echo e(money($order->advance_amount)); ?></span></div>
                        <div class="d-flex justify-content-between small"><span>COD balance</span><span><?php echo e(money($order->cod_balance)); ?></span></div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between small text-success"><span>Paid</span><span><?php echo e(money($order->amount_paid)); ?></span></div>
                    <div class="d-flex justify-content-between small fw-semibold"><span>Balance</span><span><?php echo e(money($order->remaining_balance)); ?></span></div>
                </div>
            </div>
        </div>

        
        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Payments</h6>
            <?php $__empty_1 = true; $__currentLoopData = $order->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div class="small">
                        <strong><?php echo e(money($p->amount)); ?></strong> · <?php echo e(ucfirst($p->kind)); ?> · <?php echo e(str_replace('_',' ',$p->method)); ?>

                        <?php if($p->reference): ?><br><span class="text-muted">Ref: <?php echo e($p->reference); ?></span><?php endif; ?>
                        <?php if($p->proof_url): ?><br><a href="<?php echo e($p->proof_url); ?>" target="_blank">View proof</a><?php endif; ?>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-<?php echo e($p->status==='verified'?'success':($p->status==='rejected'?'danger':'warning text-dark')); ?>"><?php echo e($p->status); ?></span>
                        <?php if($p->status === 'submitted'): ?>
                            <div class="mt-1 d-flex gap-1">
                                <form action="<?php echo e(route('admin.payments.verify', $p)); ?>" method="POST"><?php echo csrf_field(); ?><button class="btn btn-success btn-sm">Verify</button></form>
                                <form action="<?php echo e(route('admin.payments.reject', $p)); ?>" method="POST"><?php echo csrf_field(); ?><button class="btn btn-outline-danger btn-sm">Reject</button></form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted small mb-0">No payments recorded.</p>
            <?php endif; ?>

            
            <details class="mt-3">
                <summary class="small text-brand" style="cursor:pointer">+ Record a payment</summary>
                <form action="<?php echo e(route('admin.payments.record', $order->order_number)); ?>" method="POST" class="row g-2 mt-1"><?php echo csrf_field(); ?>
                    <div class="col-md-3"><input type="number" step="0.01" name="amount" class="form-control form-control-sm" placeholder="Amount" required></div>
                    <div class="col-md-3"><select name="kind" class="form-select form-select-sm"><option value="advance">Advance</option><option value="balance">Balance</option><option value="full">Full</option></select></div>
                    <div class="col-md-3"><select name="method" class="form-select form-select-sm"><option value="cash">Cash</option><option value="bank_transfer">Bank</option><option value="esewa">eSewa</option><option value="khalti">Khalti</option><option value="other">Other</option></select></div>
                    <div class="col-md-3"><button class="btn btn-brand btn-sm w-100">Record</button></div>
                </form>
            </details>
        </div>

        
        <div class="card p-3">
            <h6 class="fw-bold mb-3">History</h6>
            <?php $__currentLoopData = $order->statusHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex gap-2 mb-2 small">
                    <i class="bi bi-circle-fill text-brand mt-1" style="font-size:.5rem"></i>
                    <div>
                        <strong><?php echo e(\App\Models\Order::STATUSES[$h->to_status] ?? ucfirst($h->to_status)); ?></strong>
                        <span class="text-muted">— <?php echo e($h->created_at->format('M d, H:i')); ?> <?php if($h->changedBy): ?>by <?php echo e($h->changedBy->name); ?><?php endif; ?></span>
                        <?php if($h->note): ?><div class="text-muted"><?php echo e($h->note); ?></div><?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="col-lg-4">
        
        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Update Status</h6>
            <form action="<?php echo e(route('admin.orders.status', $order->order_number)); ?>" method="POST"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <select name="status" class="form-select form-select-sm mb-2">
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e($order->status === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <input type="text" name="note" class="form-control form-control-sm mb-2" placeholder="Note (optional)">
                <button class="btn btn-brand btn-sm w-100">Update</button>
            </form>
        </div>

        
        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-2">Customer</h6>
            <p class="small mb-1"><?php echo e($order->customer_name); ?></p>
            <p class="small mb-1 text-muted"><?php echo e($order->customer_phone); ?></p>
            <?php if($order->customer_email): ?><p class="small mb-1 text-muted"><?php echo e($order->customer_email); ?></p><?php endif; ?>
            <?php if($order->user): ?><a href="<?php echo e(route('admin.customers.show', $order->user)); ?>" class="small">View customer →</a><?php endif; ?>
        </div>

        
        <?php if($order->shipping_address): ?>
            <div class="card p-3 mb-3">
                <h6 class="fw-bold mb-2">Shipping Address</h6>
                <?php $a = $order->shipping_address; ?>
                <p class="small text-muted mb-0"><?php echo e(collect([$a['line1']??null,$a['line2']??null,$a['city']??null,$a['district']??null,$a['province']??null,$a['postal_code']??null])->filter()->join(', ')); ?></p>
            </div>
        <?php endif; ?>

        
        <div class="card p-3">
            <h6 class="fw-bold mb-2">Internal</h6>
            <form action="<?php echo e(route('admin.orders.notes', $order->order_number)); ?>" method="POST"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <label class="form-label small">Tracking number</label>
                <input type="text" name="tracking_number" value="<?php echo e($order->tracking_number); ?>" class="form-control form-control-sm mb-2">
                <label class="form-label small">Admin notes</label>
                <textarea name="admin_notes" rows="3" class="form-control form-control-sm mb-2"><?php echo e($order->admin_notes); ?></textarea>
                <button class="btn btn-outline-brand btn-sm w-100">Save</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>