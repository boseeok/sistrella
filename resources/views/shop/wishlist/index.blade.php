@extends('layouts.app')
@section('title', 'Wishlist')

@section('content')
<div class="container">
    <h2 class="section-title mb-4">My Wishlist</h2>
    <div class="row g-4">
        <div class="col-lg-3">@include('partials.account-nav')</div>
        <div class="col-lg-9">
            @if($items->isEmpty())
                <div class="card p-5 text-center">
                    <i class="bi bi-heart text-muted" style="font-size:3rem"></i>
                    <p class="mt-3 text-muted">Your wishlist is empty.</p>
                    <div><a href="{{ route('shop') }}" class="btn btn-brand">Browse Products</a></div>
                </div>
            @else
                <div class="row g-3 g-md-4">
                    @foreach($items as $item)
                        @continue(!$item->product)
                        @php $product = $item->product; @endphp
                        <div class="col-6 col-md-4">@include('partials.product-card')</div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
