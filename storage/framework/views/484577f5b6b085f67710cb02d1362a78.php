<?php $__env->startSection('title', setting('store_name', 'Crochet Store').' — '.setting('store_tagline', 'Handmade Crochet')); ?>

<?php
    $section = function ($title, $items, $link = null) {
        return compact('title', 'items', 'link');
    };
?>

<?php $__env->startSection('content'); ?>
<div class="container">

    
    <?php $hero = ($banners ?? collect())->first(); $heroImg = $hero?->image_url ?? asset('images/hero-crochet.jpg'); ?>
    <div class="hero hero-bg p-4 p-md-5 mb-5" style="--hero-img:url('<?php echo e($heroImg); ?>')">
        <div class="row">
            <div class="col-lg-7 col-md-9">
                <span class="badge bg-brand mb-2">Handmade in Nepal</span>
                <h1 class="section-title display-5 mb-3"><?php echo e($hero->title ?? 'Handmade Crochet, Made with Love'); ?></h1>
                <p class="lead"><?php echo e($hero->subtitle ?? 'Discover unique amigurumi, cozy wearables and adorable home decor — each piece lovingly crafted by hand.'); ?></p>
                <div class="d-flex gap-2 flex-wrap mt-3">
                    <a href="<?php echo e($hero->link ?? route('shop')); ?>" class="btn btn-brand btn-lg"><?php echo e($hero->button_text ?? 'Shop Now'); ?> <i class="bi bi-arrow-right ms-1"></i></a>
                    <a href="<?php echo e(route('custom.create')); ?>" class="btn btn-outline-brand btn-lg">Custom Order</a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="prepay-note p-3 mb-5 d-flex align-items-center gap-2">
        <i class="bi bi-shield-check fs-4 text-brand"></i>
        <div class="small mb-0"><strong>Easy ordering:</strong> <?php echo e(prepayment_notice()); ?> Smaller orders are full Cash on Delivery.</div>
    </div>

    
    <?php if(($featuredCategories ?? collect())->isNotEmpty()): ?>
    <section class="mb-5">
        <h3 class="section-title mb-4">Shop by Category</h3>
        <div class="row g-3">
            <?php $__currentLoopData = $featuredCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="<?php echo e(route('categories.show', $cat->slug)); ?>" class="card text-center p-3 h-100 text-decoration-none">
                        <div class="mb-2"><i class="bi <?php echo e($cat->icon ?: 'bi-flower1'); ?> text-brand" style="font-size:2rem"></i></div>
                        <span class="fw-semibold text-dark small"><?php echo e($cat->name); ?></span>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
    <?php endif; ?>

    
    <?php if(($flashSale ?? collect())->isNotEmpty()): ?>
        <section class="mb-5 flash-sale">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h3 class="section-title mb-0"><i class="bi bi-lightning-charge-fill text-danger"></i> Flash Sale</h3>
                <div class="d-flex align-items-center gap-2">
                    <?php if($flashSaleEndsAt): ?>
                        <span class="badge bg-accent text-dark"><i class="bi bi-clock me-1"></i>Ends <?php echo e(\Illuminate\Support\Carbon::parse($flashSaleEndsAt)->diffForHumans()); ?></span>
                    <?php endif; ?>
                    <button class="btn btn-sm btn-outline-brand flash-prev" type="button" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
                    <button class="btn btn-sm btn-outline-brand flash-next" type="button" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
                    <a href="<?php echo e(route('shop')); ?>" class="small ms-1">View all</a>
                </div>
            </div>
            <div class="flash-track d-flex gap-3 gap-md-4" id="flashTrack">
                <?php $__currentLoopData = $flashSale; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flash-slide"><?php echo $__env->make('partials.product-card', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>

    <?php
        $grids = [
            ['Featured', $featured ?? collect(), false],
            ['Trending Now', $trending ?? collect(), false],
            ['Best Sellers', $bestSellers ?? collect(), false],
            ['New Arrivals', $newArrivals ?? collect(), false],
            ['Recently Viewed', $recentlyViewed ?? collect(), false],
        ];
    ?>

    <?php $__currentLoopData = $grids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $items, $isFlash]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($items->isNotEmpty()): ?>
            <section class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="section-title mb-0"><?php echo e($label); ?></h3>
                    <a href="<?php echo e(route('shop')); ?>" class="small">View all <i class="bi bi-chevron-right"></i></a>
                </div>
                <div class="row g-3 g-md-4">
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-6 col-md-4 col-lg-3"><?php echo $__env->make('partials.product-card', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <section class="card bg-brand text-white p-4 p-md-5 my-5 text-center">
        <h3 class="section-title text-white">Have something special in mind?</h3>
        <p class="mb-3 opacity-75">We craft custom crochet pieces just for you — share your idea and we'll quote you.</p>
        <div><a href="<?php echo e(route('custom.create')); ?>" class="btn btn-light text-brand fw-semibold">Request a Custom Order</a></div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Full-width hero with the product photo as background + readable overlay */
    .hero-bg{position:relative;min-height:380px;display:flex;align-items:center;
        background-image:linear-gradient(90deg,rgba(246,243,237,.95) 0%,rgba(246,243,237,.80) 42%,rgba(246,243,237,.30) 100%),var(--hero-img);
        background-size:cover;background-position:center right;}
    .hero-bg .lead{color:#4a513f;}
    @media(max-width:767.98px){
        .hero-bg{background-image:linear-gradient(rgba(246,243,237,.92),rgba(246,243,237,.86)),var(--hero-img);}
    }
    .flash-track{display:flex;overflow-x:auto;padding:.25rem .25rem .85rem;
        -ms-overflow-style:none;scrollbar-width:none;}
    .flash-track::-webkit-scrollbar{display:none;}
    .flash-slide{flex:0 0 auto;width:46%;max-width:240px;}
    @media(min-width:576px){.flash-slide{width:240px;}}
    .flash-sale .product-card{height:100%;}
    .flash-prev,.flash-next{line-height:1;}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
    var track=document.getElementById('flashTrack');
    if(!track)return;
    var slides=Array.prototype.slice.call(track.children);
    if(!slides.length)return;

    // Clone the slide set once so the rotation can loop seamlessly.
    slides.forEach(function(s){ track.appendChild(s.cloneNode(true)); });

    var gap=parseInt(getComputedStyle(track).columnGap||getComputedStyle(track).gap)||16;
    function halfWidth(){ // width of the ORIGINAL (un-cloned) set
        var w=0; for(var i=0;i<slides.length;i++){ w+=slides[i].offsetWidth+gap; } return w;
    }
    var pos=0, paused=false, raf=null;
    var SPEED=0.55; // px per frame ≈ 33px/s — gentle continuous rotation

    function frame(){
        if(!paused){
            pos+=SPEED;
            var hw=halfWidth();
            if(pos>=hw){ pos-=hw; }      // seamless wrap (cloned content is identical)
            track.scrollLeft=pos;
        }
        raf=requestAnimationFrame(frame);
    }
    function nudge(dir){
        var card=(track.firstElementChild?track.firstElementChild.offsetWidth:240)+gap;
        var hw=halfWidth();
        pos+=dir*card;
        if(pos<0)pos+=hw; if(pos>=hw)pos-=hw;
        track.scrollLeft=pos;
    }
    var btnNext=document.querySelector('.flash-sale .flash-next');
    var btnPrev=document.querySelector('.flash-sale .flash-prev');
    if(btnNext)btnNext.addEventListener('click',function(){nudge(1);});
    if(btnPrev)btnPrev.addEventListener('click',function(){nudge(-1);});

    // Pause while interacting so it's easy to read / click.
    track.addEventListener('mouseenter',function(){paused=true;});
    track.addEventListener('mouseleave',function(){paused=false;});
    track.addEventListener('touchstart',function(){paused=true;},{passive:true});
    track.addEventListener('touchend',function(){paused=false;},{passive:true});

    raf=requestAnimationFrame(frame);
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\crochet-store\resources\views/shop/home.blade.php ENDPATH**/ ?>