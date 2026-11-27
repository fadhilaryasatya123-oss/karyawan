@php
    $total = max(0, (int) $s->kuota);
    $used = max(0, (int) $s->terpakai);
    $percent = $total > 0 ? min(100, $used / $total * 100) : 0;
@endphp
<div class="tally" role="img" aria-label="{{ $used }} dari {{ $total }} hari terpakai atau dicadangkan">
    <div class="tally-bar"><span style="width: {{ $percent }}%"></span></div>
</div>
