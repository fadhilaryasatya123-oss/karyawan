@php $total = max(0, (int) $s->kuota); @endphp
<div class="tally" title="{{ $s->terpakai }} dari {{ $total }} hari terpakai">
    @if($total <= 31)
        <div class="tally-boxes">
            @for($i = 1; $i <= $total; $i++)<span class="{{ $i <= $s->terpakai ? 'used' : '' }}"></span>@endfor
        </div>
    @else
        <div class="tally-bar"><span style="width: {{ min(100, $s->terpakai / $total * 100) }}%"></span></div>
    @endif
</div>
