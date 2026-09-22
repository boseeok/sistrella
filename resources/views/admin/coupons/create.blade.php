@extends('layouts.admin')
@section('title', 'New Coupon')
@section('heading', 'New Coupon')
@section('content')
<form action="{{ route('admin.coupons.store') }}" method="POST">@csrf
    @include('admin.coupons._form')
</form>
@endsection
