@extends('layouts.admin')
@section('title', 'Edit Collection')
@section('heading', 'Edit: '.$collection->name)

@section('content')
<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.collections.index') }}" class="btn btn-sm btn-light"><i class="bi bi-chevron-left"></i> Back</a>
    <a href="{{ route('collections.show', $collection->slug) }}" target="_blank" class="btn btn-sm btn-outline-brand"><i class="bi bi-eye me-1"></i>View on store</a>
</div>
<form action="{{ route('admin.collections.update', $collection) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
    @include('admin.collections._form')
</form>
@endsection
