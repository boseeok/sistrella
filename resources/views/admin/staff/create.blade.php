@extends('layouts.admin')
@section('title', 'Add Staff')
@section('heading', 'Add Staff')
@section('content')
<form action="{{ route('admin.staff.store') }}" method="POST">@csrf
    @include('admin.staff._form')
</form>
@endsection
