<!DOCTYPE html>
<html lang="ru">

<head>

    <meta charset="utf-8">

    @if(isset($pageName))
        <title>Fox - {{ $pageName }}</title>
    @else
        <title>Fox</title>
    @endif
    <meta name="description" content="">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:image" content="path/to/image.jpg">
    <link rel="shortcut icon" href="{{ asset('img/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="img/favicon/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/favicon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/favicon/apple-touch-icon-114x114.png">

    @if(checkSessionTheme())
        <link class="main-css" rel="stylesheet" href="{{ asset('css/main-dark.min.css') }}">
    @else
        <link class="main-css" rel="stylesheet" href="{{ asset('css/main.min.css') }}">
    @endif

</head>

    @yield('content')

<!-- Include css and js -->

@yield('javascript')

<script src="{{ asset('js/script.js') }}"></script>

<script>
    let counter = 0;
    @if(auth()->check())
         $(document).ready(function() {
            window.Echo.join('online-users')
                .listen('StartGame', function(e) {
                    console.log('StartGame');
                    let currentGameId = $('input[name="gameId"]').val();

                    if(currentGameId && currentGameId == e.gameId) {
                        MainFunction.startTimer(e.endGame, 'callbackJackpot');
                        $('.jackpot-timer').removeClass('hide');
                        $('.message-start-game').removeClass('hide');
                        $('.choose-winner__game').text(e.gameTypeName + ' #' + e.gameId);
                        $('.jackpot-history__hash a').attr('href', 'http://sha224.net/?val=' + e.hashGame).text(e.hashGame);
            
                    }

                })
                .listen('EndGame', function(e) {
                    console.log('EndGame');
                    console.log(JSON.stringify(e));
                    let currentGameId = $('input[name="gameId"]').val();

                    if(currentGameId && currentGameId == e.gameId) {
                        setTimeout(function() {
                            MainFunction.lotto(e.winnerId, 5, 1, 1, -115, 15, 'showWinnerJackpot', e, 'choose-winner__slider');
                        }, 100);
                    }

                    $('body').find('.cash-coins').val(300);


                    var account = $('input[name="time"]').val();

                    if(account == e.accountId) {
                        $('.balance__value').html(e.balanceUser.toLocaleString() + '<span>COINS</span>');
                    }


                })
                .listen('AddParticipant', function(e) {
                    console.log('AddParticipant: ' + JSON.stringify(e));
                    let currentGameId = $('input[name="gameId"]').val();

                    if(currentGameId && currentGameId != e.gameId) {
                        return false;
                    }

                  
                    if(e.type == 'jackpot') {
                        $('.bank__value').html(e.bank.toLocaleString() + '<span>COINS</span>');
                        $('body').find('.winners__slider').html(e.view);
                        $('body').find('.wrap-choose-winner-slider').html(e.winners);
                        $('.bank__players').text(e.countParticipants + ' игроков');
                        $('.winners__game').text(e.gameTypeName + ' #' + e.gameId);
                        $('.jackpot-history__hash a').text(e.hashGame);
                        $('input[name="gameId"]').val(e.gameId);
                        $('body').find('.winners__slider').removeClass('slick-initialized');
                        $('body').find('.winners__slider').removeClass('slick-slider');
                       $(".winners__slider").slick({
            slidesToShow: 7,
            draggable:false,
            infinite: !0,
            slidesToScroll: 1,
            centerMode: true,
            responsive: [{breakpoint: 1400, settings: {slidesToShow: 6}}, {
                breakpoint: 1280,
                settings: {slidesToShow: 5}
            }, {breakpoint: 1120, settings: {slidesToShow: 4}}, {
                breakpoint: 1024,
                settings: {slidesToShow: 2}
            }, {breakpoint: 370, settings: {slidesToShow: 2, arrows: !1}}]
        });
                        if(e.isReload) {
                            $('#jackpotTimer').text('00:20');
                            $('.jackpot-timer__left, .jackpot-timer__right').css({'width': 100 + '%'});
                        }

                    }

                })
                .listen('AddParticipantKing', function (e) {
                    console.log('AddParticipant');

                    if(e.type == 3) {

                        $('.game__participants_classic .slick-track').append(e.view);
                        var wrap = $('.game-classic');
                        wrap.find('.game__bank-value').text(e.bank);
                        wrap.find('.game__winner img').attr('src', e.image).removeClass('hide');
                        wrap.find('.game__time-link').html('ВНЕСТИ <span>'+ (+e.step + 10) +'</span> COINS');
                        wrap.find('.game__title').text('Classic #' + e.gameId);
//
                        if(e.countParticipants > 6) {
                            var count = (e.countParticipants - 6) * 78 - 78;
                            $('.game__participants_classic .slick-track').css({'transform': 'translate3d(-'+ count +'px, 0px, 0px)'});
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

                    if(e.type == 4) {

                        var wrap = $('.game-senyor');
                        $('.game__participants_senyor .slick-track').append(e.view);
                        wrap.find('.game__bank-value').text(e.bank);
                        wrap.find('.game__winner img').attr('src', e.image).removeClass('hide');
                        wrap.find('.game__time-link').html('ВНЕСТИ <span>'+ (+e.step + 1) +'</span> COINS');
                        $('.conclusion__balance-value').html((+e.step + 1) + '<span>COINS</span>');
                        wrap.find('.game__title').text('Senyor #' + e.gameId);
                        $('.cash-coins').val((+e.step + 1));

                        if(e.countParticipants > 6) {
                            var count = (e.countParticipants - 7) * 78;
                            $('.game__participants_senyor .slick-track').css({'transform': 'translate3d(-'+ count +'px, 0px, 0px)'});
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
                    MainFunction.startTimerKing(e.endGameAt, 'callbackKing', e.type);
                })
                .listen('EndGameKing', function(e) {
                    if(e) {
                        MainFunction.endGameKing(e);
                    }
                })
                .listen('SendMessage', function(e) {
                    $('.chat__body-wrap .nano-content').append(e.view);
                    var block = $(".nano-content");
                    block.scrollTop(99999999999999);
                })
                .listen('CreateGameCoinFlip', function (e) {

                    $('.wrap-coinflip').prepend(e.view);

                    $('.coin-history__left .coin-history-box__value--orange').html(e.allCash.toLocaleString() + '<span> COINS</span>');
                    $('.coin-history__left .coin-history-box__col--center .coin-history-box__value').text(e.allGame);
                    $('.coin-history__left .coin-history-box__col--last .coin-history-box__value').text(e.allGameWait);

                })
                .listen('StartGameCoinflip', function(e) {
                    if(e.gameId) {
                        $('body').find('.coin-game[data-game-id="'+ e.gameId +'"]').replaceWith(e.view);
                        var popup = $('body').find('.iziModal-wrap[data-game-id="'+ e.gameId +'"]');

                        if(popup) {
                            popup.replaceWith(e.viewPopup);
                            //MainFunction.startTimerCoinflip(e.gameId, 20, e.winnerName, e.color, e.cash);
                        }
                    }

                    $('.coin-history__left .coin-history-box__value--orange').html(e.allCash.toLocaleString() + '<span> COINS</span>');
                    $('.coin-history__left .coin-history-box__col--center .coin-history-box__value').text(e.allGame);
                    $('.coin-history__left .coin-history-box__col--last .coin-history-box__value').text(e.allGameWait);


                })
                .listen('EndGameCoinflip', function(e) {
                    $('body').find('.coin-game[data-game-id="'+ e.gameId +'"]').remove();
                    $('.coin-game-history').prepend(e.viewHistory);

                    var account = $('input[name="time"]').val();

                    if(account == e.accountId) {
                        $('.balance__value').html(e.balance.toLocaleString() + '<span>COINS</span>');
                    }

                    $('.coin-history__left .coin-history-box__value--orange').html(e.allCash.toLocaleString() + '<span> COINS</span>');
                    $('.coin-history__left .coin-history-box__col--center .coin-history-box__value').text(e.allGame);
                    $('.coin-history__left .coin-history-box__col--last .coin-history-box__value').text(e.allGameWait);

                })
                .here(function(users) {
                    counter = users.length;
                    $('.ic-user').closest('.social-menu__link').find('span').text(counter);
                })
                .joining(function(user) {
                    counter++;
                    $('.ic-user').closest('.social-menu__link').find('span').text(counter);
                })
                .leaving(function(user) {
                    counter--;
                    $('.ic-user').closest('.social-menu__link').find('span').text(counter);
                });
    });


    @endif



</script>

<div class="form-payment" style="display:none;">
    <input type="hidden" name="time" value="{{ auth()->user() ? auth()->user()->id : '' }}">
</div>
<a href="//www.free-kassa.ru/" style="display: none"><img src="/img/icons/free-kassa.png"></a>
<a href="https://www.fkwallet.ru" style="display: none"><img src="/img/icons/iconsmall_wallet8.png" title="Прием криптовалют"></a>
</body>
</html>
