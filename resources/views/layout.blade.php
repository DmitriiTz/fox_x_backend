<!DOCTYPE html>
<html lang="ru">

<head>

    <meta charset="utf-8">

    @if(isset($pageName))
        <title>Fox - {{ $pageName }}</title>
    @else
        <title>fox-x - официальный сайт jackpot , coinflip, King of the Hill</title>
    @endif
    <meta name="description"
          content="fox-x  или фокс икс официальный сайт, онлайн рулетка на реальные деньги c бесплатными промокодами на каждые 24 часа. Вывод на любые платежные системы без комиссии.Лучший заработок в интернете 2019 год">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="//fox-x.ru/"/>
    <meta property="og:title" content="Официальный сайт Фокс икс">
    <meta property="og:description" content="Испытай удачу !"/>
    <meta property="og:site_name" content="FOX-X"/>
    <meta property="og:image" content="//fox-x.ru/img/fox_prewiev_vk.jpg"/>
    <meta property="og:image:type" content="image/jpeg"/>

    <link rel="shortcut icon" href="{{ asset('img/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="img/favicon/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/favicon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/favicon/apple-touch-icon-114x114.png">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    @if(checkSessionTheme())
        <link class="main-css" rel="stylesheet" href="{{ asset('css/main-dark.min.css') }}">
    @else
        <link class="main-css" rel="stylesheet" href="{{ asset('css/main.min.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('css/css.css') }}">

</head>
<body @if(checkSessionTheme()) class="body--dark" @endif>
@include('blocks.header')

<!-- Include css and js -->

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/scripts.min.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script>
    let counter = 0;
    @if(auth()->check())
    $(document).ready(function () {
        window.Echo.join('online-users')
            .listen('StartGame', function (e) {

                console.log('StartGame' + JSON.stringify(e));
                let currentGameId = $('input[name="gameId"]').val();

                if (currentGameId && currentGameId == e.gameId) {
                    MainFunction.startTimer(35, e.endGame, 'callbackJackpot');
                    $('.jackpot-timer.first').removeClass('hide');
                    $('.message-start-game').removeClass('hide');
                    $('.choose-winner__game').text(e.gameTypeName + ' #' + e.gameId);
                    $('.jackpot-history__hash a').text(e.hashGame);

                }

            })
            .listen('EndGameTimer', function (e) {
                console.log('EndGameTimer: ' + JSON.stringify(e))
                MainFunction.startTimer(14, e.timer, '');
                MainFunction.checkStatus(e);
            })
            .listen('EndGame', function (e) {
                console.log('EndGame');


                let currentGameId = $('input[name="gameId"]').val();

                if (currentGameId && currentGameId == e.gameId) {
                    setTimeout(function () {
                        MainFunction.lotto(e.winnerId, 5, 1, 1, -115, 10, 'showWinnerJackpot', e, e.winners, 'choose-winner__slider');
                    }, 100);
                    setTimeout(function () {
                        $('.jackpot-timer__left.first span').css({width: 100 + '%'});
                        $('.jackpot-timer__right.first span').css({width: 100 + '%'});
                        // MainFunction.startTimer(13,'');
                    }, 1000);
                }

                $('body').find('.cash-coins').val(300);


                var account = $('input[name="time"]').val();

                if (account == e.accountId) {
                    $('.balance__value').html(e.balanceUser.toLocaleString() + '<span>COINS</span>');
                }


            })
            .listen('AddParticipant', function (e) {
                console.log('AddParticipant: ' + JSON.stringify(e));
                let currentGameId = $('input[name="gameId"]').val();

                if (currentGameId && currentGameId != e.gameId) {
                    return false;
                }


                if (e.type == 'jackpot') {
                    $('.bank__value').html(e.bank.toLocaleString() + '<span>COINS</span>');
                    $('body').find('.winners__slider').html(e.view);
                    $('body').find('.wrap-choose-winner-slider').html(e.winners);
                    $('.bank__players').text(e.countParticipants + ' игроков');
                    $('.winners__game').text(e.gameTypeName + ' #' + e.gameId);
                    $('.jackpot-history__hash a').text(e.hashGame);
                    $('input[name="gameId"]').val(e.gameId);
                    $('body').find('.winners__slider').removeClass('slick-initialized');
                    $('body').find('.winners__slider').removeClass('slick-slider');
                    $('.jackpot-history__list.bets').prepend(e.bet);
                    $(".winners__slider").slick({
                        slidesToShow: 7,
                        draggable: false,
                        infinite: !0,
                        slidesToScroll: 1,
                        centerMode: false,
                        responsive: [{breakpoint: 1400, settings: {slidesToShow: 6}}, {
                            breakpoint: 1280,
                            settings: {slidesToShow: 5}
                        }, {breakpoint: 1120, settings: {slidesToShow: 4}}, {
                            breakpoint: 1024,
                            settings: {slidesToShow: 2}
                        }, {breakpoint: 370, settings: {slidesToShow: 2, arrows: !1}}]
                    });
                    var element = 'choose-winner__slider';
                    var el = $('<html></html>');
                    el.html(e.winners);
                    var users = [];
                    // var users = [];
                    el.find('.choose-winner__slider-item').each(function () {
                        let count = parseInt($(this).find('img').data('percent') * 1.3 / 14);
                        console.log(count);
                        for (i = 0; i < count; i++) {
                            users.push(this);
                        }
                    });
                    // console.log(users);
                    //  users = mulAndShuffle(users, Math.ceil(130 / users.length));
                    var shuffle = function (array) {

                        var currentIndex = array.length;
                        var temporaryValue, randomIndex;

                        // While there remain elements to shuffle...
                        while (0 !== currentIndex) {
                            // Pick a remaining element...
                            randomIndex = Math.floor(Math.random() * currentIndex);
                            currentIndex -= 1;

                            // And swap it with the current element.
                            temporaryValue = array[currentIndex];
                            array[currentIndex] = array[randomIndex];
                            array[randomIndex] = temporaryValue;
                        }

                        return array;

                    };
                    users = shuffle(users);
                    var html = '';
                    //   console.log(tempEl);
                    $('body').find('.' + element).html('');
                    users.forEach(function (item, i, arr) {
                        console.log(item);
                        $('body').find('.' + element).append($(item).clone());

                    });
                    if (e.isReload) {
                        $('#jackpotTimer').text('35');
                        $('.jackpot-timer__left.first').css({'width': 100 + '%'});
                        $('.jackpot-timer__right.first').css({'width': 100 + '%'});
                    }

                }

            })
            .listen('AddParticipantKing', function (e) {
                console.log('AddParticipantKing' + JSON.stringify(e));
                $('#bedBet').attr('onclick', '');
                setTimeout(function () {
                    $('#bedBet').attr('onclick', "MainFunction.createGameKing('classic');");
                }, 300);
                // e.view = '<li class="game__participants-item slick-slide"><img src="' + e.image + '" alt="avatar"></li>';
                if (e.type == 3) {


                    $('.game__participants_classic .slick-track').prepend(e.view);
                    var wrap = $('.game-classic');
                    wrap.find('.game__bank-value').text(e.bank);
                    wrap.find('.game__winner img').attr('src', e.image).removeClass('hide');
                    wrap.find('.game__time-link').html('ВНЕСТИ <span>' + (+e.step + 10) + '</span> COINS');
                    wrap.find('.game__title').text('Classic #' + e.gameId);
//
                    if (e.countParticipants > 6) {
                        var count = (e.countParticipants - 6) * 78 - 78;
                        $('.game__participants_classic .slick-track').css({'transform': 'translate3d(-' + count + 'px, 0px, 0px)'});
                    }
//


                    $(".game__participants_classic").slick({
                        infinite: false,
                        slidesToShow: 324,
                        slidesToScroll: 1,
                        arrows: !1,
                        centerMode: false,
                        variableWidth: !0,
                        responsive: [{breakpoint: 1640, settings: {slidesToShow: 3, slidesToScroll: 1}}, {
                            breakpoint: 1400,
                            settings: {slidesToShow: 2, slidesToScroll: 1, centerMode: !1}
                        }]
                    });


                }

                if (e.type == 4) {

                    $('.game__participants_senyor .slick-track').prepend(e.view);
                    var wrap = $('.game-senyor');
                    wrap.find('.game__bank-value').text(e.bank);
                    wrap.find('.game__winner img').attr('src', e.image).removeClass('hide');
                    wrap.find('.game__time-link').html('ВНЕСТИ <span>' + (+e.step + 1) + '</span> COINS');
                    $('.conclusion__balance-value').html((+e.step + 1) + '<span>COINS</span>');
                    wrap.find('.game__title').text('Senyor #' + e.gameId);
                    $('.cash-coins').val((+e.step + 1));

                    if (e.countParticipants > 6) {
                        var count = (e.countParticipants - 7) * 78;
                        $('.game__participants_senyor .slick-track').css({'transform': 'translate3d(-' + count + 'px, 0px, 0px)'});
                    }


                    $(".game__participants_senyor").slick({
                        infinite: false,
                        slidesToShow: 324,
                        slidesToScroll: 1,
                        arrows: !1,
                        centerMode: !0,
                        variableWidth: !0,
                        responsive: [{breakpoint: 1640, settings: {slidesToShow: 3, slidesToScroll: 1}}, {
                            breakpoint: 1400,
                            settings: {slidesToShow: 2, slidesToScroll: 1, centerMode: !1}
                        }]
                    });

                }


            })
            .listen('StartGameKing', function (e) {
                console.log('StartGameKing' + JSON.stringify(e));
                if (e.type == 3) {
                    console.log(e.view);
                    if (e.endGameAt % 2 == 0 && e.view != '') {
                        $('.game__participants_classic .slick-track').html(e.view);
                    }
                    var wrap = $('.game-classic');
                    wrap.find('.game__winner img').attr('src', e.image).removeClass('hide');

                }
                if (e.type == 4) {
                    if (e.endGameAt % 2 == 0 && e.view != '') {
                        $('.game__participants_senyor .slick-track').html(e.view);
                    }
                    var wrap = $('.game-senyor');
                    wrap.find('.game__winner img').attr('src', e.image).removeClass('hide');
                }

                MainFunction.startTimerKing(e.endGameAt, 'callbackKing', e.type);
            })
            .listen('EndGameKing', function (e) {
                console.log('EndGameKing');

                if (e) {
                    MainFunction.endGameKing(e);
                }
            })
            .listen('EndBettingGameKing', function (e) {
                console.log('EndBettingGameKing');
                $('#bedBet').attr('onclick', '');
            })
            .listen('SendMessage', function (e) {
                $('.chat__body-wrap .nano-content').append(e.view);
                var block = $(".chat .nano-content");
                block.scrollTop(99999999999999);
            })
            .listen('CreateGameCoinFlip', function (e) {

                $('.wrap-coinflip').prepend(e.view);

                $('.coin-history__left .coin-history-box__value--orange').html(e.allCash.toLocaleString() + '<span> COINS</span>');
                $('.coin-history__left .coin-history-box__col--center .coin-history-box__value').text(e.allGame);
                $('.coin-history__left .coin-history-box__col--last .coin-history-box__value').text(e.allGameWait);

            })

            .listen('StartGameCoinflip', function (e) {
                console.log('StartGameCoinflip');
                if (e.gameId) {
                    $('body').find('.coin-game[data-game-id="' + e.gameId + '"]').replaceWith(e.view);
                    var popup = $('body').find('.iziModal-wrap[data-game-id="' + e.gameId + '"]');

                    if (popup) {
                        popup.replaceWith(e.viewPopup);
                        //MainFunction.startTimerCoinflip(e.gameId, 20, e.winnerName, e.color, e.cash);
                    }
                }

                $('.coin-history__left .coin-history-box__value--orange').html(e.allCash.toLocaleString() + '<span> COINS</span>');
                $('.coin-history__left .coin-history-box__col--center .coin-history-box__value').text(e.allGame);
                $('.coin-history__left .coin-history-box__col--last .coin-history-box__value').text(e.allGameWait);


            })
            .listen('EndGameCoinflip', function (e) {
                console.log('EndGameCoinflip');


                var account = $('input[name="time"]').val();

                setTimeout(function () {
                    $('body').find('.coin-game[data-game-id="' + e.gameId + '"]').remove();
                    $('.coin-game-history').prepend(e.viewHistory);
                    if (account == e.accountId) {

                        $('.balance__value').html(e.balance.toLocaleString() + '<span>COINS</span>');

                    }
                }, 8000);


                $('.coin-history__left .coin-history-box__value--orange').html(e.allCash.toLocaleString() + '<span> COINS</span>');
                $('.coin-history__left .coin-history-box__col--center .coin-history-box__value').text(e.allGame);
                $('.coin-history__left .coin-history-box__col--last .coin-history-box__value').text(e.allGameWait);

            })
            .here(function (users) {
                counter = users.length;
                $('.ic-user').closest('.social-menu__link').find('span').text(counter);
            })
            .joining(function (user) {
                counter++;
                $('.ic-user').closest('.social-menu__link').find('span').text(counter);
            })
            .leaving(function (user) {
                counter--;
                $('.ic-user').closest('.social-menu__link').find('span').text(counter);
            });
    });
    @endif
</script>

@yield('content')

<div class="form-payment" style="display:none;">
    <input type="hidden" name="time" value="{{ auth()->user() ? auth()->user()->id : '' }}">
</div>
<a href="//www.free-kassa.ru/" style="display: none"><img src="/img/icons/free-kassa.png"></a>
<a href="//www.fkwallet.ru" style="display: none"><img src="/img/icons/iconsmall_wallet8.png"
                                                       title="Прием криптовалют"></a>
</body>
</html>
