@extends('layout')

@section('content')
    <!-- Start section -->
    <style>
        .slick-track {
            width: 100000px !important;
            transform: translate3d(0px, 0px, 0px) !important;
        }
    </style>
    <?php
        if(auth()->user() && auth()->user()->id != 4){
            $auser = Auth::user()->id;
            if(getBalance(auth()->user()) > 10){
                $btn_bets = 'bet';
            }else{
                $btn_bets = '';
            }
            $bl = '<input type="hidden" id="balance_bet" value="'.getBalance(auth()->user()).'">';
        }else{
            $auser = 0;
            $btn_bets = '';
            $bl = '';
        }
    ?>
    <main id="app" class="main crash-main">
        <div class="main__left">

            <div class="controls-panel-crash">
                <form method="POST" id="bet_form">
                    @csrf
                    <div class="block-bet">
                        <div class="crash-bet-title">Введите Вашу ставку:</div>

                        <div class="block-bet-val">
                            <div class="clear-bet-name clear-bet">Сброс</div>
                            <div class="clear-bet-val {{ $btn_bets }}">
                                <ul>
                                    <li data-action="+10">+10</li>
                                    <li data-action="+50">+50</li>
                                    <li data-action="+100">+100</li>
                                    <li data-action="+500">+500</li>
                                    <li data-action="+1000">+1000</li>
                                    <li data-action="half">1/2</li>
                                    <li data-action="X2">2X</li>
                                    <li data-action="%10">10%</li>
                                    <li data-action="%25">25%</li>
                                    <li data-action="%50">50%</li>
                                    <li data-action="max">MAX</li>
                                </ul>
                            </div> 
                        </div>
                        {!! $bl !!}
                        <input type="number" name="bet" id="bet" value="0" autocomplete="off" placeholder="Ваша ставка" step="any" min="0">
                    </div>

                    <div class="block-bet top">
                        <div class="crash-bet-title">Вывод денег:</div>

                        <div class="block-bet-val">
                            <div class="clear-bet-name" id="clear_cashout">Сброс</div>
                            <div class="clear-bet-val cashout">
                                <ul>
                                    <li data-action="1">MIN</li>
                                    <li data-action="+.1">+0.1</li>
                                    <li data-action="+.5">+0.5</li>
                                    <li data-action="+1.00">+1</li>
                                    <li data-action="-.1">-0.1</li>
                                    <li data-action="-.5">-0.5</li>
                                    <li data-action="-1.00">-1</li>
                                    <li data-action="X2">2X</li>
                                    <li data-action="X5">5X</li>
                                    <li data-action="X10">10X</li>
                                </ul>
                            </div> 
                        </div>

                        <input type="number" name="cashout" id="cashout" placeholder="Автовывод" value="0"
                               autocomplete="off" step="0.01"
                               min="0" onkeypress="doubleValidate(this)">

                        <script>
                            function doubleValidate(input) {
                                input.value = input.value.replace(/[,]/g, ".");
                            }
                        </script>
                    </div>

                    @if(auth()->user() && auth()->user()->id != 4)
                        <button class="button button--gradient" id="dp_bet" type="submit" disabled="true">Сделать ставку</button>
                    @else
                        <a class="button button--gradient bank__button" data-izimodal-open="#auth" data-izimodal-transitionin="fadeInDown">СДЕЛАТЬ СТАВКУ</a>
                    @endif
                </form>
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

        </div>
        <div class="main__center main__center--crash " id="crash_block">

            <div class="crash-top-bar">
                <a href="javascript:void(0);" data-izimodal-open="#historyCrash" data-izimodal-transitionin="fadeInDown" class="bank__button"><img src="/img/Vector.png"></a>
                <ul>
                    @foreach($games as $gf)
                        <?php
                            $g = $gf['game'];

                            if($g->profit < 3){
                                $color = 1;
                            }else if($g->profit > 2 AND $g->profit < 4){
                                $color = 2;
                            }else if($g->profit > 3 AND $g->profit < 50){
                                $color = 3;
                            }else if($g->profit > 49 AND $g->profit < 100){
                                $color = 4;
                            }else if($g->profit > 99){
                                $color = 5;
                            }
                        ?>
                        <li class="color-{{$color}}">
                            <a href="javascript:void(0);" data-info="{{$g->profit}}"
                               data-izimodal-open="#historyGame_{{$g->id}}" data-izimodal-transitionin="fadeInDown">
                                {{$g->profit}}
                                <span class="tooltiptext">
                                    ROUND: {{$g->id}}<br>
                                    HASH: {{ hash("sha224", $g->profit, FALSE) }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div id="s_g"></div>


            <div class="canvas-crash-block">
                <canvas id="crashGraphic" height="450"></canvas>
                <div class="flex-container-round">
                    <div class="crash-round">
                        Общие ставки: <span class="person-dp-sp"><i class="material-icons">person</i> <span id="users_count_bet" style="margin-left: 0;">{{ count($bets) }}</span>
                        <span><img src="/img/Vector2.png"> <span id="users_total_bet" style="margin-left: 0;">{{ $price }}</span></span></span>
                    </div>
                    <div class="crash-round sp">
                        Общие выигрыши: <span><img src="/img/Vector2.png"> <span id="users_count_bet_win">0</span>
                    </div>
                </div>
            </div>

            @include('crash-ubets', [
                'ubets' => $ubets,
            ])

            <input type="hidden" id="auser" value="{{$auser}}">
            
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

    <script src="{{ asset('js/main.js') }}"></script>
    <script type="text/javascript">
        @if($game->create_game >= 0)

        @endif

        var i_int = parseInt(<?= time() - $game->create_game; ?>) + 1;
        var curent = <?= time(); ?>;
        var n = <?= $game->number ?>;
        console.log('game_number: ', n);

        if(i_int == 1){
            var stop = <?= $game->stop_game - $game->number; ?>;
        }else{
            var stop = <?= $game->stop_game - $game->number + 5; ?>;
        }

        function intervalGr()
        {
            // console.log('i_int', i_int, ' n ', n, ' stop', stop);
            if(i_int >= n){
                clearInterval(intervalID);
                var x = parseInt(stop) - parseInt(curent);

                console.log(x + ' - x');

                var tm;
                if(x < 0){
                    tm = 17;
                }else{
                    tm = x;
                }

                if(tm > 10){
                    var res = parseInt(tm) - 10;
                    var timer = res * 1000;
                    console.log(timer + ' - timer');
                    crushGraph(i_int, timer);
                }else if(x <= 10){
                    startCrashTimer(i_int, tm);
                }

            }
            else{

                initGraph(i_int);
                i_int = i_int + 0.025; 
            }                   
        }
        var intervalID = setInterval(intervalGr, 25);


        function gameInfoInterval(){
            var rand_number = $('body').data('coeff');
            var profit = $('body').data('profit');

            if(rand_number > 0) {
                $.ajax({
                    type: 'GET',
                    url: '/crash/get-info?rand_number=' + rand_number,
                    success: function(result){
                        $('body').data('profit', result.game.profit).attr('data-profit', result.game.profit);
                        // console.log('Game info: ', result);
                        console.log(rand_number, result.game.profit);
                        console.log('game_id', result.game.id);
                        // console.log('rand_number - ' + result.rand_number);
                        if(rand_number >= result.game.profit || rand_number >= 999) {
                            $('body').data('coeff', '0.00').attr('data-coeff', '0.00');
                            admGameStop(rand_number);
                            gameInfoInterval();
                            console.log('График остановлен на ' + rand_number + ' (' + result.game.profit + ')');
                        }
                        $('.crash-player-table').replaceWith(result.ubets);

                        if(rand_number > 0 && $('#dp_bet').prop( "disabled", true )) {
                            $('#dp_bet').attr('disabled', false);
                        }

                    }
                });
            }
        }
        var int = setInterval(gameInfoInterval, 500);

        function admGameStop(profit){
            clearInterval(intervalID);
            crushGraph(i_int, 7000);
            var coeff = drawY.toFixed(2);
            if(coeff > 999) coeff = 999;
            if(profit) coeff = profit;
            $.ajax({
                type: 'GET',
                url: '/crash/set-current-profit?profit=' + profit,
                success: function (result) {
                    console.log('stop profit', result.profit, coeff);
                    // $('body').data('profit', 0).attr('data-profit', 0);
                },
                error: function (result) {
                    console.log('error', result);
                }
            });

            console.log('stop',i_int,intervalID, coeff);
        }

    </script>

    <div class="modal modal--help" id="historyCrash">
        <div class="modal__top">
            <h6 class="modal__title"><img src="/img/Vector.png"> История</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="modal-help nano">
                <div class="nano-content rules-of-the-game modal-table">                
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Номер игры</th>
                                <th>Коэффициент</th>
                                <th>Дата</th>
                                <th>Hash</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($games as $gt)
                                <?php
                                    $g = $gt['game'];

                                    if($g->profit < 3){
                                        $color = 1;
                                    }else if($g->profit > 2 AND $g->profit < 4){
                                        $color = 2;
                                    }else if($g->profit > 3 AND $g->profit < 50){
                                        $color = 3;
                                    }else if($g->profit > 49 AND $g->profit < 100){
                                        $color = 4;
                                    }else if($g->profit > 99){
                                        $color = 5;
                                    }
                                ?>
                                <tr>
                                    <th scope="row" class="link-info-game-modal">
                                        <a href="javascript:void(0);" data-info="{{$g->profit}}" data-izimodal-open="#historyGame_{{$g->id}}" data-izimodal-transitionin="fadeInDown">{{$g->id}}</a>
                                    </th>
                                    <td class="text-color-{{$color}}" scope="row">{{$g->profit}}x</td>
                                    <td scope="row">{{date('d/m/Y H:i', $g->create_game)}}</td>
                                    <td scope="row">{{ hash("sha224", $g->profit, FALSE) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @foreach($games as $modal_game)
        <?php
            $_game = $modal_game['game'];
            $_bets = $modal_game['bets'];
        ?>
        <div class="modal modal--help info-game-crash" id="historyGame_{{$_game->id}}">
            <div class="modal__top">
                <h6 class="modal__title">CRASH: {{$_game->id}}</h6>
                <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
            </div>
            <div class="modal__body">
                <div class="modal-help nano">
                    <div class="nano-content rules-of-the-game modal-table mb prm">
                        <div class="info-bl-game">
                            <div class="list-info">Хэш: {{ hash("sha224", $_game->profit, FALSE) }} <a href="http://sha224.net/?val={{ $_game->profit }}" target="_blank">Проверить</a></div>
                            <div class="list-info">Номер игры: {{$_game->id}}</div>
                            <div class="list-info">Дата: {{date('d/m/Y H:i', $_game->create_game)}}</div>
                            <div class="list-info">Коэффициент: {{$_game->profit}}x</div>
                        </div>
                    </div>
                    <div class="nano-content rules-of-the-game modal-table prm">
                        @if(!$_bets->isEmpty())
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Игрок</th>
                                        <th>Ставка</th>
                                        <th>Коэффициент</th>
                                        <th>Выигрыш</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($_bets as $bet)
                                        <?php
                                            $user = DB::table('users')->where('id', $bet->user_id)->first();

                                            if($_game->profit > $bet->number){
                                                $color = '#7DD358';
                                                $win = '+' . $bet->number * $bet->price - $bet->price;
                                            }else{
                                                $color = '#E95E3F';
                                                $win = '-';
                                            }
                                        ?>
                                        <tr style="color: {{$color}}">
                                            <th scope="row" class="link-info-game-modal">
                                                {{$user->name}}
                                            </th>
                                            <td scope="row">{{$bet->price}}</td>
                                            <td scope="row">{{$bet->number}}</td>
                                            <td scope="row">{{$win}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div style="text-align: center;">
                                Никто не играл
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @include('account.blocks.popups')

@endsection
