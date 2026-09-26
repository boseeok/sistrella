@props(['current' => 1])
{{-- Cart → Details → Confirmation progress indicator --}}
<ol class="steps" aria-label="Checkout progress">
    @foreach(['Cart', 'Details & payment', 'Confirmation'] as $i => $label)
        @php $n = $i + 1; @endphp
        <li class="{{ $n < $current ? 'done' : ($n === $current ? 'current' : '') }}" @if($n === $current) aria-current="step" @endif>
            <span class="num">@if($n < $current)<i class="bi bi-check-lg" aria-hidden="true"></i>@else{{ $n }}@endif</span>{{ $label }}
        </li>
    @endforeach
</ol>
