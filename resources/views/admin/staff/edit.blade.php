@extends('layouts.admin')
@section('title', 'Edit Staff')
@section('heading', 'Edit Staff')
@section('content')
<form action="{{ route('admin.staff.update', $staff) }}" method="POST">@csrf @method('PUT')
    @include('admin.staff._form')
</form>
@endsection
