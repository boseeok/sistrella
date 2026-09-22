@extends('layouts.admin')
@section('title', 'New Product')
@section('heading', 'New Product')

@section('content')
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">@csrf
    @include('admin.products._form')
</form>
@endsection
