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
@endsection
