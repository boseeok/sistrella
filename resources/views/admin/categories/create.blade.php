@extends('layouts.admin')
@section('title', 'New Category')
@section('heading', 'New Category')
@section('content')
<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">@csrf
    @include('admin.categories._form')
</form>
@endsection
