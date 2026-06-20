@extends('layouts.admin')
@section('title', 'New Banner')
@section('heading', 'New Banner')
@section('content')
<form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">@csrf
    @include('admin.banners._form')
</form>
@endsection
