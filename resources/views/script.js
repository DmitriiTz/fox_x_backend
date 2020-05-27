var MainFunction = {

    setIntervalId: false,
    setIntervalKingId3: false,
    setIntervalKingId4: false,

    saveUser: function () {
        $('#table1-body input[name="user[]"]:checked').each(function (indx, element) {
            var el = $(element);

            var id = el.val();
            var role = el.closest('tr').find('select[name="role_id"]').val();
            var ref = el.closest('tr').find('select[name="is_referral_power"]').val();
            var blocked = el.closest('tr').find('select[name="is_blocked"]').val();

            $.ajax({
                type: 'post',
                url: '/admin/save-ajax-info',
                data: {id: id, role: role, ref: ref, blocked: blocked, '_token': $('meta[name="csrf-token"]').attr('content')},
                success: function (result) {
                    if (indx == $('#table1-body input[name="user[]"]:checked').length - 1) {
                        setTimeout(function () {
                            location.reload();
                        }, 200);
                    }
                }
            });

        });
    },


    checkAuthUser: function (element) {
        var el = $(element);

        var gameId = el.closest('.coin-game').attr('data-game-id');

        if (gameId) {
            $.ajax({
                type: 'post',
                url: '/check-auth-user',
                data: {'_token': $('meta[name="csrf-token"]').attr('content'), gameId: gameId},
                success: function (result) {
                    if (result == 1) {
                        $('#auth').iziModal('open');
                    }

                    if (result == 2 || result == 3) {
                        el.closest('.coin-game').find('.coin-game__eye a')[0].click();
                    }

                    if (result == 4) {
                        $('#inputModal').iziModal('open');
                    }

                    if (result == 5) {
                        MainFunction.setParticipateCoinflip(element);
                    }

                }
            });
        }


    },

    selectColor: function (color) {
        $('input[name="color"]').val(color);
        if (color == 'blue') {
            $('#betModalCoin .bet-avatar__wrap i').removeClass('ic-coin-orange').addClass('ic-coin-blue');
        } else {
            $('#betModalCoin .bet-avatar__wrap i').removeClass('ic-coin-blue').addClass('ic-coin-orange');
        }
    },

    registration: function (element, e) {
        e.preventDefault();
        var el = $(element);
        var data = el.closest('form').serialize();

        $.ajax({
            type: 'post',
            url: '/registration',
            data: data,
            success: function (result) {
                if (result.error) {
                    $('#authRegistration').html(result.view);
                } else {
                    location.reload();
                }
            }
        });

    },

    saveStatusUser: function () {

        var arr = [];
        $('.table-checkbox input:checked').each(function (indx, element) {
            if ($(element).val() != 'on') {
                arr.push($(element).val());
            }

        });

        var statusId = $('.selectric-wrapper:first').find('select').val();

        if (arr && statusId) {
            $.ajax({
                type: 'post',
                url: '/admin/change-status',
                data: {'_token': $('meta[name="csrf-token"]').attr('content'), arr: arr, statusId: statusId},
                success: function (result) {
                    location.reload();
                }
            });
        }


    },

    authUser: function (element, e) {
        e.preventDefault();

        var el = $(element);
        var data = el.closest('form').serialize();

        $.ajax({
            type: 'post',
            url: '/auth-user',
            data: data,
            success: function (result) {
                if (result.error) {
                    $('#authRegistration').html(result.view);
                } else {
                    location.reload();
                }
            }
        });

    },

    coinflipIntervalId: false,

    startTimerCoinflip: function (gameId, duration, name, color, cash) {

        clearInterval(MainFunction.coinflipIntervalId);
        var timer = duration,
            minutes, seconds;
        var popup = $('body').find('.iziModal-wrap[data-game-id="' + gameId + '"]');

        if (popup) {
            MainFunction.coinflipIntervalId = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                popup.find('.wait-palyer__timer-value').text(seconds);

                if (--timer < 0) {
                    timer = duration;
                }

                if (timer == 0) {

                    setTimeout(function () {
                        popup.find('.wait-palyer__timer-value').text('00');
                        popup.find('.coin-create__center').replaceWith($('.roulette').clone().removeClass('hide'));
                        MainFunction.showWinnerCoinflip(popup, name, color, cash);

                    }, 1000);
                    clearInterval(MainFunction.coinflipIntervalId);

                }

            }, 1000);
        }

    },


    showWinnerCoinflip: function (popup, name, color, cash) {

        var n = +color;
        r = 10;
        var carusel = popup.find('.wait-palyer__roulette-slider .slick-track');
        popup.find('.wait-palyer__roulette-circle').removeClass('hide');

        data = +carusel.find('div:first').data('count');

        var getWinCoin = carusel.find('div:eq(49)');

        if (getWinCoin.data('count') == n) {
            var coord = -3340;
            carusel.find('div:eq(49)').attr('win', 'true');
        } else {
            var coord = -3270;
            carusel.find('div:eq(48)').attr('win', 'true');
        }


        carusel.css('transform', 'translate3d(0px, 0px, 0px)');

        function go() {
            carusel.animate({marginTop: coord}, {
                duration: 1000 * 10,
                easing: 'easeOutCubic',
                step: function (now, fx) {
                    now = -now;
                    if (now < 50) {
                        var coinID = 1;
                    } else {
                        var coinID = Math.round(now / 70) + 1;
                    }
                    carusel.find('div:eq(' + (coinID - 1) + ')').css('z-index', 'unset');
                    carusel.find('div:eq(' + (coinID - 1) + ')').css('transform', 'scale(1,1)');
                    carusel.find('div:eq(' + coinID + ')').css('z-index', '9999');
                    carusel.find('div:eq(' + coinID + ')').css('transform', 'scale(1.33,1.33)');
                },
                complete: function () {


                    if (color == 1) {
                        popup.find('.wait-palyer__right').addClass('wait-palyer--lose');
                        var wrapLooser = popup.find('.wait-palyer__right .wait-palyer__rate');
                        wrapLooser.animate({num: cash}, {
                            duration: 3000,
                            step: function (num) {
                                wrapLooser.html(Math.round(-(num - cash)) + '<br><span>COINS</span>');
                            }
                        });

                        var wrapWinner = popup.find('.wait-palyer__left .wait-palyer__rate');
                        wrapWinner.animate({num: (cash * 2) - 1}, {
                            duration: 3000,
                            step: function (num) {
                                wrapWinner.html(Math.round(num + 1) + '<br><span>COINS</span>');
                            }
                        });

                        popup.find('.wait-palyer__left .wait-palyer__text').removeClass('hide').text('Победил');
                        popup.find('.wait-palyer__right .wait-palyer__text').removeClass('hide').text('Проиграл');


                    } else {
                        popup.find('.wait-palyer__left').addClass('wait-palyer--lose');
                        var wrapLooser = popup.find('.wait-palyer__left .wait-palyer__rate');
                        wrapLooser.animate({num: cash}, {
                            duration: 3000,
                            step: function (num) {
                                wrapLooser.html(Math.round(-(num - cash)) + '<br><span>COINS</span>');
                            }
                        });

                        var wrapWinner = popup.find('.wait-palyer__right .wait-palyer__rate');
                        wrapWinner.animate({num: (cash * 2) - 1}, {
                            duration: 3000,
                            step: function (num) {
                                wrapWinner.html(Math.round(num + 1) + '<br><span>COINS</span>');
                            }
                        });

                        popup.find('.wait-palyer__left .wait-palyer__text').removeClass('hide').text('Проиграл');
                        popup.find('.wait-palyer__right .wait-palyer__text').removeClass('hide').text('Победил');

                    }

                    popup.find('.modal-hash__text').removeClass('hide');
                    popup.find('.wait-palyer__spinner').replaceWith('<div class="wait-palyer__action"> <p class="wait-palyer__action-text">Победил: ' + name + '</p> </div>');


                }
            });
        }

        go();


    },

    setParticipateCoinflip: function (element) {
        var el = $(element);
        var gameId = el.closest('.coin-game').attr('data-game-id');

        if (gameId) {
            $.ajax({
                type: 'post',
                url: '/account/set-participant-coinflip',
                data: {'_token': $('meta[name="csrf-token"]').attr('content'), gameId: gameId},
                success: function (result) {
                    if (!result.error) {
                        $.ajax({
                            type: 'post',
                            url: '/account/show-game-coinflip',
                            data: {'_token': $('meta[name="csrf-token"]').attr('content'), gameId: gameId},
                            success: function (result) {
                                if (result) {
                                    $('#waitPlayer').html(result);
                                    $('li.hide > a[data-izimodal-open="#waitPlayer"]')[0].click();
                                }
                            }
                        });
                        // el.closest('.coin-game').find('.coin-game__eye a')[0].click();
                        $('.balance__value').html(result.balanceUser.toLocaleString() + '<span>COINS</span>');
                    } else {
                        window.createNotification({
                            closeOnClick: !0,
                            positionClass: "nfc-top-right",
                            showDuration: 2e3,
                            theme: "warning"
                        })({title: result.message})
                    }
                }
            });
        }


    },

    showGame: function (element, id) {
        var el = $(element);

        if (id) {
            var gameId = id;
        } else {
            var gameId = el.closest('.coin-game').attr('data-game-id');
        }


        if (gameId) {

            $.ajax({
                type: 'post',
                url: '/account/show-game-coinflip',
                data: {'_token': $('meta[name="csrf-token"]').attr('content'), gameId: gameId},
                success: function (result) {
                    if (result) {
                        $('#waitPlayer').html(result);

                    }
                }
            });

        }

    },

    createGameCoinflip: function (element) {
        var el = $(element);

        var cash = el.closest('#betModalCoin').find('.bet__form-input').val();
        var color = $('input[name="color"]').val();


        if (cash && color) {

            $.ajax({
                type: 'post',
                url: '/account/create-game-coinflip',
                data: {'_token': $('meta[name="csrf-token"]').attr('content'), cash: cash, color: color},
                success: function (result) {


                    $('.coin-history__right .coin-history-box__value--orange').html(result.bankUser.toLocaleString() + '<span> COINS</span>');
                    $('.coin-history__right .coin-history-box__col--center .coin-history-box__value').text(result.userGames);

                    setTimeout(function () {
                        MainFunction.metkaGame = false;
                    }, 500);

                    setTimeout(function () {
                        $('#betModalCoin #coins2').find('input[name="balance"]').val(100);
                        $('#coins-result2 input').val(10);
                        $('.balance-warning').addClass('hide');
                        $('.min-price-warning').addClass('hide');
                    }, 300);

                    $('body').find('.iziModal-overlay')[0].click();

                    if (!result.error) {
                        if ($('.bet-indicator').hasClass('disabled')) {
                            $('.bet-indicator').removeClass('disabled');
                            $('.bet-indicator p').html('Есть ставки');
                        }
                        $('.balance__value').html(result.balance.toLocaleString() + '<span>COINS</span>');

                        window.createNotification({
                            closeOnClick: !0,
                            positionClass: "nfc-top-right",
                            showDuration: 2e3,
                            theme: "success"
                        })({title: result.message});

                        $('#waitPlayer').html(result.view);

                        $('li.hide > a[data-izimodal-open="#waitPlayer"]')[0].click();

                    } else {

                        window.createNotification({
                            closeOnClick: !0,
                            positionClass: "nfc-top-right",
                            showDuration: 2e3,
                            theme: "warning"
                        })({title: result.message});
                    }
                }
            });
        }

    },


    validatePrice: function (element) {

        $('.balance-warning').addClass('hide');
        $('.min-price-warning').addClass('hide');

        var el = $(element);
        var balance = $('#betModalCoin').find('input[name="balance"]').val();

        if (+el.val() > +balance) {
            $('.balance-warning').removeClass('hide');
            return false;
        }

        if (+el.val() < 100) {
            $('.min-price-warning').removeClass('hide');
            return false;
        }

        return true;

    },

    startTimer: function (duration, callback, type) {

        var that = this;
        var timer = duration,
            minutes, seconds;
        that.setIntervalId = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            $('#jackpotTimer').text(seconds);

            var left = duration / 2;
            var right = left;

            if (timer >= right) {
                $('.jackpot-timer__left.first span').css({width: 100 + '%'});
                $('.jackpot-timer__right.first span').css({'width': ((timer / 3) * 60 / duration * 10) - 100 + '%'});
            }

            if (timer <= left) {
                $('.jackpot-timer__right.first span').css({'width': 0 + '%'});
                $('.jackpot-timer__left.first span').css({'width': (timer / 3) * 60 / duration * 10 + '%'});
            }

            if (--timer < 0) {
                timer = duration;
            }

            if (timer == 0) {
                $('.message-start-game').addClass('hide');
                setTimeout(function () {
                    $('.jackpot-timer__left.first span').css({'width': (timer / 3) * 60 / duration * 10 + '%'});
                    $('#jackpotTimer').text('00');
                    if (callback) {
                        // MainFunction.callback();
                        window["MainFunction"][callback](type);
                    }

                }, 1000);
                clearInterval(that.setIntervalId);


            }

        }, 1000);
    },

    startTimerKing: function (duration, callback, type) {

        var that = this;

        var timer = duration,
            minutes, seconds;

        if (type == 3) {
            //   clearInterval(MainFunction.setIntervalKingId3);
            //      MainFunction.setIntervalKingId3 = setInterval(function() {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            $('.classic-timer').removeClass('hide');
            $('.classic-timer .game__time-counter').text(seconds);


            if (--timer < -1) {
                timer = duration;
            }
            if (timer == -1) {
                //  $('#bedBet').attr('onclick','');
                setTimeout(function () {
                    $('.classic-timer .game__time-counter').text('00');

                    if (callback) {
                        window["MainFunction"][callback](type);
                    }

                }, 1000);
                clearInterval(MainFunction.setIntervalKingId3);

            }

            //  }, 1000);
        }

        if (type == 4) {
            //  clearInterval(MainFunction.setIntervalKingId4);
            // MainFunction.setIntervalKingId4 = setInterval(function() {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            $('.senyor-timer').removeClass('hide');
            $('.senyor-timer .game__time-counter').text(seconds);


            if (--timer < -1) {
                timer = duration;
            }

            if (timer == -1) {

                setTimeout(function () {
                    $('.senyor-timer .game__time-counter').text('00');

                    if (callback) {
                        window["MainFunction"][callback](type);
                    }

                }, 1000);
                clearInterval(MainFunction.setIntervalKingId4);

            }

            // }, 1000);
        }

    },

    callbackKing: function (type) {
        if (type == 3) {
            var wrap = $('.game-classic');

        }

        if (type == 4) {
            var wrap = $('.game-senyor');
        }

        wrap.find('.game__bank-value').text(0);
        wrap.find('.game-winner img').addClass('hide');


    },

    callbackJackpot: function () {
        $('.message-start-game').addClass('hide');

        $('.winners').addClass('hide');
        $('.jackpot-history-top').removeClass('hide');
        $('body').find('.choose-winner').removeClass('hide');

    },


    placeBet: function (element) {
        var el = $(element);
        var cash = el.closest('.modal__body').find('.cash-coins').val();
        var gameTypeId = $('body').find('input[name="game_type_id"]').val();

        if (cash && gameTypeId) {

            $.ajax({
                type: 'post',
                url: '/account/set-participant-game',
                data: {'_token': $('meta[name="csrf-token"]').attr('content'), cash: cash, gameTypeId: gameTypeId},
                success: function (result) {
                    setTimeout(function () {
                        MainFunction.metkaGame = false;
                    }, 500);

                    if (result.error == 0) {
                        $('.balance__value').html(result.balance.toLocaleString() + '<span>COINS</span>');
                        $('.conclusion__balance-value').html(result.balance.toLocaleString() + '<span>COINS</span>');

                        $('body').find('.iziModal-overlay')[0].click();

                        window.createNotification({
                            closeOnClick: !0,
                            positionClass: "nfc-top-right",
                            showDuration: 2e3,
                            theme: "success"
                        })({title: "Ставка успешно создана"})
                    } else {
                        $('body').find('.iziModal-overlay')[0].click();

                        window.createNotification({
                            closeOnClick: !0,
                            positionClass: "nfc-top-right",
                            showDuration: 2e3,
                            theme: "warning"
                        })({title: result.message})
                    }
                }
            });

        }

    },

    metkaGame: false,


    calculate: function (element, e, type) {
        $('.warning__balance').addClass('hide');

        var el = $(element);
        var balance = el.closest('.bet__form').find('input[name="balance"]').val();
        if (+balance > +el.val()) {
            el.closest('.bet__form').find('.count-money input').val(el.val() / 10);
        } else {
            $('.warning__balance').removeClass('hide');
        }

        if (e && e.keyCode == 13 && !MainFunction.metkaGame) {
            MainFunction.metkaGame = true;

            if (type == 'jackpot') {
                MainFunction.placeBet(element);
            }

            if (type == 'senyor') {
                MainFunction.createGameKing('senyor');
            }

            if (type == 'coinflip') {
                MainFunction.createGameCoinflip(element);
            }


        }

    },

    activatePromocode: function (element) {
        var el = $(element);
        var code = el.closest('form').find('input[name="promo"]').val();

        if (code) {

            $.ajax({
                type: 'post',
                url: '/account/activate-promocode',
                data: {'_token': $('meta[name="csrf-token"]').attr('content'), code: code},
                success: function (result) {
                    if (!result.error) {
                        $('.balance__value').html(result.sum.toLocaleString() + '<span>COINS</span>');

                        window.createNotification({
                            closeOnClick: !0,
                            positionClass: "nfc-top-right",
                            showDuration: 2e3,
                            theme: "success"
                        })({title: "Промокод успешно активирован"})

                    } else {
                        window.createNotification({
                            closeOnClick: !0,
                            positionClass: "nfc-top-right",
                            showDuration: 2e3,
                            theme: "warning"
                        })({title: result.text})
                    }
                }
            });

        } else {
            window.createNotification({
                closeOnClick: !0,
                positionClass: "nfc-top-right",
                showDuration: 2e3,
                theme: "warning"
            })({title: "Ошибка активации"})
        }

    },

    activateTheme: function (element) {

        var el = $(element);
        var theme = el.data('current-theme');

        $.ajax({
            type: 'get',
            url: '/change-theme',
            data: {theme: theme}
        });

        if (theme == 'light') {
            $('body--dark').addClass('body');
            $('body').removeClass('body--dark');
            el.data('current-theme', 'dark');
            $('.main-css').attr('href', '/css/main.min.css');
            el.find('i').addClass('ic-moon').removeClass('ic-sun');
            $('.header__logo img').attr('src', '/img/logo.png');
        } else {
            $('body').addClass('body--dark');
            $('.balance__output').addClass('button--dark');
            $('.game-history__time i').addClass('ic-clock-dark');
            $('.header__logo img').attr('src', '/img/logo-dark.png');
            $('.main-css').attr('href', '/css/main-dark.min.css');
            el.data('current-theme', 'light');
            el.find('i').addClass('ic-sun').removeClass('ic-moon');
        }
    },

    getBonus: function () {
        $('.get-bonus')[0].remove();

        $.ajax({
            type: 'get',
            url: '/account/daily-bonus-generate',
            success: function (result) {

                MainFunction.getBonuslotto(result, 8, 2, 5, 80, 5);
            }
        });

    },

    getBonuslotto: function (winner, countElement = 8, minValue = 2, maxValue = 5, width = 80, seconds = 0, callback = false, e, element = 'slick-track') {
        console.log(winner);
        element = 'bonus__slider';
        var translate = -40;
        var balance = winner.sum;

        /*
         $("." + element).slick({
                  infinite: true,
                  arrows: !1,
                  variableWidth: !0,
              });
              */

        var countDiv = $("." + element + " .slick-track").find('div').length;

        var n = +winner;
        var min = minValue,
            max = maxValue;
        var rand = min - 0.5 + Math.random() * (max - min + 1);
        r = Math.round(rand);
        var carusel = $('body').find('.' + element + ' .slick-track');
        carusel.stop();
        var globalSeconds = seconds;


        if (seconds > 0) {
            var sec = setInterval(function () {
                globalSeconds--;
                if (globalSeconds == 0) {
                    clearInterval(sec);
                }

            }, 1000);
        }

        function mulAndShuffle(arr, k) {
            var
                res = [],
                len = arr.length,
                total = k * len,
                rand, prev;
            while (total) {
                rand = arr[Math.floor(Math.random() * len)];
                if (len == 1) {
                    res.push(prev = rand);
                    total--;
                } else if (rand !== prev) {
                    res.push(prev = rand);
                    total--;
                }
            }
            return res;
        }

        // var el = $('<html></html>');
        // el.html(e.winners);

        var users = $('.' + element).find('.bonus__slider-item');

        users = mulAndShuffle(users, Math.ceil(130 / users.length));

        var html = '';
        var tempEl = $('.' + element).find('[data-count="' + winner.winner + '"]');
        //   console.log(tempEl);
        $('body').find('.' + element).find('.slick-track').html('');
        users.forEach(function (item, i, arr) {
            console.log(item);
            if (i == 112) {
                // html += ;
                $('body').find('.' + element).find('.slick-track').append(tempEl.clone());

            } else {
                $('body').find('.' + element).find('.slick-track').append($(item).clone());
            }

        });

        function randomInteger(min, max) {
            var rand = min + Math.random() * (max + 1 - min);
            rand = Math.floor(rand);
            return rand;
        }

        function go() {
            carusel.animate({marginLeft: -8888 - randomInteger(0, 70)}, 10000, 'easeOutCubic',
                function () {


                    if (callback) {
                        window[callback](e);
                    }

                    if (balance) {
                        $('.balance__value').html(balance.toLocaleString() + '<span>COINS</span>');
                    }


                });
        }

        go();


    },

    lotto: function (winner, countElement = 8, minValue = 2, maxValue = 500, width = 80, seconds = 0, callback = false, e, element = 'slick-track') {

        var translate = -40;
        var balance = winner.sum;
        if (element != 'slick-track') {
            $("." + element).slick({
                infinite: true,
                arrows: !1,
                variableWidth: !0,
            });
        } else {
            element = 'bonus__slider';
            winner = winner.winner;
            translate = -95;
        }

        var countDiv = $("." + element + " .slick-track").find('div').length;
        if (countDiv < 300) {
            $("." + element + " .slick-track").append($("." + element + " .slick-track").find('div').clone());
        }

        var n = +winner;
        var min = minValue,
            max = maxValue;
        var rand = min - 0.5 + Math.random() * (max - min + 1);
        r = Math.round(rand);
        var carusel = $('body').find('.' + element + ' .slick-track');
        carusel.stop();
        var globalSeconds = seconds;


        if (seconds > 0) {
            var sec = setInterval(function () {
                globalSeconds--;
                if (globalSeconds == 0) {
                    clearInterval(sec);
                }

            }, 1000);
        }

        function mulAndShuffle(arr, k) {
            var
                res = [],
                len = arr.length,
                total = k * len,
                rand, prev;
            while (total) {
                rand = arr[Math.floor(Math.random() * len)];
                if (len == 1) {
                    res.push(prev = rand);
                    total--;
                } else if (rand !== prev) {
                    res.push(prev = rand);
                    total--;
                }
            }
            return res;
        }

        var el = $('<html></html>');
        el.html(e.winners);
        console.log(e.winners);
        var users = [];
        // var users = [];
        el.find('.choose-winner__slider-item').each(function () {
            let count = parseInt($(this).find('img').data('percent') * 1.3);
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
        var tempEl = el.find('[data-count="' + winner + '"]');
        //   console.log(tempEl);
        $('body').find('.' + element).find('.slick-track').html('');
        users.forEach(function (item, i, arr) {
            // console.log(item);
            if (i == 112) {
                // html += ;
                $('body').find('.' + element).find('.slick-track').append(tempEl.clone());

            } else {
                $('body').find('.' + element).find('.slick-track').append($(item).clone());
            }

        });

        function randomInteger(min, max) {
            var rand = min + Math.random() * (max + 1 - min);
            rand = Math.floor(rand);
            return rand;
        }

        function go() {
            carusel.animate({marginLeft: -8140 - randomInteger(0, 70)}, 10000, 'easeOutCubic',
                function () {


                    if (callback) {
                        window[callback](e);
                    }

                    if (balance) {
                        $('.balance__value').html(balance.toLocaleString() + '<span>COINS</span>');
                    }


                });
        }

        go();

    },

    newGame: function () {

        $('.jackpot-history__list.bets').html('');
        $('.wrap-first-step').find('.slick-slider').html(' <div class="winners__slider-item winners__slider-item--">' +
            '<img src="https://fox-x.ru/img/fox.png" alt="avatar" class="winners__slider-avatar">' +
            '<span class="winners__slider-percent"></span>' +
            '</div>' +
            '<div class="winners__slider-item winners__slider-item--">' +
            '<img src="https://fox-x.ru/img/fox.png" alt="avatar" class="winners__slider-avatar">' +
            '<span class="winners__slider-percent"></span>' +
            '</div>');
        /*
          $(".winners__slider").slick({
            slidesToShow: 7,
            draggable:false,
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
        */
        $('.jackpot-history__hash a').html('');
        $('.winners').removeClass('hide');
        $('.message-start-game').removeClass('hide');
        $('.winner-step-3').addClass('hide');
        // $('.jackpot-timer.first').addClass('hide');
        $('.bank__value').html('0 <span>COINS</span>');
        $('.bank__players').text('0 игроков');
    },

    getInfoUser: function (userId) {

        if (userId) {
            $.ajax({
                type: 'post',
                url: '/admin/get-info-user',
                data: {'_token': $('meta[name="csrf-token"]').attr('content'), userId: userId},
                success: function (result) {
                    $('.wrap-modal-user').html(result);
                    $('body').find('.modal, .modal__container').css({
                        'visibility': 'visible',
                        'opacity': 1,
                        'transition': '.5s ease all'
                    });
                }
            });
        }

    },

    saveInfoUser: function (userId) {

        if (userId) {
            var form = $('body').find('.info-user form').serialize();

            $.ajax({
                type: 'post',
                url: '/admin/save-info-user',
                data: form,
                success: function (result) {
                    $('body').find('.modal__close-link')[0].click();
                    setTimeout(function () {
                        location.reload();
                    }, 100);
                }
            });

        }

    },

    saveInfoApplication: function (userId) {
        if (userId) {
            var form = $('body').find('.info-user form').serialize();

            $.ajax({
                type: 'post',
                url: '/admin/save-application-info',
                data: form,
                success: function (result) {
                    $('body').find('.modal__close-link')[0].click();
                }
            });

        }
    },

    // saveAjaxInfo:function (element) {
    //     var el = $(element),
    //         nameField = el.attr('name'),
    //         value = el.val(),
    //         userId = el.attr('data-user-id');
    //
    //     if(userId) {
    //
    //         $.ajax({
    //             type:'post',
    //             url: '/admin/save-ajax-info',
    //             data: {'_token': $('meta[name="csrf-token"]').attr('content'), userId:userId, nameField:nameField, value:value},
    //             success:function(result) {
    //
    //             }
    //         });
    //
    //     }
    //
    // },

    getInfoOutputUser: function (userId, applicationId) {

        if (userId && applicationId) {
            $.ajax({
                type: 'post',
                url: '/admin/get-info-user-output',
                data: {'_token': $('meta[name="csrf-token"]').attr('content'), userId: userId, applicationId: applicationId},
                success: function (result) {
                    $('.wrap-modal-user').html(result);
                    $('body').find('.modal, .modal__container').css({
                        'visibility': 'visible',
                        'opacity': 1,
                        'transition': '.5s ease all'
                    });
                }
            });
        }

    },

    createGameKing: function (type) {

        var cash = false;
        $('#bedBet').attr('onclick', '');
        if (type) {

            if (type == 'senyor') {
                cash = $('.cash-coins').val();
            }


            $.ajax({
                type: 'post',
                url: '/account/set-participants-king',
                data: {'_token': $('meta[name="csrf-token"]').attr('content'), type: type, cash: cash},
                success: function (result) {
                    setTimeout(function () {
                        MainFunction.metkaGame = false;
                    }, 500);

                    setTimeout(function () {
                        $('#bedBet').attr('onclick', "MainFunction.createGameKing('classic');");
                    }, 1500);

                    if (result.error == 0) {
                        if (result.balance) {
                            $('.conclusion__balance-value').html(result.balance.toLocaleString() + '<span>COINS</span>');
                            $('.balance__value').html(result.balance.toLocaleString() + '<span>COINS</span>');
                        }

                        if ($('body').find('.iziModal-overlay')[0]) {
                            $('body').find('.iziModal-overlay')[0].click();
                        }


                        window.createNotification({
                            closeOnClick: !0,
                            positionClass: "nfc-top-right",
                            showDuration: 2e3,
                            theme: "success"
                        })({title: "Ставка успешно создана"})
                    } else {
                        if ($('body').find('.iziModal-overlay')[0]) {
                            $('body').find('.iziModal-overlay')[0].click();
                        }

                        window.createNotification({
                            closeOnClick: !0,
                            positionClass: "nfc-top-right",
                            showDuration: 2e3,
                            theme: "warning"
                        })({title: result.message})
                    }
                }
            });
        }

    },


    endGameKing: function (e) {

        console.log(e);
        if (e.type == 3) {

            $('.game-classic').hide();
            $('.winner.classic').find('.winner__avatar img').attr('src', e.winner.image);
            $('.winner.classic').find('.winner__name').text(e.winner.name);
            $('.winner.classic').find('.winner__value').text(e.winnerTicket);
            $('.winner.classic').find('.winner__hash a').text(e.gameId);
            $('.winner.classic').find('.winner__right .winner-circle__numbers').html('<i class="ic-dollar"></i>' + e.winnerTicket);
            $('.winner.classic').find('.winner__left .winner-circle__numbers').html('<i class="ic-coin"></i>' + e.winner.price);
            $('.winner.classic').show();


            setTimeout(function () {
                $('.winner.classic').hide();
                $('.game-classic').show();
                var wrap = $('.game-classic');
                wrap.find('.game__title').text('Classic #' + e.newGameId);
                wrap.find('.game__time-link').html('ВНЕСТИ <span>10</span> COINS');
                setNewGame(wrap);
                $('#bedBet').attr('onclick', "MainFunction.createGameKing('classic');");
            }, 5000);

        }

        if (e.type == 4) {
            $('.game-senyor').hide();
            $('.winner.senyor').find('.winner__avatar img').attr('src', e.winner.image);
            $('.winner.senyor').find('.winner__name').text(e.winner.name);
            $('.winner.senyor').find('.winner__value').text(e.winnerTicket);
            $('.winner.senyor').find('.winner__hash a').text(e.gameId);
            $('.winner.senyor').find('.winner__right .winner-circle__numbers').html('<i class="ic-dollar"></i>' + e.winnerTicket);
            $('.winner.senyor').find('.winner__left .winner-circle__numbers').html('<i class="ic-coin"></i>' + e.winner.price);
            $('.winner.senyor').show();


            setTimeout(function () {
                $('.winner.senyor').hide();
                $('.game-senyor').show();
                var wrap = $('.game-senyor');
                wrap.find('.game__title').text('Senyor #' + e.newGameId);
                wrap.find('.game__time-link').html('ВНЕСТИ <span>1</span> COINS');
                $('.cash-coins').val(1);
                setNewGame(wrap);
            }, 5000);

        }

        function setNewGame(wrap) {
            wrap.find('.game__bank-value').text(0);
            wrap.find('.game__winner img').attr('src', 'https://fox-x.ru/img/fox.png');

            wrap.find('.slick-track').empty();
            wrap.find('.game__time-counter').text('20');
            $('.game-history').prepend(e.view);

            var user = $('input[name="time"]').val();
            if (user == e.winnerAccountId) {
                $('.balance__value').html(e.balance.toLocaleString() + '<span>COINS</span>');
            }

            $('.game__participants_classic .slick-track').css({'transform': 'translate3d(0px, 0px, 0px)'});
            $('.game__participants_senyor .slick-track').css({'transform': 'translate3d(0px, 0px, 0px)'});
        }

    }

};

var Message = {
    sendMessage: function (e) {
        console.log('send');
        if (!e || e.keyCode == 13) {
            var message = $('.chat-form__inp');

            if (message.val()) {
                $.ajax({
                    type: 'post',
                    url: '/account/send-message',
                    data: {'_token': $('meta[name="csrf-token"]').attr('content'), message: message.val()},
                    success: function (result) {
                        message.val('');
                        var block = $(".chat .nano-content");
                        block.scrollTop(99999999999999);
                    }
                });
            } else {
                var message = $('.mobile .chat-form__inp');

                if (message.val()) {
                    $.ajax({
                        type: 'post',
                        url: '/account/send-message',
                        data: {'_token': $('meta[name="csrf-token"]').attr('content'), message: message.val()},
                        success: function (result) {
                            message.val('');
                            var block = $(".chat .nano-content");
                            block.scrollTop(99999999999999);
                        }
                    });
                }
            }
        }

    }
};

var Payment = {
    toUpAccount: function () {
        var summa = $('#inputModal').find('.output-box__field');
        var paymentType = $('#inputModal').find('input[name="inputType"]:checked').val();

        if (summa.val() && summa.val() >= 50 && summa.val() <= 15000 && paymentType) {

            $.ajax({
                type: 'post',
                url: '/account/to-up-account',
                data: {'_token': $('meta[name="csrf-token"]').attr('content'), summa: summa.val(), paymentType: paymentType},
                success: function (result) {
                    $('.form-payment').html(result);
                    $('body').find('.freekassa')[0].submit();
                }
            });

        }

    },

    withdrawalOfFunds: function (element) {
        var el = $(element);
        el.closest('.modal').find('.warning__balance').addClass('hide');
        var summa = $('#outputModal').find('.output-box__field');
        var paymentType = $('#outputModal').find('input[name="outputType"]').val();
        var balance = el.closest('.modal').find('input[name="balance"]').val();
        var phone = el.closest('.modal').find('input[name="requisites"]').val();

        if (summa.val() && summa.val() >= 200 && summa.val() <= 15000 && paymentType && summa.val() <= (balance / 10) && phone) {

            $.ajax({
                type: 'post',
                url: '/account/withdrawal-of-funds-account',
                data: {'_token': $('meta[name="csrf-token"]').attr('content'), summa: summa.val(), paymentType: paymentType, phone: phone},
                success: function (result) {

                    $('.balance__value').html(result.toLocaleString() + '<span>COINS</span>');
                    $('.conclusion__balance-value').html(result.toLocaleString() + '<span>COINS</span>');

                    $('.iziModal-overlay')[0].click();
                    window.createNotification({
                        closeOnClick: !0,
                        positionClass: "nfc-top-right",
                        showDuration: 2e3,
                        theme: "success"
                    })({title: "Заявка успешно создана"})
                }
            });

        } else {
            el.closest('.modal').find('.warning__balance').removeClass('hide');
        }
    }
};

$(document).ready(function () {


    $('#auth a').click(function (e) {
        var el = $(this);
        if (el.attr('href') == '/help') {
            return true;
        }

        var checkbox = $('#checkAuth').prop('checked');

        if (!checkbox) {
            e.preventDefault();
        } else {
            if (el.hasClass('standart-auth')) {
                $('#auth').iziModal('close');
                $('#authLogin').iziModal('open');
            }
        }
    });

    var checkbox = $('#checkAuth').prop('checked');

    if (!checkbox) {
        $('#auth ul a').addClass('hide-icon');
    }

    $('#checkAuth').change(function (e) {
        if ($(this).prop('checked')) {
            $('#auth ul a').removeClass('hide-icon');
        } else {
            $('#auth ul a').addClass('hide-icon');
        }
    });

    $('#referralTable .paginations__item a').click(function (e) {
        e.preventDefault();
        var el = $(this);
        var page = el.attr('data-page');

        $('#referralTable .paginations__item a').removeClass('paginations__link--active');
        el.addClass('paginations__link--active');

        $.ajax({
            type: 'get',
            url: '/account/profile?page=' + page,
            success: function (result) {
                $('.wrap-ref-list').html(result);
            }
        });


    });


    $('#table1-top .table-sort').click(function () {
        var el = $(this);
        var order = el.attr('data-order');

        if (order == 'asc') {
            el.attr('data-order', 'desc');
        } else {
            el.attr('data-order', 'asc');
        }

        var sort = [];

        $('#table1-top .table-sort').each(function (indx, element) {
            var currentElement = $(element);
            var order = currentElement.attr('data-order');
            var column = currentElement.attr('data-column');
            var obj = {
                order: order,
                column: column
            };

            sort.push(obj);
        });

        json = JSON.stringify(sort);

        $('input[name="sort"]').val(json);

        $('.sorted-form').submit();


    });


    $('#goodBet').click(function () {
        $('.cash-coins').trigger('keyup');
    });

    $('#isMusic').change(function () {
        var el = $(this);
        if (el.prop('checked')) {
            var result = 1;
        } else {
            var result = 2;
        }

        $.ajax({
            type: 'post',
            url: '/account/setting-music',
            data: {'_token': $('meta[name="csrf-token"]').attr('content'), result: result}
        });

    });


    $('body').on('click', '.rooms__link', function () {
        var el = $(this);
        var typeGameId = el.attr('data-game-id');
        var currentGameId = $('body').find('input[name="game_type_id"]').val();

        if (typeGameId && currentGameId && currentGameId != typeGameId) {
            $.ajax({
                type: 'post',
                url: '/',
                data: {'_token': $('meta[name="csrf-token"]').attr('content'), typeGameId: typeGameId},
                success: function (result) {

                    max_id = setTimeout(function () {
                    });
                    while (max_id--) {
                        clearTimeout(max_id);
                    }

                    $('.script').remove();

                    $('.change-game-wrap-jackpot').html(result);


                    $body = $('body');
                    $body.removeEventListener();

                    $scripts_min = $('script[src$="/js/scripts.min.js"]');
                    $scripts_min.remove();
                    $body.append($('<script/>').attr('src', $scripts_min.attr("src")));

                    $script = $('script[src$="/js/script.js"]');
                    $script.remove();
                    $body.append($('<script/>').attr('src', $script.attr("src")));
                }
            });
        }

    });

    $('body').on('click', '.modal__close-link', function () {
        $('.modal, .modal__container').css({
            'visibility': 'hidden',
            'opacity': 0
        });
        $('body').css({
            'overflow': 'visible'
        });
    });

    // Close edit modal when user click on window
    var modal = document.getElementsByClassName('modal__container')[0];

    // When the user clicks anywhere outside of the modal, close it
    $(window).click(function (event) {
        if (event.target == modal) {
            $('.modal, .modal__container').css({
                'visibility': 'hidden',
                'opacity': 0
            });
        }
    });


    $('body').on('click', '.modal__bottom .paginations__item a', function (e) {
        var el = $(this);
        e.preventDefault();
        var page = el.attr('data-page');
        var userId = el.closest('.info-user').find('input[name="id"]').val();

        $.ajax({
            type: 'get',
            url: '/admin/users/next-page?page=' + page + '&userId=' + userId,
            success: function (result) {
                $('.wrap-paginate-user').html(result);
            }
        });


    });


    $('#saveProfile').click(function () {
        var error;
        if ($('#password').val() !== $('#password-check').val()) {
            error = true;
            window.createNotification({
                closeOnClick: !0,
                positionClass: "nfc-top-right",
                showDuration: 2e3,
                theme: 'error'
            })({title: "Пароли не совпадают!"})
        } else {


            $.ajax({
                url: '/account/update-profile',
                method: 'POST',
                data: {
                    email: $('input[name="email"]').val(),
                    password: $('input[name="password"]').val(),
                    vk: $('input[name="vk"]').val(),
                    telegram: $('input[name="telegram"]').val(),
                    facebook: $('input[name="facebook"]').val(),
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    if (result.error == 1) result = 'error';
                    else result = 'success';
                    window.createNotification({
                        closeOnClick: !0,
                        positionClass: "nfc-top-right",
                        showDuration: 2e3,
                        theme: result
                    })({title: "Данные обновлены"});

                },
                error: function (jqXhr, textStatus, errorThrown) {
                    console.log(jqXhr);
                }
            });
        }
    });

    $('.choose-period__title').click(function () {
        var el = $(this);
        var id = el.attr('for');
        var value = $('#' + id).val();

        setTimeout(function () {
            $('input[name="calendar_range"]').val(value);
        }, 10);


    });


    var block = $(".chat .nano-content");
    block.scrollTop(99999999999999);


});


function showWinnerJackpot(e) {
    setTimeout(function () {
        if (e) {
            $('.winner-number').html('<i class="ic-dollar"></i> ' + e.winnerTicket);
            $('.winner__hash a').attr('href', e.linkHash).text(e.hash);
            $('.winner-percent').text(e.percent + '%');
            $('.winner__avatar img').attr('src', e.image);
            $('.winner__name').text(e.name);
            $('.winner__value').html(e.bank + '<span> COINS</span>');
            $('.jackpot-history__list.last-winners .jackpot-timer__wrap').after(e.viewHistoryWinner);
        }

        $('.winner-step-3').removeClass('hide');
        $('.choose-winner').addClass('hide');
        $('.jackpot-history-top').addClass('hide');


        setTimeout(function () {
            $('#jackpotTimer').text('35');
            $('.jackpot-timer__left.first span').css({width: 100 + '%'});
            $('.jackpot-timer__right.first span').css({width: 100 + '%'});
            MainFunction.newGame();
            $('input[name="gameId"]').val('');
        }, 4000);

    }, 1500);


}
