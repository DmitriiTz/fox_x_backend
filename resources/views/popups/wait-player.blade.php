
<div class="iziModal-wrap" style="height: auto;" data-game-id="{{ $game->id }}">
    <div class="iziModal-content" style="padding: 0;">
        <div class="modal__top">
            <h6 class="modal__title">Coinflip #{{ $game->id }}</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Round hash: {{ $hashWinner }}</p>
                <p class="modal-hash__text hide">Round secret <a href="http://sha224.net/?val={{ $winnerTicket }}" target="blank" class="conclusion__balance-hash">{{ $winnerTicket }}</a></p>
            </div>
        </div>

        <div class="wait-palyer">
            @if($game->participants->first()->color)
                <div class="wait-palyer__left">
                    <div class="wait-palyer__box">
                        <i class="ic-coin-orange"></i>
                        <div class="wait-palyer__avatar">
                            <img src="{{ asset($game->participants->first()->account->image) }}" alt="avatar">
                        </div>
                    </div>
                    <p class="wait-palyer__nick">{{ $game->participants->first()->account->name }}</p>
                    <p class="wait-palyer__rate" data-cash="{{ $game->participants->first()->cash }}">{{ $game->participants->first()->cash }} <br><span>COINS</span></p>
                    <p class="wait-palyer__tickets">0-50</p>
                    <p class="wait-palyer__text hide">Победил</p>
                </div>
            @else
                @if($game->participants->count() == 1)
                    <div class="wait-palyer__left">
                        <div class="wait-palyer__loader">
                            <i class="ic-load-gray"></i>
                        </div>
                        <p class="wait-palyer__loader-hint">Ожидание игрока</p>
                    </div>
                @else
                    <div class="wait-palyer__left">
                        <div class="wait-palyer__box">
                            <i class="ic-coin-orange"></i>
                            <div class="wait-palyer__avatar">
                                <img src="{{ asset($game->participants->sortByDesc('created_at')->first()->account->image) }}" alt="avatar">
                            </div>
                        </div>
                        <p class="wait-palyer__nick">{{ $game->participants->sortByDesc('created_at')->first()->account->name }}</p>
                        <p class="wait-palyer__rate" data-cash="{{ $game->participants->sortByDesc('created_at')->first()->cash }}">
                            {{ $game->participants->sortByDesc('created_at')->first()->cash }} <br><span>COINS</span></p>
                        <p class="wait-palyer__tickets">0-50</p>
                        <p class="wait-palyer__text hide">Победил</p>
                    </div>
                @endif
            @endif
            <div class="coin-create__center coin-create__center--one">
                <span class="coin-create__vs">vs</span>
                <p class="wait-palyer__bet">Bank</p>
                <p class="wait-palyer__bet-done">{{ $game->participants->sum('cash') }}</p>
                <div class="wait-palyer__timer">
                    <i class="ic-timer"></i>
                    <p class="wait-palyer__timer-text">0:<span
                                id="coinModalTimer2"
                                class="wait-palyer__timer-value">{{ strtotime($game->end_game_at) - strtotime(now()) > 0 ? strtotime($game->end_game_at) - strtotime(now()) : 10 }}</span></p>
                </div>
            </div>
            @if(!$game->participants->first()->color)
                <div class="wait-palyer__right">
                    <div class="wait-palyer__box">
                        <i class="ic-coin-blue"></i>
                        <div class="wait-palyer__avatar">
                            <img src="{{ asset($game->participants->first()->account->image) }}" alt="avatar">
                        </div>
                    </div>
                    <p class="wait-palyer__nick">{{ $game->participants->first()->account->name }}</p>
                    <p class="wait-palyer__rate" data-cash="{{ $game->participants->first()->cash }}">{{ $game->participants->first()->cash }} <br><span>COINS</span></p>
                    <p class="wait-palyer__tickets">50-100</p>
                    <p class="wait-palyer__text hide">Победил</p>
                </div>
            @else
                @if($game->participants->count() == 1)
                <div class="wait-palyer__right">
                    <div class="wait-palyer__loader">
                        <i class="ic-load-gray"></i>
                    </div>
                    <p class="wait-palyer__loader-hint">Ожидание игрока</p>
                </div>
                @else
                    <div class="wait-palyer__right">
                        <div class="wait-palyer__box">
                            <i class="ic-coin-blue"></i>
                            <div class="wait-palyer__avatar">
                                <img src="{{ asset($game->participants->sortByDesc('created_at')->first()->account->image) }}" alt="avatar">
                            </div>
                        </div>
                        <p class="wait-palyer__nick">{{ $game->participants->sortByDesc('created_at')->first()->account->name }}</p>
                        <p class="wait-palyer__rate" data-cash="{{ $game->participants->sortByDesc('created_at')->first()->cash }}">
                            {{ $game->participants->sortByDesc('created_at')->first()->cash }} <br><span>COINS</span></p>
                        <p class="wait-palyer__tickets">50-100</p>
                        <p class="wait-palyer__text hide">Победил</p>
                    </div>
                @endif
            @endif
            <div class="wait-palyer__roulette-circle hide"></div>
        </div>
        @if($game->participants->count() < 2)
        <div class="wait-palyer__spinner">
            <p class="wait-palyer__spinner-text">Ожидание игрока</p>
            <i>...</i>
        </div>
        @else
            <div class="wait-palyer__spinner">
                <p class="wait-palyer__spinner-text">Игра началась</p>
                <i class="ic-load-green wait-palyer__spinner-icon"></i>
            </div>
        @endif
    </div>
</div>

<script>

    @if($game->end_game_at > now())
        MainFunction.startTimerCoinflip('{{ $game->id }}', {{ strtotime($game->end_game_at) - strtotime(now()) -10 }},
        '{{ $game->winner->name }}', '{{ $winnerApplication->color }}', '{{ $winnerApplication->cash }}');
    @else
        @if($game->winner_account_id)
            var popup = $('body').find('.iziModal-wrap[data-game-id="{{ $game->id }}"]');
            popup.find('.wait-palyer__timer-value').text('00');
            popup.find('.coin-create__center').replaceWith($('.roulette').clone().removeClass('hide'));
            MainFunction.showWinnerCoinflip(popup, '{{ $game->winner->name }}', '{{ $winnerApplication->color }}', '{{ $winnerApplication->cash }}');
        @endif
    @endif

</script>