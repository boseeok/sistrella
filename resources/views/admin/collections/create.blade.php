@extends('layouts.admin')
@section('title', 'New Collection')
@section('heading', 'New Occasion / Collection')

@section('content')
<form action="{{ route('admin.collections.store') }}" method="POST" enctype="multipart/form-data">@csrf
    @include('admin.collections._form')
</form>
@endsection
