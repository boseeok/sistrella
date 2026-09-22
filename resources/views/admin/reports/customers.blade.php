@extends('layouts.admin')
@section('title', 'Customer Report')
@section('heading', 'Customer Report')

@section('content')
<div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.reports.export', 'customers') }}" class="btn btn-success btn-sm"><i class="bi bi-download me-1"></i>Export CSV</a></div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">Top Customers by Value</h6>
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light"><tr><th>Customer</th><th>Orders</th><th>Lifetime value</th></tr></thead>
                <tbody>
                    @forelse($topCustomers as $u)
                        <tr>
                            <td><a href="{{ route('admin.customers.show', $u) }}">{{ $u->name }}</a><br><small class="text-muted">{{ $u->email }}</small></td>
                            <td>{{ $u->orders_count }}</td>
                            <td class="fw-semibold">{{ money($u->orders_sum_grand_total ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No customers yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card p-3"><h6 class="fw-bold mb-3">Customer Growth</h6><canvas id="g" height="160"></canvas></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const g = @json($growth);
new Chart(document.getElementById('g'), {type:'bar',data:{labels:g.labels,datasets:[{data:g.values,backgroundColor:'#0D9488'}]},options:{plugins:{legend:{display:false}}}});
</script>
@endpush
