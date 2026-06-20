@extends('layouts.admin')
@section('title', 'Edit Category')
@section('heading', 'Edit Category')
@section('content')
<form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
    @include('admin.categories._form')
</form>
@endsection
