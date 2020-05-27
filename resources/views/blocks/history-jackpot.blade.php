@php
    $arrClasses = ['blue', 'green', 'yellow'];
@endphp

@php
    $rand = rand(0, 2);
@endphp
@if($gameIteration->winner)
    <li class="jackpot-history__item history-item history-item--" style="border-left:2px solid  {{ $gameIteration->winner->color }}">
        <div class="history-item__left">
            <img src="{{ asset($gameIteration->winner->image) }}" alt="avatar" class="history-item__avatar">
            <div class="history-item__wrap">
                <p class="history-item__name">{{ $gameIteration->winner->name }}</p>
            </div>
        </div>
        <div class="history-item__right">
            <p class="history-item__value">{{ $gameIteration->participants->sum('cash') }} <span>COINS</span></p>
            <p class="history-item__range"><i class="ic-game-history"></i>{{ $gameIteration->winner->range }}</p>
        </div>
    </li>
@endif