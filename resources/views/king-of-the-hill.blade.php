@extends('layout')

@section('content')

    <!-- Start section -->
    <style>
        .slick-track {
            width: 100000px !important;
            transform: translate3d(0px, 0px, 0px) !important;
        }
    </style>
    <div id="app"></div>
    <main class="main">
        <div class="main__left">

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

        </div>
        <div class="main__center main__center--king ">

            <div class="main__center--king-wrap nano">
                <div class="nano-content">

                    <div class="winner classic" style="display: none;">
                        <h5 class="winner__title">ПОБЕДИТЕЛЬ</h5>
                        <div class="winner__wrap">
                            <div class="winner__left winner-circle">
                                <p class="winner-circle__text">СТАВКА ПОБЕДИТЕЛЯ</p>
                                <span class="winner-circle__numbers">25%</span>
                            </div>
                            <div class="winner__center">
                                <div class="winner__center-top">
                                    <div class="winner__avatar">
                                        <img src="img/jackwin_avatar.png" alt="avatar">
                                    </div>
                                    <div class="winner__box ">
                                        <p class="winner__name">Jarkyh53</p>
                                        <p class="winner__value hide">5, 750<span>COINS</span></p>
                                    </div>
                                </div>
                                <p class="winner__hash hide">Round number: <a href="#">D14A028C2A3A2BC9476102BB288234C41</a></p>
                            </div>
                            <div class="winner__right winner-circle">
                                <p class="winner-circle__text">СУММА ВЫИГРЫША</p>
                                <span class="winner-circle__numbers"><i class="ic-dollar"></i> 6246</span>
                            </div>
                        </div>
                    </div>

                    <div class="game game-classic">
                        <div class="game__left">
                            <p class="game__title">Classic #{{ $gameClassic ? $gameClassic->id : $lastGame->id + 1 }}</p>
                            <ul class="game__participants game__participants_classic">
                                @if($gameClassic)
                                    @foreach($gameClassic->participants->reverse() as $key => $participant)
                                        @if($loop->index == $gameClassic->participants->count()-1)
                                            @continue
                                        @endif
                                        @include('blocks.king-participants')
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="game__center">
                            <div class="game__winner">
                                @if($gameClassic && $gameClassic->participants->count() > 0)
                                    <img src="{{ asset($gameClassic->participants->first()->account->image) }}" alt="winner"
                                         class="game__winner-img">
                                @else
                                    <img src="{{ asset('img/fox.png') }}" alt="winner" class="game__winner-img">
                                @endif
                            </div>
                        </div>
                        <div class="game__bank game__bank--mobile">
                            <p class="game__bank-title">В БАНКЕ:</p>
                            <span class="game__bank-value">{{ $gameClassic ? $gameClassic->participants->sum('cash') : 0 }}</span>
                            <span class="game__bank-text">COINS</span>
                        </div>
                        <div class="game__right">
                            <div class="game__bank">
                                <p class="game__bank-title">В БАНКЕ:</p>
                                <span class="game__bank-value">{{ $gameClassic ? $gameClassic->participants->sum('cash') : 0 }}</span>
                                <span class="game__bank-text">COINS</span>
                            </div>
                            <div class="game__time">
                                <p class="game__time-text classic-timer
                                    @if(!$gameClassic || !$gameClassic->end_game_at || $gameClassic->end_game_at < now() || $gameClassic->participants->groupBy('account_id')->count() < 2) @endif">
                                    Осталось:
                                    <span class="game__time-value">00:<span
                                            class="game__time-counter">{{ ($gameClassic->status_id > 1) ? strtotime($gameClassic->end_game_at) - strtotime($timer) : '20' }}</span></span>
                                </p>
                                @if(auth()->user() && auth()->user()->id != 4)
                                    <a class="game__time-link button button--gradient" id="bedBet"
                                       onclick="MainFunction.createGameKing('classic');">ВНЕСТИ
                                        <span>{{ $gameClassic && $gameClassic->participants->count() ? $gameClassic->participants->count() * 10 + 10 : 10 }}</span>
                                        COINS</a>
                                @else
                                    <a class="game__time-link button button--gradient" id="bedBet" data-izimodal-open="#auth"
                                       data-izimodal-transitionin="fadeInDown">ВНЕСТИ
                                        <span>{{ $gameClassic && $gameClassic->participants->count() ? $gameClassic->participants->count() * 10 + 10 : 10 }}</span>
                                        COINS</a>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="winner senyor" style="display: none;">
                        <h5 class="winner__title">ПОБЕДИТЕЛЬ</h5>
                        <div class="winner__wrap">
                            <div class="winner__left winner-circle">
                                <p class="winner-circle__text">СТАВКА ПОБЕДИТЕЛЯ</p>
                                <span class="winner-circle__numbers">25%</span>
                            </div>
                            <div class="winner__center">
                                <div class="winner__center-top">
                                    <div class="winner__avatar">
                                        <img src="img/jackwin_avatar.png" alt="avatar">
                                    </div>
                                    <div class="winner__box ">
                                        <p class="winner__name">Jarkyh53</p>
                                        <p class="winner__value hide">5, 750<span>COINS</span></p>
                                    </div>
                                </div>
                                <p class="winner__hash hide">Round number: <a href="#">D14A028C2A3A2BC9476102BB288234C41</a></p>
                            </div>
                            <div class="winner__right winner-circle">
                                <p class="winner-circle__text">СУММА ВЫИГРЫША</p>
                                <span class="winner-circle__numbers"><i class="ic-dollar"></i> 6246</span>
                            </div>
                        </div>
                    </div>


                    <div class="game game-senyor">
                        <div class="game__left">
                            <p class="game__title">Senyor #{{ $gameSenyor ? $gameSenyor->id : $lastGame->id + 2 }}</p>
                            <ul class="game__participants game__participants_senyor">
                                @if($gameSenyor)
                                    @foreach($gameSenyor->participants->reverse() as $key => $participant)
                                        @if($loop->index == $gameSenyor->participants->count()-1)

                                            @continue

                                        @endif
                                        @include('blocks.king-participants')
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="game__center">
                            <div class="game__winner">
                                @if($gameSenyor && $gameSenyor->participants->count() > 0)
                                    <img src="{{ asset($gameSenyor->participants->first()->account->image) }}" alt="winner"
                                         class="game__winner-img">
                                @else
                                    <img src="{{ asset('img/fox.png') }}" alt="winner" class="game__winner-img">
                                @endif
                            </div>
                        </div>
                        <div class="game__bank game__bank--mobile">
                            <p class="game__bank-title">В БАНКЕ:</p>
                            <span class="game__bank-value">{{ $gameSenyor ? $gameSenyor->participants->sum('cash') : 0 }}</span>
                            <span class="game__bank-text">COINS</span>
                        </div>
                        <div class="game__right">
                            <div class="game__bank">
                                <p class="game__bank-title">В БАНКЕ:</p>
                                <span class="game__bank-value">{{ $gameSenyor ? $gameSenyor->participants->sum('cash') : 0 }}</span>
                                <span class="game__bank-text">COINS</span>
                            </div>
                            <div class="game__time">
                                <p class="game__time-text senyor-timer
                                @if(!$gameSenyor
                                || !$gameSenyor->end_game_at
                                || $gameSenyor->end_game_at < now()
                                || $gameSenyor->participants->groupBy('account_id')->count() < 2) @endif">Осталось:
                                    <span class="game__time-value">00:<span
                                            class="game__time-counter">{{ ($gameSenyor->status_id > 1) ? strtotime($gameSenyor->end_game_at) - strtotime($timer) : '20' }}</span></span>
                                </p>
                                @if(auth()->user() && auth()->user()->id != 4)
                                    <a class="game__time-link button button--gradient" data-izimodal-open="#betModal"
                                       data-izimodal-transitionin="fadeInDown"
                                       id="goodBet">Внести
                                        <span>{{ count($gameSenyor->participants) > 0 ? $gameSenyor->participants->sortByDesc('created_at')->first()->cash + 1 : 1 }}</span>
                                        COINS</a>
                                @else
                                    <a class="game__time-link button button--gradient" data-izimodal-open="#auth"
                                       data-izimodal-transitionin="fadeInDown"
                                       id="goodBet">Внести
                                        <span>{{  count($gameSenyor->participants) > 0 ? $gameSenyor->participants->sortByDesc('cash')->first()->cash + 1 : 1 }}</span>
                                        COINS</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <ul class="game-history">
                        @foreach($games as $game)
                            @include('blocks.history-king')
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Help block -->
            <div class="help mobile-show">
                <p class="help__title">Помощь</p>
                <a class="help__link" data-izimodal-open="#helpModal" data-izimodal-transitionin="fadeInDown">
                    <i class="ic-rules"></i>
                    Правила игры
                </a>
            </div>

            <!-- Application block -->
        <!-- @include('blocks.application', ['isMobile' => 1]) -->

            @include('blocks.balance', ['isMobile' => 1])

        </div>
        @include('blocks.chat')
    </main>
    <!-- End section -->

    <div class="modal modal--bet" id="betModal">
        <div class="modal__top">
            <h6 class="modal__title">СДЕЛАТЬ СТАВКУ</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Текущий баланс:</p>
                <p class="conclusion__balance-value">{{ getBalance(auth()->user()) }} <span>coins</span></p>
            </div>
            <form class="bet__form">
                <input type="hidden" name="balance" value="{{ getBalance(auth()->user()) }}">
                <div class="bet__form-wrap">
                    <label class="bet__form-label">COINS</label>
                    <div id="coins" class="bet__form-box">
                        <input type="number" onchange="MainFunction.calculate(this, event, 'senyor');"
                               onkeyup="MainFunction.calculate(this, event, 'senyor');" step="2"
                               class="bet__form-input cash-coins"
                               value="{{ count($gameSenyor->participants) > 0 ? $gameSenyor->participants->sortByDesc('created_at')->first()->cash + 1 : 1 }}">
                        <span class="bet__form-up"></span>
                        <span class="bet__form-down"></span>
                    </div>
                </div>
                <span class="bet__form-sum">=</span>
                <div class="bet__form-wrap bet__form-wrap--gray">
                    <label class="bet__form-label">руб</label>
                    <div id="coins-result" class="bet__form-box count-money">
                        <input type="number" class="bet__form-input" readonly value="30">
                        <span class="bet__form-up"></span>
                        <span class="bet__form-down"></span>
                    </div>
                </div>
            </form>

            <div class="warning__balance hide">
                <p class="warning__balance-text">* На вашем счете недостаточно средств для ставки</p>
            </div>
            <div class="modal__buttons">
                <a href="javascript:void(0);" onclick="MainFunction.createGameKing('senyor');" class="button button--gradient">СДЕЛАТЬ
                    СТАВКУ</a>
                <a href="#" class="button" data-izimodal-open="#inputModal" data-izimodal-transitionin="fadeInDown">Пополнить</a>
            </div>
        </div>
    </div>


    @include('account.blocks.popups')


    <script>

        $(".game__participants_classic").slick({
            infinite: false,
            slidesToShow: 7,
            slidesToScroll: 1,
            arrows: !1,
            centerMode: false,
            variableWidth: !0,
            responsive: [{breakpoint: 1640, settings: {slidesToShow: 3, slidesToScroll: 1}}, {
                breakpoint: 1400,
                settings: {slidesToShow: 2, slidesToScroll: 1, centerMode: !1}
            }]
        });

        var slidesData = '';
        $($('.game__participants_classic li').get().reverse()).each(function (i) {
            slidesData += $(this).prop('outerHTML');
        });
        $('.game__participants_classic .slick-track').html('');
        $('.game__participants_classic .slick-track').html(slidesData);


        $(".game__participants_senyor").slick({
            infinite: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: !1,
            centerMode: false,
            variableWidth: true,
            responsive: [{breakpoint: 1640, settings: {slidesToShow: 3, slidesToScroll: 1}}, {
                breakpoint: 1400,
                settings: {slidesToShow: 2, slidesToScroll: 1, centerMode: !1}
            }]
        });
        var slidesData = '';
        $($('.game__participants_senyor li').get().reverse()).each(function (i) {
            slidesData += $(this).prop('outerHTML');
        });
        $('.game__participants_senyor .slick-track').html('');
        $('.game__participants_senyor .slick-track').html(slidesData);


    </script>

@endsection
