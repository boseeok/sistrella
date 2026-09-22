@extends('layouts.admin')
@section('title', 'Edit Banner')
@section('heading', 'Edit Banner')
@section('content')
<form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
    @include('admin.banners._form')
</form>
@endsection
