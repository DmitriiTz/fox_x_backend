

    <li class="jackpot-history__item history-item history-item--" style="border-left: 2px solid {{$bet->color}}">
        <div class="history-item__left">
            <img src="{{ asset($bet->account->image) }}" alt="avatar" class="history-item__avatar">
            <div class="history-item__wrap">
                <p class="history-item__name">{{ $bet->account->name }}</p>
            </div>
        </div>
        <div class="history-item__right">
            <p class="history-item__value">{{ $bet->cash }} <span>COINS</span></p>
            <p class="history-item__range"><i class="ic-game-history"></i>{{$bet->min_cash_number}} - {{$bet->max_cash_number}}</p>
        </div>
    </li>
