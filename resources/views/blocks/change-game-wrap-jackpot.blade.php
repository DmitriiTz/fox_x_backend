<main class="main">
    <div class="main__left">

        <!-- Rooms block -->
        <div class="rooms">
            <p class="rooms__title">Выбор комнаты:</p>
            <ul class="rooms__wrap">
                <li class="rooms__link @if($gameTypeId == 1) rooms__link--active @endif" data-game-id="1" onclick="switchHomeLayout(1)">
                    CLASSIC
                </li>
                <li class="rooms__link @if($gameTypeId == 2) rooms__link--active @endif" data-game-id="2" onclick="switchHomeLayout(2)">
                    SMALL
                </li>
            </ul>
            <div id="roomClassic" class="rooms__box rooms__box--@if($gameTypeId == 1)active @endif">
                <p class="rooms__text">Минимальная ставка от 300 coins</p>
            </div>
            <div id="roomSmall" class="rooms__box rooms__box--@if($gameTypeId == 2)active @endif">
                <p class="rooms__text">Максимальная ставка до 300 coins</p>
            </div>
        </div>

        <!-- Bank block -->
        <div class="bank">
            <div class="bank__info">
                <p class="bank__title">Банк:</p>
                <p class="bank__players">@if($game) {{ count($game->participants) }} @else 0 @endif игроков</p>
            </div>
            <div class="bank__body">
                <div class="bank__circle">
                    <div class="bank__circle-inside">

                        <p class="bank__value">@if($game) {{ $game->participants->sum('cash') }} @else 0 @endif
                            <span>COINS</span>
                        </p>
                    </div>
                </div>
                @if(auth()->user() && auth()->user()->id != 4)
                    <a href="javascript:void(0);" onclick="$('#betModal').iziModal('open');" class="button button--gradient bank__button">СДЕЛАТЬ СТАВКУ</a>
                @else
                    <a class="button button--gradient bank__button" data-izimodal-open="#auth" data-izimodal-transitionin="fadeInDown">СДЕЛАТЬ
                        СТАВКУ</a>
                @endif
            </div>
        </div>

    @include('blocks.balance')

    <!-- Help block -->
        <div class="help">
            <p class="help__title">Помощь</p>
            <a class="help__link" data-izimodal-open="#helpModal" data-izimodal-transitionin="fadeInDown">
                <i class="ic-rules"></i>
                Правила игры
            </a>
        </div>

    <!-- @include('blocks.notification') -->

    @include('blocks.copyright')

    <!-- @include('blocks.application') -->

    </div>
    <div class="main__center main__center--full">

        <!-- Rooms block -->
        <div class="rooms mobile-show">
            <p class="rooms__title">Выбор комнаты:</p>
            <ul class="rooms__wrap">
                <li class="rooms__link @if($gameTypeId == 1) rooms__link--active @endif" data-game-id="1" onclick="switchHomeLayout(1)">CLASSIC</li>
                <li class="rooms__link @if($gameTypeId == 2) rooms__link--active @endif" data-game-id="2" onclick="switchHomeLayout(2)">SMALL</li>
            </ul>
            <div id="roomClassic" class="rooms__box rooms__box--active">
                <p class="rooms__text">Минимальная ставка от 300 coins</p>
            </div>
            <div id="roomSmall" class="rooms__box">
                <p class="rooms__text">Максимальная ставка до 300 coins</p>
            </div>
        </div>


        <div class="wrap-first-step ">
            <div class="winners @if($game && $game->end_game_at && strtotime($game->end_game_at) < strtotime($time)) hide @endif">
                <a href="#" class="winners__game">{{ $gameType->name }} @if($game) #{{ $game->id }} @endif</a>
                <h5 class="winners__title">Игроки</h5>
                <div class="winners__slider">

                    @include('blocks.jackpot-participants')

                </div>
            </div>


        </div>


        <div class="choose-winner @if($game && $game->end_game_at
			&& strtotime($game->end_game_at) < strtotime($time)
			&& strtotime($game->animation_at) - 14 <= strtotime($time)
			&& strtotime($game->animation_at) - 5 >= strtotime($time)) @else hide @endif">
            <a href="#" class="choose-winner__game">Classic @if($game) #{{ $game->id }} @endif</a>
            <h5 class="choose-winner__title">ВЫБИРАЕМ ПОБЕДИТЕЛЯ</h5>
            <div class="wrap-choose-winner-slider">
                @include('blocks.choose-winner-slider')
            </div>
            <div class="choose-winner__point">
                <i class="ic-roulette-arrow"></i>
            </div>
        </div>

        <div class="winner winner-step-3 @if($game && strtotime($game->end_game_at) < strtotime($time) && strtotime($game->animation_at) - 5 <= strtotime($time)
			&& strtotime($game->animation_at) >= strtotime($time)
			&& $game->winner_ticket)  @else hide @endif">
            <h5 class="winner__title">ПОБЕДИТЕЛЬ</h5>
            <div class="winner__wrap">
                <div class="winner__left winner-circle">
                    <p class="winner-circle__text">ШАНС ВЫИГРЫША</p>
                    <span class="winner-circle__numbers winner-percent">{{ $result ? $result['percent'] : '' }}%</span>
                </div>
                <div class="winner__center">
                    <div class="winner__center-top">
                        <div class="winner__avatar">
                            <img src="{{ $result ? asset($result['image']) : '' }}" alt="avatar">
                        </div>
                        <div class="winner__box">
                            <p class="winner__name">{{ $result ? $result['name'] : '' }}</p>
                            <p class="winner__value">{{ $result ? $result['bank'] : '' }}<span>COINS</span></p>
                        </div>
                    </div>
                    <p class="winner__hash">Round number: <a target="_blank"
                                                             href="{{ $result ? $result['linkHash'] : '' }}">{{ $result ?$result['hash'] : '' }}</a>
                    </p>
                </div>
                <div class="winner__right winner-circle">
                    <p class="winner-circle__text">СЧАСЛИВЫЙ БИЛЕТ</p>
                    <span class="winner-circle__numbers winner-number"><i class="ic-dollar"></i> {{ $result ? $result['winnerTicket'] : '' }}</span>
                </div>
            </div>
        </div>

        <div class="jackpot-timer first">
            <div class="jackpot-timer__wrap first">
                <div class="jackpot-timer__left first">
                    <span></span>
                </div>
                <div class="jackpot-timer__center first">
                    <p class="jackpot-timer__title first">До начала</p>

                    <p id="jackpotTimer" class="jackpot-timer__time first">
                        {{ ($game && strtotime($game->end_game_at) > strtotime($time)) ? strtotime($game->end_game_at) - strtotime($time) : '35' }}</p>
                </div>
                <div class="jackpot-timer__right first">
                    <span></span>
                </div>
            </div>
        </div>

        <div class="jackpot-history">
            <div class="jackpot-history-bottom message-start-game">
                <div class="jackpot-history-bottom__left">
                    <img src="img/new-game-circle.png" alt="icon" class="jackpot-history__img">
                    <div class="jackpot-history__wrap">
                        <p class="jackpot-history__title">Игра началась, делайте ваши депозиты</p>
                        <p class="jackpot-history__hash">Хэш раунда Sha224
                            <a>{{ isset($game) ? hash('sha224', strval($game->winner_ticket_big)) : '' }}</a></p>
                    </div>
                </div>
                <div class="jackpot-history-bottom__right ">
					@if(auth()->user() && auth()->user()->id != 4)
                    <a data-izimodal-open="#betModal" data-izimodal-transitionin="fadeInDown" class="jackpot-history__button">СДЕЛАТЬ
                        СТАВКУ</a>
					@else
						<a class="jackpot-history__button" data-izimodal-open="#auth" data-izimodal-transitionin="fadeInDown">СДЕЛАТЬ
                        СТАВКУ</a>
					@endif
                </div>
            </div>
            <div class="jackpot-history__wrapper nano bets" style="height: auto">
                <div class="nano-content" style="position: unset;height: auto;">
                    <div class="jackpot-history-top @if($game && $game->end_game_at
			&& strtotime($game->end_game_at) < strtotime($time)
			&& strtotime($game->animation_at) - 14 <= strtotime($time)
			&& strtotime($game->animation_at) - 5 >= strtotime($time)) @else hide @endif">
                        <img src="{{ asset('img/time-out-circle.png') }}" alt="icon" class="jackpot-history__img">
                        <div class="jackpot-history__wrap">
                            <p class="jackpot-history__title">ВРЕМЯ СТАВОК ИСТЕКЛО</p>
                            <p class="jackpot-history__hint">Игра уже начинается!</p>
                            <p class="jackpot-history__hash">Хэш раунда Sha224
                                <a>{{ isset($game) ? hash('sha224', strval($game->winner_ticket_big)) : '' }}</a></p>
                        </div>
                    </div>
                    <ul class="jackpot-history__list bets">

                        @if($game)
                            @foreach($game->participants->reverse() as $bet)
                                @include('blocks.bet-jackpot')

                            @endforeach
                        @endif

                    </ul>


                </div>
            </div>

            <!-- Bank block -->
            <div class="bank mobile-show">
                <div class="bank__info">
                    <p class="bank__title">Банк:</p>
                    <p class="bank__players">@if($game) {{ count($game->participants) }} @else 0 @endif игроков</p>
                </div>
                <div class="bank__body">
                    <div class="bank__circle">
                        <div class="bank__circle-inside">
                            <p class="bank__value">@if($game) 100 @else 0 @endif
                                <span>COINS</span>
                            </p>
                        </div>
                    </div>
                    <a class="button button--gradient bank__button" data-izimodal-open="#betModal" data-izimodal-transitionin="fadeInDown"
                    >СДЕЛАТЬ СТАВКУ</a>
                </div>
            </div>

            <div class="jackpot-history__wrapper nano" style="margin-top:20px;">
                <div class="nano-content">

                    <ul class="jackpot-history__list last-winners">
                        <div class="jackpot-timer__wrap stats">
                            <p class="history__link history__link--active">ПОСЛЕДНИЕ 10 ПОБЕДИТЕЛЕЙ</p>

                        </div>
                        @foreach($games as $gameIteration)
                            @include('blocks.history-jackpot')

                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="jackpot-history__wrapper nano" style="margin-top:20px;    height: 300px;">
                <div class="nano-content">

                    <ul class="jackpot-history__list">


                        <div class="jackpot-timer__wrap stats">
                            <p class="history__link history__link--active">СЧАСТЛИВЧИК ДНЯ</p>

                        </div>


                        <li class="jackpot-history__item history-item history-item--"
                            style="border-bottom: 2px solid {{$luckyOfTheDay['color']}}">
                            <div class="history-item__left">
                                <img src="{{$luckyOfTheDay['image']}}" alt="avatar" class="history-item__avatar">
                                <div class="history-item__wrap">
                                    <p class="history-item__name">{{$luckyOfTheDay['name']}}</p>
                                </div>
                            </div>
                            <div class="history-item__right">
                                <p class="history-item__value"><span style="color:#a5a5a5">Выигрыш: </span>{{$luckyOfTheDay['bank']}} <span>COINS</span>
                                </p>
                                <p class="history-item__range"><span style="color:#a5a5a5">Шанс: </span>{{$luckyOfTheDay['percent']}} %</p>
                            </div>
                        </li>


                        <div class="jackpot-timer__wrap stats">
                            <p class="history__link history__link--active">НАИБОЛЬШИЙ ВЫИГРЫШ</p>

                        </div>
                        <li class="jackpot-history__item history-item history-item--"
                            style="border-bottom: 2px solid {{$biggestBank['color']}}">
                            <div class="history-item__left">
                                <img src="{{$biggestBank['image']}}" alt="avatar" class="history-item__avatar">
                                <div class="history-item__wrap">
                                    <p class="history-item__name">{{$biggestBank['name']}}</p>
                                </div>
                            </div>
                            <div class="history-item__right">
                                <p class="history-item__value"><span style="color:#a5a5a5">Выигрыш: </span>{{$biggestBank['bank']}} <span>COINS</span>
                                </p>
                                <p class="history-item__range"><span style="color:#a5a5a5">Шанс: </span>{{$biggestBank['percent']}} %</p>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        @include('blocks.balance', ['isMobile' => 1])
    </div>

    @include('blocks.chat')
</main>

<input type="hidden" name="game_type_id" value="{{ $gameTypeId }}">
<input type="hidden" name="gameId" value="{{ $game ? $game->id : '' }}">
