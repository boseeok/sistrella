@extends('layouts.admin')
@section('title', 'Reports')
@section('heading', 'Reports')

@section('content')
<div class="row g-2 mb-4">
    <div class="col-auto"><a href="{{ route('admin.reports.sales') }}" class="btn btn-outline-brand btn-sm"><i class="bi bi-graph-up me-1"></i>Sales</a></div>
    <div class="col-auto"><a href="{{ route('admin.reports.inventory') }}" class="btn btn-outline-brand btn-sm"><i class="bi bi-box me-1"></i>Inventory</a></div>
    <div class="col-auto"><a href="{{ route('admin.reports.customers') }}" class="btn btn-outline-brand btn-sm"><i class="bi bi-people me-1"></i>Customers</a></div>
    <div class="col-auto ms-auto"><a href="{{ route('admin.reports.export', 'orders') }}" class="btn btn-success btn-sm"><i class="bi bi-download me-1"></i>Export orders CSV</a></div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">Total Sales</small><div class="fs-5 fw-bold">{{ money($widgets['total_sales']) }}</div></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">Collected</small><div class="fs-5 fw-bold">{{ money($widgets['collected']) }}</div></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">Orders</small><div class="fs-5 fw-bold">{{ $widgets['orders_total'] }}</div></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">Customers</small><div class="fs-5 fw-bold">{{ $widgets['customers'] }}</div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-6"><div class="card p-3"><h6 class="fw-bold mb-3">Daily Sales (14 days)</h6><canvas id="c1" height="120"></canvas></div></div>
    <div class="col-lg-6"><div class="card p-3"><h6 class="fw-bold mb-3">Monthly Revenue (12 months)</h6><canvas id="c2" height="120"></canvas></div></div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const daily = @json($dailySales);
const monthly = @json($monthlyRevenue);
new Chart(document.getElementById('c1'), {type:'line',data:{labels:daily.labels,datasets:[{data:daily.values,borderColor:'#0D9488',backgroundColor:'rgba(13,148,136,.1)',fill:true,tension:.35}]},options:{plugins:{legend:{display:false}}}});
new Chart(document.getElementById('c2'), {type:'bar',data:{labels:monthly.labels,datasets:[{data:monthly.values,backgroundColor:'#0D9488'}]},options:{plugins:{legend:{display:false}}}});
</script>
@endpush
