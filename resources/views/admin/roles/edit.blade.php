@extends('layouts.admin')
@section('title', 'Edit Role')
@section('heading', 'Edit Role')
@section('content')
<form action="{{ route('admin.roles.update', $role) }}" method="POST">@csrf @method('PUT')
    @include('admin.roles._form')
</form>
@endsection
