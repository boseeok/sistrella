@props(['items' => []])
{{-- Breadcrumb trail with schema.org markup. $items = [label => url|null, ...]; Home is added automatically --}}
@php $trail = ['Home' => route('home')] + $items; $pos = 0; @endphp
<nav aria-label="Breadcrumb">
    <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        @foreach($trail as $label => $url)
            @php $pos++; @endphp
            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" @if($loop->last) aria-current="page" @endif>
                @if($url && ! $loop->last)
                    <a href="{{ $url }}" itemprop="item"><span itemprop="name">{{ $label }}</span></a>
                @else
                    <span itemprop="name">{{ $label }}</span>
                @endif
                <meta itemprop="position" content="{{ $pos }}">
            </li>
        @endforeach
    </ol>
</nav>
