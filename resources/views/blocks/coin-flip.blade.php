@extends('layout')

@section('content')


    <body @if(!checkSessionTheme()) class="body--dark" @endif>
    @include('blocks.header')
    <main class="main">
        <div class="main__left">

            <!-- Create game block -->
            <div class="create-game">
                <div class="create-game__info">
                    <p class="create-game__title">Новая игра:</p>
                </div>
                <div class="create-game__body">

                    @if(auth()->user() && auth()->user()->id != 4)
                        <a href="javascript:void(0);" data-izimodal-open="#createGame" data-izimodal-transitionin="fadeInDown" class="create-game__button">
                            <span>+</span><br>
                            Создать<br> игру
                        </a>
                    @else
                        <a href="javascript:void(0);" class="create-game__button" data-izimodal-open="#auth" data-izimodal-transitionin="fadeInDown">
                            <span>+</span><br>
                            Создать<br> игру
                        </a>
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
        <div class="main__center main__center--king">

            

            <!-- Create game block -->
            <div class="create-game mobile-show">
                <div class="create-game__info">
                    <p class="create-game__title">Новая игра:</p>
                </div>
                <div class="create-game__body">
                    @if(auth()->user() && auth()->user()->id != 4)
                        <a href="javascript:void(0);" data-izimodal-open="#createGame" data-izimodal-transitionin="fadeInDown" class="create-game__button">
                            <span>+</span><br>
                            Создать<br> игру
                        </a>
                    @else
                        <a class="create-game__button" data-izimodal-open="#auth" data-izimodal-transitionin="fadeInDown">
                            <span>+</span><br>
                            Создать<br> игру
                        </a>
                    @endif
                </div>
            </div>

            <div class="main__center--king-wrap nano">
                <div class="nano-content">

                    <!-- History list player -->
                    <div class="coin-history">
                        <div class="coin-history__left">
                            <h4 class="coin-history__title">Ежедневная статистика</h4>
                            <div class="coin-history-box">
                                <div class="coin-history-box__col">
                                    <p class="coin-history-box__value coin-history-box__value--orange">{{ $bank }} <span>COINS</span></p>
                                    <p class="coin-history-box__text">Общая сумма</p>
                                </div>
                                <div class="coin-history-box__col coin-history-box__col--center">
                                    <p class="coin-history-box__value">{{ $gamesToday }}</p>
                                    <p class="coin-history-box__text">Всего игр</p>
                                </div>
                                <div class="coin-history-box__col coin-history-box__col--last">
                                    <p class="coin-history-box__value">{{ count($activeGames) }}</p>
                                    <p class="coin-history-box__text">Игр в ожидании</p>
                                </div>
                            </div>
                        </div>
                        <div class="coin-history__right">
                            @if(auth()->user() && auth()->user()->id != 4)
                                <h4 class="coin-history__title">ВАШИ ИГРЫ</h4>
                                <div class="coin-history-box">
                                    <div class="coin-history-box__col bet-indicator @if($userGamesNow == 0) disabled @endif">
                                        @if($userGamesNow > 0)
                                        <i class="ic-user"></i>
                                        <p class="coin-history-box__text">Есть ставки</p>
                                    @else
                                    <i class="ic-user"></i>
                                    <p class="coin-history-box__text">Нет ставок</p>
                                    @endif
                                    </div>
                                    <div class="coin-history-box__col coin-history-box__col--center">
                                        <p class="coin-history-box__value">{{ $userGames }}</p>
                                        <p class="coin-history-box__text">Создано игр</p>
                                    </div>
                                    <div class="coin-history-box__col coin-history-box__col--last">
                                        <p class="coin-history-box__value coin-history-box__value--orange">{{ $bankUser }} <span>COINS</span></p>
                                        <p class="coin-history-box__text">Сумма выигрышей</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <ul class="coin-hint">
                        <li class="coin-hint__text">Игрок</li>
                        <li class="coin-hint__text">Ставка</li>
                        <li class="coin-hint__text">Наблюдение</li>
                        <li class="coin-hint__text">Ставка</li>
                        <li class="coin-hint__text">Игрок</li>
                    </ul>


                    <div class="wrap-coinflip">
                        @foreach($activeGames as $game)
                            @include('blocks.coinflip-game')
                        @endforeach

                    </div>


                    @foreach($animationGames as $game)
                    <!-- Common game block -->
                        @include('blocks.animation-game')
                    @endforeach

                    <!-- History list games-->
                    <ul class="coin-game-history">
                        @foreach($games as $game)
                            @include('blocks.history-coinflip', ['game' => $game])
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

            <!-- History list player -->
            <div class="coin-history mobile-show">
                <div class="coin-history__left">
                    <h4 class="coin-history__title">Ежедневная статистика</h4>
                    <div class="coin-history-box">
                        <div class="coin-history-box__col">
                            <p class="coin-history-box__value coin-history-box__value--orange">4, 127 <span>COINS</span></p>
                            <p class="coin-history-box__text">Общая сумма</p>
                        </div>
                        <div class="coin-history-box__col coin-history-box__col--center">
                            <p class="coin-history-box__value">325</p>
                            <p class="coin-history-box__text">Всего игр</p>
                        </div>
                        <div class="coin-history-box__col coin-history-box__col--last">
                            <p class="coin-history-box__value">73</p>
                            <p class="coin-history-box__text">Игр в ожидании</p>
                        </div>
                    </div>
                </div>
                <div class="coin-history__right">
                    <h4 class="coin-history__title">ВАШИ ИГРЫ</h4>
                    <div class="coin-history-box">
                        <div class="coin-history-box__col bet-indicator  @if($userGamesNow == 0) disabled @endif">

                            @if($userGamesNow > 0)
                                        <i class="ic-user"></i>
                                        <p class="coin-history-box__text">Есть ставки</p>
                                    @else
                                    <i class="ic-user"></i>
                                    <p class="coin-history-box__text">Нет ставок</p>
                                    @endif
                        </div>
                        <div class="coin-history-box__col coin-history-box__col--center">
                            <p class="coin-history-box__value">25</p>
                            <p class="coin-history-box__text">Создано игр</p>
                        </div>
                        <div class="coin-history-box__col coin-history-box__col--last">
                            <p class="coin-history-box__value coin-history-box__value--orange">1, 027 <span>COINS</span></p>
                            <p class="coin-history-box__text">Сумма выигрышей</p>
                        </div>
                    </div>
                </div>
            </div>
            @include('blocks.balance', ['isMobile' => 1])
            <!-- @include('blocks.notification', ['isMobile' => 1]) -->


            @include('blocks.copyright', ['isMobile' => 1])

            <ul class="hide">
                <li>
                    <a data-izimodal-open="#createGame" data-izimodal-transitionin="fadeInDown">Окно 1. Создание игры</a>
                </li>
                <li class="hide">
                    <a data-izimodal-open="#waitPlayer" data-izimodal-transitionin="fadeInDown">Окно 2, ожидание второго игрока или зрителя</a>
                </li>
                <li>
                    <a data-izimodal-open="#waitPlayer2" data-izimodal-transitionin="fadeInDown">Окно 3 , когда присоединился второй игрок</a>
                </li>
                <li>
                    <a data-izimodal-open="#waitPlayer3" data-izimodal-transitionin="fadeInDown">Окно 4 ,добавляем следующий глаз</a>
                </li>
                <li>
                    <a data-izimodal-open="#waitPlayer4" data-izimodal-transitionin="fadeInDown">Окно 5 , игра началась</a>
                </li>
                <li>
                    <a data-izimodal-open="#waitPlayer5" data-izimodal-transitionin="fadeInDown">Окно 6. Выигрыш</a>
                </li>
                <li>
                    <a data-izimodal-open="#waitPlayer6" data-izimodal-transitionin="fadeInDown">Окно 6. Выигрыш от первого игрока</a>
                </li>
                <li>
                    <a data-izimodal-open="#waitPlayer7" data-izimodal-transitionin="fadeInDown">Окно 6. Выигрыш от второго игрока</a>
                </li>
                <li>
                    <a data-izimodal-open="#waitPlayer8" data-izimodal-transitionin="fadeInDown">Окно 6 Окно для третьего  лица</a>
                </li>
            </ul>
        </div>
        @include('blocks.chat')
    </main>




    @include('account.blocks.popups')

    <div class="modal modal--bet" id="betModal">
        <input type="hidden" name="balance" value="{{ getBalance(auth()->user()) }}">
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
                <div class="bet__form-wrap">
                    <label class="bet__form-label">COINS</label>
                    <div id="coins" class="bet__form-box">
                        <input type="number" class="bet__form-input" value="12345" onchange="MainFunction.calculate(this);" onkeyup="MainFunction.calculate(this);">
                        <span class="bet__form-up"></span>
                        <span class="bet__form-down"></span>
                    </div>
                </div>
                <span class="bet__form-sum">=</span>
                <div class="bet__form-wrap bet__form-wrap--gray">
                    <label class="bet__form-label">руб</label>
                    <div id="coins-result" class="bet__form-box">
                        <input type="number" class="bet__form-input" value="12345">
                        <span class="bet__form-up"></span>
                        <span class="bet__form-down"></span>
                    </div>
                </div>
            </form>

            <div class="warning__balance hide">
                <p class="warning__balance-text">* На вашем счете недостаточно средств для ставки</p>
            </div>
            <div class="modal__buttons hide">
                <a href="#" class="button button--gradient">СДЕЛАТЬ СТАВКУ</a>
                <a href="#" class="button" data-izimodal-open="#inputModal" data-izimodal-transitionin="fadeInDown">Пополнить</a>
            </div>
        </div>
    </div>

    <div class="modal modal--bet " id="betModalCoin">
        <div class="modal__top">
            <h6 class="modal__title">СДЕЛАТЬ СТАВКУ</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Текущий баланс:</p>
                <p class="conclusion__balance-value">{{ getBalance(auth()->user()) }} <span>coins</span></p>
            </div>
            <div class="bet-avatar">
                <div class="bet-avatar__wrap">
                    <i class="ic-coin-orange"></i>
                    <img src="{{ asset(auth()->user()->image) }}" alt="avatar" class="bet-avatar__img">
                </div>
            </div>
            <form class="bet__form">
                <input type="hidden" name="balance" value="{{ getBalance(auth()->user()) }}">
                <div class="bet__form-wrap">
                    <label class="bet__form-label">COINS</label>
                    <div id="coins2" class="bet__form-box">
                        <input type="number" name="balance" class="bet__form-input" name="cash"  onkeyup="MainFunction.validatePrice(this); MainFunction.calculate(this, event, 'coinflip');"
                               onchange="MainFunction.validatePrice(this); MainFunction.calculate(this, event, 'coinflip');" value="100">
                        <span class="bet__form-up"></span>
                        <span class="bet__form-down"></span>
                    </div>
                </div>
                <span class="bet__form-sum">=</span>
                <div class="bet__form-wrap bet__form-wrap--gray">
                    <label class="bet__form-label">руб</label>
                    <div id="coins-result2" class="bet__form-box count-money">
                        <input type="number" class="bet__form-input" readonly value="10">
                        <span class="bet__form-up"></span>
                        <span class="bet__form-down"></span>
                    </div>
                </div>
            </form>

            <div class="warning__balance hide balance-warning">
                <p class="warning__balance-text">* На вашем счете недостаточно средств для ставки</p>
            </div>
            <div class="warning__balance hide min-price-warning">
                <p class="warning__balance-text">* Минимальная ставка от 100 coins</p>
            </div>
            <div class="modal__buttons">
                <a href="#" onclick="MainFunction.createGameCoinflip(this);" class="button button--gradient">СДЕЛАТЬ СТАВКУ</a>
                <a href="#" class="button button--border" data-izimodal-open="#inputModal" data-izimodal-transitionin="fadeInDown">Пополнить</a>
            </div>
        </div>
    </div>

    <div class="modal modal--bet" id="betModalCoin2">
        <div class="modal__top">
            <h6 class="modal__title">СДЕЛАТЬ СТАВКУ</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Текущий баланс:</p>
                <p class="conclusion__balance-value">53,218 <span>coins</span></p>
            </div>
            <div class="bet-avatar">
                <div class="bet-avatar__wrap">
                    <i class="ic-coin-orange"></i>
                    <img src="img/jackpot-winnter-avatar.png" alt="avatar" class="bet-avatar__img">
                </div>
            </div>
            <form class="bet__form bet__form--left">
                <p class="bet__form-text">Нужно внести 700500 coins</p>
                <div class="bet__form-wrap">
                    <label class="bet__form-label">COINS</label>
                    <div id="coins3" class="bet__form-box">
                        <input type="number" class="bet__form-input" value="1234567">
                        <span class="bet__form-up"></span>
                        <span class="bet__form-down"></span>
                    </div>
                </div>
                <span class="bet__form-sum">=</span>
                <div class="bet__form-wrap bet__form-wrap--gray">
                    <label class="bet__form-label">руб</label>
                    <div id="coins-result3" class="bet__form-box">
                        <input type="number" class="bet__form-input" value="1234567">
                        <span class="bet__form-up"></span>
                        <span class="bet__form-down"></span>
                    </div>
                </div>
            </form>

            <div class="warning__balance">
                <p class="warning__balance-text">* На вашем счете недостаточно средств для ставки</p>
            </div>
            <div class="modal__buttons">
                <a href="#" class="button button--gradient">СДЕЛАТЬ СТАВКУ</a>
                <a href="#" class="button button--border" data-izimodal-open="#inputModal" data-izimodal-transitionin="fadeInDown">Пополнить</a>
            </div>
        </div>
    </div>


    <div class="modal modal--bet" id="createGame">
        <div class="modal__top">
            <h6 class="modal__title">Coinflip</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Round hash:</p>
                <a href="" target="blank" class="conclusion__balance-hash"></a>
            </div>
        </div>
        <div class="coin-create">
            <div class="coin-create__orange">
                <a onclick="MainFunction.selectColor('orange');" data-izimodal-open="#betModalCoin" data-izimodal-transitionin="fadeInDown" class="coin-create__icon">
                    <i class="ic-coin-orange"></i>
                </a>
                <p class="coin-create__tickets">0-50</p>
            </div>
            <div class="coin-create__center coin-create__center--one">
                <span class="coin-create__vs">vs</span>
                <p class="wait-palyer__bet">Bank</p>
                <p class="wait-palyer__bet-done">0</p>
                <div class="wait-palyer__timer">
                    <i class="ic-timer"></i>
                    <p class="wait-palyer__timer-text">0:<span id="coinModalTimer" class="wait-palyer__timer-value">10</span></p>
                </div>
            </div>
            <div class="coin-create__blue">
                <a onclick="MainFunction.selectColor('blue');" data-izimodal-open="#betModalCoin" data-izimodal-transitionin="fadeInDown" class="coin-create__icon">
                    <i class="ic-coin-blue"></i>
                </a>
                <p class="coin-create__tickets">50-100</p>
            </div>
        </div>
        <div class="modal-hint">
            <p class="modal-hint__text">Выберите цвет</p>
        </div>
    </div>

    <div class="modal modal--bet" id="waitPlayer"></div>

    <div class="modal modal--bet" id="waitPlayer2">
        <div class="modal__top">
            <h6 class="modal__title">Coinflip #51285</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Round hash:</p>
                <a href="" target="blank" class="conclusion__balance-hash"></a>
            </div>
        </div>

        <div class="wait-palyer">
            <div class="wait-palyer__left">
                <div class="wait-palyer__box">
                    <i class="ic-coin-orange"></i>
                    <div class="wait-palyer__avatar">
                        <img src="img/jackpot-winnter-avatar.png" alt="avatar">
                    </div>
                </div>
                <p class="wait-palyer__nick">Temkin</p>
                <p class="wait-palyer__rate">7, 528 <br><span>COINS</span></p>
                <p class="wait-palyer__tickets">0-50</p>
            </div>
            <div class="coin-create__center coin-create__center--one">
                <span class="coin-create__vs">vs</span>
                <p class="wait-palyer__bet">Bank</p>
                <p class="wait-palyer__bet-done">150,056</p>
                <div class="wait-palyer__timer">
                    <i class="ic-timer"></i>
                    <p class="wait-palyer__timer-text">0:<span id="coinModalTimer3" class="wait-palyer__timer-value">10</span></p>
                </div>
            </div>
            <div class="wait-palyer__right">
                <div class="wait-palyer__loader">
                    <i class="ic-load-gray"></i>
                </div>
                <p class="wait-palyer__loader-hint">Ожидание игрока</p>
                <p class="wait-palyer__tickets">50-100</p>
            </div>
        </div>
        <div class="modal-hint">
            <p class="modal-hint__text">Сделайте ставку</p>
        </div>

        <div class="modal__buttons">
            <a class="button button--gradient" data-izimodal-open="#betModal" data-izimodal-transitionin="fadeInDown">СДЕЛАТЬ СТАВКУ</a>
            <a href="#" class="button button--border" data-izimodal-close>Отмена</a>
        </div>
    </div>

    <div class="modal modal--bet modal--done" id="waitPlayer3">
        <div class="modal__top">
            <h6 class="modal__title">Coinflip #51285</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Round hash:</p>
                <a href="d14a028c2a3a2bc9476102bb288234c415a2b01f828ea62ac5b3e42f" target="blank" class="conclusion__balance-hash">d14a028c2a3a2bc9476102bb288234c415a2b01f828ea62ac5b3e42f</a>
            </div>
        </div>

        <div class="wait-palyer">
            <div class="wait-palyer__left">
                <div class="wait-palyer__box">
                    <i class="ic-coin-orange"></i>
                    <div class="wait-palyer__avatar">
                        <img src="img/jackpot-winnter-avatar.png" alt="avatar">
                    </div>
                </div>
                <p class="wait-palyer__nick">Temkin</p>
                <p class="wait-palyer__rate">7, 528 <br><span>COINS</span></p>
                <p class="wait-palyer__tickets">0-50</p>
            </div>
            <div class="wait-palyer__center">
                <span class="wait-palyer__vs">vs</span>
                <p class="wait-palyer__bet">Bank</p>
                <p class="wait-palyer__bet-done">150, 056</p>
                <div class="wait-palyer__timer">
                    <i class="ic-timer"></i>
                    <p class="wait-palyer__timer-text">0:<span id="coinModalTimer4" class="wait-palyer__timer-value">30</span></p>
                </div>
            </div>
            <div class="wait-palyer__right">
                <div class="wait-palyer__box">
                    <i class="ic-coin-blue"></i>
                    <div class="wait-palyer__avatar">
                        <img src="img/jackpot-winnter-avatar.png" alt="avatar">
                    </div>
                </div>
                <p class="wait-palyer__nick">Temkin2</p>
                <p class="wait-palyer__rate">7, 528 <br><span>COINS</span></p>
                <p class="wait-palyer__tickets">50-100</p>
            </div>
        </div>
        <div class="wait-palyer__spinner">
            <p class="wait-palyer__spinner-text">Ожидание игры</p>
            <i class="ic-load-green wait-palyer__spinner-icon"></i>
        </div>
    </div>

    <div class="modal modal--bet modal--done" id="waitPlayer4">
        <div class="modal__top">
            <h6 class="modal__title">Coinflip #51285</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Round hash:</p>
                <a href="d14a028c2a3a2bc9476102bb288234c415a2b01f828ea62ac5b3e42f" target="blank" class="conclusion__balance-hash">d14a028c2a3a2bc9476102bb288234c415a2b01f828ea62ac5b3e42f</a>
            </div>
        </div>

        <div class="wait-palyer">
            <div class="wait-palyer__left">
                <div class="wait-palyer__box">
                    <i class="ic-coin-orange"></i>
                    <div class="wait-palyer__avatar">
                        <img src="img/jackpot-winnter-avatar.png" alt="avatar">
                    </div>
                </div>
                <p class="wait-palyer__nick">Temkin</p>
                <p class="wait-palyer__rate">7, 528 <br><span>COINS</span></p>
                <p class="wait-palyer__tickets">0-50</p>
            </div>
            <div class="wait-palyer__roulette">
                <div class="wait-palyer__roulette-slider">
                    <div class="wait-palyer__roulette-orange">
                        <img src="img/icons/coin-orange.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-blue">
                        <img src="img/icons/coin-blue.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-orange">
                        <img src="img/icons/coin-orange.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-blue">
                        <img src="img/icons/coin-blue.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-orange">
                        <img src="img/icons/coin-orange.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-blue">
                        <img src="img/icons/coin-blue.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-orange">
                        <img src="img/icons/coin-orange.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-blue">
                        <img src="img/icons/coin-blue.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-blue">
                        <img src="img/icons/coin-blue.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-orange">
                        <img src="img/icons/coin-orange.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-blue">
                        <img src="img/icons/coin-blue.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-orange">
                        <img src="img/icons/coin-orange.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-blue">
                        <img src="img/icons/coin-blue.png" alt="icon">
                    </div>
                </div>
                
            </div>
            <div class="wait-palyer__right">
                <div class="wait-palyer__box">
                    <i class="ic-coin-blue"></i>
                    <div class="wait-palyer__avatar">
                        <img src="img/jackpot-winnter-avatar.png" alt="avatar">
                    </div>
                </div>
                <p class="wait-palyer__nick">Temkin2</p>
                <p class="wait-palyer__rate">7, 528 <br><span>COINS</span></p>
                <p class="wait-palyer__tickets">50-100</p>
            </div>
        </div>
        <div class="wait-palyer__spinner">
            <p class="wait-palyer__spinner-text">Игра началась</p>
            <i class="ic-load-green wait-palyer__spinner-icon"></i>
        </div>
    </div>

    <div class="modal modal--bet modal--done" id="waitPlayer5">
        <div class="modal__top">
            <h6 class="modal__title">Coinflip #51285</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Round hash:</p>
                <a href="d14a028c2a3a2bc9476102bb288234c415a2b01f828ea62ac5b3e42f" target="blank" class="conclusion__balance-hash">d14a028c2a3a2bc9476102bb288234c415a2b01f828ea62ac5b3e42f</a>
                <p class="modal-hash__text">Round secret 43565656565656565656</p>
            </div>
        </div>

        <div class="wait-palyer">
            <div class="wait-palyer__left wait-palyer--win">
                <div class="wait-palyer__box">
                    <i class="ic-coin-orange"></i>
                    <div class="wait-palyer__avatar">
                        <img src="img/jackpot-winnter-avatar.png" alt="avatar">
                    </div>
                </div>
                <p class="wait-palyer__nick">Temkin</p>
                <p id="coinModalMiniTimer1" class="wait-palyer-timer__text">0</p>
                <p class="wait-palyer__tickets">0-50</p>
                <p class="wait-palyer__text">Победил</p>
            </div>
            <div class="wait-palyer__roulette">
                <div class="wait-palyer__roulette-slider">
                    <div class="wait-palyer__roulette-orange">
                        <img src="img/icons/coin-orange.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-blue">
                        <img src="img/icons/coin-blue.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-orange">
                        <img src="img/icons/coin-orange.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-blue">
                        <img src="img/icons/coin-blue.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-orange">
                        <img src="img/icons/coin-orange.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-blue">
                        <img src="img/icons/coin-blue.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-orange">
                        <img src="img/icons/coin-orange.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-blue">
                        <img src="img/icons/coin-blue.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-blue">
                        <img src="img/icons/coin-blue.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-orange">
                        <img src="img/icons/coin-orange.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-blue">
                        <img src="img/icons/coin-blue.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-orange">
                        <img src="img/icons/coin-orange.png" alt="icon">
                    </div>
                    <div class="wait-palyer__roulette-blue">
                        <img src="img/icons/coin-blue.png" alt="icon">
                    </div>
                </div>
               
            </div>
            <div class="wait-palyer__right wait-palyer--lose">
                <div class="wait-palyer__box">
                    <i class="ic-coin-blue"></i>
                    <div class="wait-palyer__avatar">
                        <img src="img/jackpot-winnter-avatar.png" alt="avatar">
                    </div>
                </div>
                <p class="wait-palyer__nick">Temkin</p>
                <p id="coinModalMiniTimer2" class="wait-palyer-timer__text">9999</p>
                <p class="wait-palyer__tickets">50-100</p>
                <p class="wait-palyer__text">Проиграл</p>
            </div>
        </div>
        <div class="wait-palyer__action">
            <p class="wait-palyer__action-text">Победил: Дмитрий</p>
        </div>

    </div>

    <div class="modal modal--bet modal--wait" id="waitPlayer6">
        <div class="modal__top">
            <h6 class="modal__title">Coinflip #51285</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Round hash:</p>
                <a href="" target="blank" class="conclusion__balance-hash"></a>
            </div>
        </div>

        <div class="wait-palyer">
            <div class="wait-palyer__left">
                <div class="wait-palyer__box">
                    <i class="ic-coin-orange"></i>
                    <div class="wait-palyer__avatar">
                        <img src="img/jackpot-winnter-avatar.png" alt="avatar">
                    </div>
                </div>
                <p class="wait-palyer__nick">Temkin</p>
                <p class="wait-palyer__rate">7, 528 <br><span>COINS</span></p>
                <p class="wait-palyer__tickets">0-50</p>
            </div>
            <div class="wait-palyer__center">
                <span class="wait-palyer__vs">vs</span>
                <p class="wait-palyer__bet">Bank</p>
                <p class="wait-palyer__value">Билеты</p>
                <div class="wait-palyer__timer">
                    <i class="ic-timer"></i>
                    <p class="wait-palyer__timer-text">0:<span id="coinModalTimer5" class="wait-palyer__timer-value">30</span></p>
                </div>
            </div>
            <div class="wait-palyer__right">
                <div class="wait-palyer__box">
                    <i class="ic-coin-blue"></i>
                    <div class="wait-palyer__avatar">
                        <img src="img/jackpot-winnter-avatar.png" alt="avatar">
                    </div>
                </div>
                <p class="wait-palyer__nick">Temkin2</p>
                <p class="wait-palyer__rate--load"><i class="ic-load-gray"></i></p>
                <p class="wait-palyer__tickets">50-100</p>
            </div>
        </div>
        <div class="wait-palyer__spinner">
            <p class="wait-palyer__spinner-text">Ожидание ставки соперника</p>
            <i class="ic-load-green wait-palyer__spinner-icon"></i>
        </div>
    </div>

    <div class="modal modal--bet modal--done-two" id="waitPlayer7">
        <div class="modal__top">
            <h6 class="modal__title">Coinflip #51285</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Round hash:</p>
                <a href="d14a028c2a3a2bc9476102bb288234c415a2b01f828ea62ac5b3e42f" target="blank" class="conclusion__balance-hash">d14a028c2a3a2bc9476102bb288234c415a2b01f828ea62ac5b3e42f</a>
            </div>
        </div>

        <div class="wait-palyer">
            <div class="wait-palyer__left">
                <div class="wait-palyer__box">
                    <i class="ic-coin-orange"></i>
                    <div class="wait-palyer__avatar">
                        <img src="img/jackpot-winnter-avatar.png" alt="avatar">
                    </div>
                </div>
                <p class="wait-palyer__nick">Temkin</p>
                <p class="wait-palyer__rate">7, 528 <br><span>COINS</span></p>
                <p class="wait-palyer__tickets">0-50</p>
            </div>
            <div class="wait-palyer__center">
                <span class="wait-palyer__vs">vs</span>
                <p class="wait-palyer__bet">Bank</p>
                <p class="wait-palyer__bet-done">150,056</p>
                <p class="wait-palyer__value">Билеты</p>
                <div class="wait-palyer__timer">
                    <i class="ic-timer"></i>
                    <p class="wait-palyer__timer-text">0:<span id="coinModalTimer6" class="wait-palyer__timer-value">10</span></p>
                </div>
            </div>
            <div class="wait-palyer__right">
                <div class="wait-palyer__box">
                    <i class="ic-coin-blue"></i>
                    <div class="wait-palyer__avatar">
                        <img src="img/jackpot-winnter-avatar.png" alt="avatar">
                    </div>
                </div>
                <p class="wait-palyer__nick">Temkin2</p>
                <p class="wait-palyer__rate">7, 528 <br><span>COINS</span></p>
                <p class="wait-palyer__tickets">50-100</p>
            </div>
        </div>
        <div class="wait-palyer__spinner">
            <p class="wait-palyer__spinner-text">Ожидайте начало игры</p>
            <i class="ic-load-green wait-palyer__spinner-icon"></i>
        </div>
    </div>

    <div class="modal modal--bet modal--done-two" id="waitPlayer8">
        <div class="modal__top">
            <h6 class="modal__title">Coinflip #51285</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Round hash:</p>
                <a href="d14a028c2a3a2bc9476102bb288234c415a2b01f828ea62ac5b3e42f" target="blank" class="conclusion__balance-hash">d14a028c2a3a2bc9476102bb288234c415a2b01f828ea62ac5b3e42f</a>
            </div>
        </div>

        <div class="wait-palyer">
            <div class="wait-palyer__left">
                <div class="wait-palyer__box">
                    <i class="ic-coin-orange"></i>
                    <div class="wait-palyer__avatar">
                        <img src="img/jackpot-winnter-avatar.png" alt="avatar">
                    </div>
                </div>
                <p class="wait-palyer__nick">Temkin</p>
                <p class="wait-palyer__rate">7, 528 <br><span>COINS</span></p>
                <p class="wait-palyer__tickets">0-50</p>
            </div>
            <div class="wait-palyer__center">
                <span class="wait-palyer__vs">vs</span>
                <p class="wait-palyer__bet">Bank</p>
                <p class="wait-palyer__bet-done">150,056</p>
                <p class="wait-palyer__value">Билеты</p>
                <div class="wait-palyer__timer">
                    <i class="ic-timer"></i>
                    <p class="wait-palyer__timer-text">0:<span id="coinModalTimer7" class="wait-palyer__timer-value">10</span></p>
                </div>
            </div>
            <div class="wait-palyer__right">
                <div class="wait-palyer__box">
                    <i class="ic-coin-blue"></i>
                    <div class="wait-palyer__avatar">
                        <img src="img/jackpot-winnter-avatar.png" alt="avatar">
                    </div>
                </div>
                <p class="wait-palyer__nick">Temkin2</p>
                <p class="wait-palyer__rate">7, 528 <br><span>COINS</span></p>
                <p class="wait-palyer__tickets">50-100</p>
            </div>
        </div>
        <div class="wait-palyer__spinner">
            <p class="wait-palyer__spinner-text">Определяется победитель</p>
            <i class="ic-load-green wait-palyer__spinner-icon"></i>
        </div>
    </div>

    <input type="hidden" name="color" value="">


    <div class="wait-palyer__roulette roulette hide">
        <div class="wait-palyer__roulette-slider">
            <div class="wait-palyer__roulette-orange" data-count="1">
                <img src="{{ asset('img/icons/coin-orange.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-blue" data-count="0">
                <img src="{{ asset('img/icons/coin-blue.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-orange" data-count="1">
                <img src="{{ asset('img/icons/coin-orange.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-blue" data-count="0">
                <img src="{{ asset('img/icons/coin-blue.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-orange" data-count="1">
                <img src="{{ asset('img/icons/coin-orange.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-blue" data-count="0">
                <img src="{{ asset('img/icons/coin-blue.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-orange" data-count="1">
                <img src="{{ asset('img/icons/coin-orange.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-blue" data-count="0">
                <img src="{{ asset('img/icons/coin-blue.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-orange" data-count="1">
                <img src="{{ asset('img/icons/coin-orange.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-blue" data-count="0">
                <img src="{{ asset('img/icons/coin-blue.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-orange" data-count="1">
                <img src="{{ asset('img/icons/coin-orange.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-blue" data-count="0">
                <img src="{{ asset('img/icons/coin-blue.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-orange" data-count="1">
                <img src="{{ asset('img/icons/coin-orange.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-blue" data-count="0">
                <img src="{{ asset('img/icons/coin-blue.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-orange" data-count="1">
                <img src="{{ asset('img/icons/coin-orange.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-blue" data-count="0">
                <img src="{{ asset('img/icons/coin-blue.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-orange" data-count="1">
                <img src="{{ asset('img/icons/coin-orange.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-blue" data-count="0">
                <img src="{{ asset('img/icons/coin-blue.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-orange" data-count="1">
                <img src="{{ asset('img/icons/coin-orange.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-blue" data-count="0">
                <img src="{{ asset('img/icons/coin-blue.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-orange" data-count="1">
                <img src="{{ asset('img/icons/coin-orange.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-blue" data-count="0">
                <img src="{{ asset('img/icons/coin-blue.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-orange" data-count="1">
                <img src="{{ asset('img/icons/coin-orange.png') }}" alt="icon">
            </div>
            <div class="wait-palyer__roulette-blue" data-count="0">
                <img src="{{ asset('img/icons/coin-blue.png') }}" alt="icon">
            </div>
        </div>
    </div>
@section('javascript')
    <script>
        $("#createGame, #waitPlayer, #waitPlayer2, #waitPlayer3, #waitPlayer4, #waitPlayer5, #waitPlayer6, #waitPlayer7, #waitPlayer8").iziModal({
            width: 700,
            radius: 6,
            top: 50,
            bodyOverflow: !0,
            overlayColor: "rgba(254,254,254, .9)"
        });

        $(document).on("opened", "#createGame", function (e) {
            o($("#coinModalTimer"))
        }), $(document).on("opened", "#waitPlayer", function (e) {
            o($("#coinModalTimer2"))
        }), $(document).on("opened", "#waitPlayer2", function (e) {
            o($("#coinModalTimer3"))
        }), $(document).on("opened", "#waitPlayer3", function (e) {
            o($("#coinModalTimer4"))
        }), $(document).on("opened", "#waitPlayer5", function (e) {
            i($("#coinModalMiniTimer2")), s($("#coinModalMiniTimer1"), $("#coinModalMiniTimer2"))
        }), $(document).on("opened", "#waitPlayer6", function (e) {
            o($("#coinModalTimer5"))
        }), $(document).on("opened", "#waitPlayer7", function (e) {
            o($("#coinModalTimer6"))
        }), $(document).on("opened", "#waitPlayer8", function (e) {
            o($("#coinModalTimer7"))
        })
    </script>
@endsection
@endsection