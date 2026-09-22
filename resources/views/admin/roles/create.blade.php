@extends('layouts.admin')
@section('title', 'New Role')
@section('heading', 'New Role')
@section('content')
<form action="{{ route('admin.roles.store') }}" method="POST">@csrf
    @include('admin.roles._form')
</form>
@endsection
