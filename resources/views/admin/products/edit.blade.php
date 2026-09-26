@extends('layouts.admin')
@section('title', 'Edit Product')
@section('heading', 'Edit Product')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-light"><i class="bi bi-chevron-left"></i> Back</a>
    <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="btn btn-sm btn-outline-brand"><i class="bi bi-eye me-1"></i>View on store</a>
</div>
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
    @include('admin.products._form')
</form>

{{-- Image actions (HTML forms can't nest, so the gallery buttons point here) --}}
@foreach($product->images as $img)
    <form id="img-primary-{{ $img->id }}" action="{{ route('admin.products.images.primary', [$product, $img]) }}" method="POST" class="d-none">@csrf @method('PATCH')</form>
    <form id="img-delete-{{ $img->id }}" action="{{ route('admin.products.images.destroy', [$product, $img]) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
@endforeach
@endsection
