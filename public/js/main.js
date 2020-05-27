$('#crashGraphic').attr('width', $('#crash_block').width() - 60);

var graphStep = 0.1;
var startX = 0;
var canvas = $('#crashGraphic')[0];
var context = canvas.getContext('2d');
var canvasWidth;
var canvasHeight;
var scaleX = 80;
var scaleY = 300;
var border = 5;
var drawX, drawY, drawX2;
var interval;
var si = 0;
var platr = 1;
var cti = 15;
var c_t = 10;
var newStep;
var stage = 9;
var sec = 0;

function startCrashTimer(curentX, i){
    context.closePath();

    $('#dp_bet').attr('disabled', false);
    $('.block-state-crash').html(' ');
    $('#users_total_bet').html('0');
    $('#users_count_bet').html('0');

    context.beginPath();

    sendGraph();

    var int = setInterval(function(){

        if(i >= 1){
            clearCanvas();

            canvasWidth = canvas.width - 20;
            canvasHeight = canvas.height - 25;

            if ((border * 2 + curentX * scaleX) > canvasWidth) {
                scaleX = (canvasWidth - border * 2) / curentX;
            }
            if ((border * 2 + getCrashGraphicY(curentX) * scaleY) > canvasHeight) {
                scaleY = (canvasHeight - border * 2) / getCrashGraphicY(curentX);
            }

            context.clearRect(0, 0, canvasWidth, canvasHeight);
            context.clearRect(0, 0, canvasWidth + 20, canvasHeight);

            context.lineWidth = 0.5;

            context.moveTo(border + 20 + 0.5, canvasHeight - border);
            context.lineTo(border + 20 + 0.5, border);
            context.moveTo(border + 20 + 0.5, canvasHeight - border);
            context.lineTo(canvasWidth - border, canvasHeight - border);

            context.strokeStyle = '#aaa';
            context.stroke();

            context.stroke();
            context.font = "40px Tahoma";
            context.textAlign = "center";
            context.fillStyle = "green";
            context.fillText('Place your bets...' + i, canvas.width/2, canvas.height/2);

            i--;
        }else{
            clearInterval(int);
            location.reload();

        }

    }, 1000);

}

// Инициализация графика canvas
function paintCrashGraphic(curentX, randomNumber) {
    canvasWidth = canvas.width;
    canvasHeight = canvas.height - 20;
    context.lineWidth = 2;
    if ((border * 2 + curentX * scaleX) > canvasWidth) {
        scaleX = (canvasWidth - border * 2) / curentX;
    }
    if ((border * 2 + getCrashGraphicY(curentX) * scaleY) > canvasHeight) {
        scaleY = (canvasHeight - border * 2) / getCrashGraphicY(curentX);
    }

    context.lineWidth = 0.5;
    context.stroke();
    context.closePath();

    context.strokeStyle = '#aaa';
    context.clearRect(0, 0, canvasWidth, canvasHeight);
    context.beginPath();
    context.moveTo(border + 20 + 0.5, canvasHeight - border);
    context.lineTo(border + 20 + 0.5, border);
    context.moveTo(border + 20 + 0.5, canvasHeight - border);
    context.lineTo(canvasWidth - border, canvasHeight - border);


    context.closePath();
    drawX = startX;
    var isFirst = true;
    context.stroke();
    context.beginPath();
    context.lineWidth = 0.5;

    //Нижняя разметка цифр
    var m = 0;
    var x_max = 10;
    var x0 = y0 = 30;
    var stepY = Math.round(canvasHeight / maxCount);
    var stepX = Math.round(canvasWidth / 10);
    var maxCount = 35 + 10;
    var maxCount = 35 + 10;
    var width = canvas.width - 80;
    var height = canvas.height + 10;
    //нижняя разметка и цифры
    for (var i = x0; m < x_max; i += stepX) {
        m ++;
        context.font = "10px";
        context.textAlign = "center";
        context.fillStyle = "#aaa";
        context.moveTo(i, height + y0);
        context.lineTo(i, height + y0 + 15);
        context.fillText(m + 's', i + 3, canvasHeight + 10);
    }
    context.lineWidth = 0.5;
    context.stroke();
    context.closePath();

    //Вертикальная разметка цифр
    var v = 0;
    var v_max = 7;
    var stepYv = 1;
    var _i = 8;
    var h = canvasHeight / v_max;
    var height = 10;
    while (v < v_max){
        _i = _i - stepYv;
        v_max--;
        context.font = "10px Tahoma";
        context.textAlign = "center";
        context.fillStyle = "#aaa";
        context.fillText(_i + 'x', 10, height + 10);

        height = height + h;
    }
}

// Инициализация линий и роста графика
function initGraph(curentX, isFirst,randomNumber){
    context.closePath();
    // $('#dp_bet').attr('disabled', true);
    clearCanvas();

    canvasWidth = canvas.width - 20;
    canvasHeight = canvas.height - 25;

    if ((border * 2 + curentX * scaleX) > canvasWidth) {
        scaleX = (canvasWidth - border * 2) / curentX;
    }
    if ((border * 2 + getCrashGraphicY(curentX) * scaleY) > canvasHeight) {
        scaleY = (canvasHeight - border * 2) / getCrashGraphicY(curentX);
    }

    context.clearRect(0, 0, canvasWidth + 20, canvasHeight);
    context.clearRect(0, 0, canvasWidth + 20, canvasHeight);
    context.clearRect(0, 0, canvasWidth + 20, canvasHeight);

    initScale(curentX);

    drawX = startX;
    drawXX = startX;
    var isFirst = true;
    var isFirstX = true;

    context.beginPath();
    context.lineWidth = 9.5;
    context.stroke();

    context.strokeStyle = '#FFB838';
    while (drawX <= curentX) {
        drawY = getCrashGraphicY(drawX);
        drawX += graphStep;
        if (isFirst) {
            isFirst = false;
            context.moveTo(border + drawX * scaleX + 10, canvasHeight - 5 - border - (drawY - 1) * scaleY );
        } else {
            context.lineTo(border + drawX * scaleX + 10, canvasHeight - 5 - border - (drawY - 1) * scaleY);
        }
    }

    context.stroke();
    context.font = "50px Tahoma";
    context.textAlign = "center";
    if(parseInt(curentX) < 12){
        context.fillStyle = '#E95E3F';
    }else if(parseInt(curentX) > 11 && parseInt(curentX) < 66){
        context.fillStyle = '#7DD358';
    }else if(parseInt(curentX) >= 65 && parseInt(curentX) < 78){
        context.fillStyle = '#3E8EBA';
    }else{
        context.fillStyle = '#FFB838';
    }
    var coeff = 0;
    if(drawY != undefined) {
        coeff = drawY.toFixed(2);
    }
    // console.log(drawY, coeff);
    // socketCG.broadcast.emit('crash-coef', coeff);
    // var socketCG = io.connect(':6001');
    // socketCG.broadcast.emit('crash-coef', 'test coef');
    //
    // $(document).trigger('crash-coeff', coeff);

    // console.log(coeff);
    $('body').data('coeff', coeff).attr('data-coeff', coeff);
    if(coeff > 999) stop_game(999);

    context.fillText(coeff + 'x', canvas.width/2, canvas.height/2);

    var prof_max = $('#users_count_bet_win').text();

    $('.crash-table-body').each(function (index, element) {
        // index (число) - текущий индекс итерации (цикла)
        // данное значение является числом
        // начинается отсчёт с 0 и заканчивается количеству элементов в текущем наборе минус 1
        // element - содержит DOM-ссылку на текущий элемент

        if($(element).attr('data-cashout') < drawY){
            if (!$(element).hasClass("win")) {
                $(element).addClass('win');
                var el = '.user-' + $(element).attr('data-user');
                $(el + ' .cash').html($(element).attr('data-cashout') + 'x');
                var profit = parseInt($(element).attr('data-cashout')) * parseInt($(element).attr('data-bet'));
                var pr = parseInt(profit) - parseInt($(element).attr('data-bet'));
                $(el + ' .profit').html('+' + pr);
                var profit_users = parseInt(prof_max) + parseInt(pr);
                $('#users_count_bet_win').html(profit_users);
                if($(element).attr('data-user') == $('#auser').val()){
                    updateUserBalanceCrash($(element).attr('data-user'), pr);
                }
            }
        }
    });

    init2Line(curentX);
}

// Инициализация доп. линии 100.0Х зеленого цвета
function init2Line(curentX){
    context.closePath();

    canvasWidth = canvas.width;
    canvasHeight = canvas.height - 25;

    if ((border * 2 + curentX * scaleX) > canvasWidth) {
        scaleX = (canvasWidth - border * 2) / curentX;
    }
    if ((border * 2 + getCrashGraphicY(curentX) * scaleY) > canvasHeight) {
        scaleY = (canvasHeight - border * 2) / getCrashGraphicY(curentX);
    }

    drawX = startX;
    drawXX = startX;
    var isFirst = true;
    var isFirstX = true;

    context.beginPath();
    context.lineWidth = 10;

    while (drawXX <= curentX) {
        drawYY = getCrashGraphicY(drawXX);
        drawXX += graphStep;

        if(parseInt(drawXX) < 78){
            if (isFirstX) {
                isFirstX = false;
                context.moveTo(border + drawXX * scaleX + 10, canvasHeight - border - (drawYY - 1) * scaleY);
            } else {
                context.lineTo(border + drawXX * scaleX + 10, canvasHeight - border - (drawYY - 1) * scaleY);
            }
        }
    }

    context.strokeStyle = '#3E8EBA';
    context.stroke();

    init3Line(curentX);
}

// Инициализация доп. линии от 2.0Х до 50.0Х зеленого цвета
function init3Line(curentX){
    context.closePath();

    canvasWidth = canvas.width;
    canvasHeight = canvas.height - 25;

    if ((border * 2 + curentX * scaleX) > canvasWidth) {
        scaleX = (canvasWidth - border * 2) / curentX;
    }
    if ((border * 2 + getCrashGraphicY(curentX) * scaleY) > canvasHeight) {
        scaleY = (canvasHeight - border * 2) / getCrashGraphicY(curentX);
    }

    drawX = startX;
    drawXX = startX;
    var isFirst = true;
    var isFirstX = true;

    context.beginPath();
    context.lineWidth = 10;

    while (drawXX <= curentX) {
        drawYY = getCrashGraphicY(drawXX);
        drawXX += graphStep;

        if(parseInt(drawXX) < 66){
            if (isFirstX) {
                isFirstX = false;
                context.moveTo(border + drawXX * scaleX + 10, canvasHeight - border - (drawYY - 1) * scaleY);
            } else {
                context.lineTo(border + drawXX * scaleX + 10, canvasHeight - border - (drawYY - 1) * scaleY);
            }
        }
    }

    context.strokeStyle = '#7DD358';
    context.stroke();

    init4Line(curentX);
}

// Инициализация доп. линии до 2.0Х красного цвета
function init4Line(curentX){
    context.closePath();

    canvasWidth = canvas.width;
    canvasHeight = canvas.height - 25;

    if ((border * 2 + curentX * scaleX) > canvasWidth) {
        scaleX = (canvasWidth - border * 2) / curentX;
    }
    if ((border * 2 + getCrashGraphicY(curentX) * scaleY) > canvasHeight) {
        scaleY = (canvasHeight - border * 2) / getCrashGraphicY(curentX);
    }

    drawX = startX;
    drawXX = startX;
    var isFirst = true;
    var isFirstX = true;

    context.beginPath();
    context.lineWidth = 10;

    while (drawXX <= curentX) {
        drawYY = getCrashGraphicY(drawXX);
        drawXX += graphStep;

        if(parseInt(drawXX) < 12){
            if (isFirstX) {
                isFirstX = false;
                context.moveTo(border + drawXX * scaleX + 10, canvasHeight - border - (drawYY - 1) * scaleY);
            } else {
                context.lineTo(border + drawXX * scaleX + 10, canvasHeight - border - (drawYY - 1) * scaleY);
            }
        }
    }

    context.strokeStyle = '#E95E3F';
    context.stroke();

    arcGraf(curentX);
}

function arcGraf(curentX){
    context.closePath();

    canvasWidth = canvas.width;
    canvasHeight = canvas.height - 25;

    if ((border * 2 + curentX * scaleX) > canvasWidth) {
        scaleX = (canvasWidth - border * 2) / curentX;
    }
    if ((border * 2 + getCrashGraphicY(curentX) * scaleY) > canvasHeight) {
        scaleY = (canvasHeight - border * 2) / getCrashGraphicY(curentX);
    }

    drawX = startX;
    drawXX = startX;

    context.beginPath();
    context.lineWidth = 1;


    //Первый параметр - весь Х
    //Второй параметр - весь Y
    //Третий параметр - размер
    drawXX = curentX;
    drawYY = getCrashGraphicY(drawXX);

    context.arc(border + drawXX * scaleX + 10, canvasHeight - border - (drawYY - 1) * scaleY + 2, 8, 0, 2*Math.PI, false);

    if(parseInt(drawXX) < 12){
        context.strokeStyle = '#E95E3F';
        context.fillStyle = '#E95E3F';
        context.fill();
        context.stroke();
    }else if(parseInt(drawXX) > 11 && parseInt(drawXX) < 66){
        context.strokeStyle = '#7DD358';
        context.fillStyle = '#7DD358';
        context.fill();
        context.stroke();
    }else if(parseInt(drawXX) >= 65 && parseInt(drawXX) < 78){
        context.strokeStyle = '#3E8EBA';
        context.fillStyle = '#3E8EBA';
        context.fill();
        context.stroke();
    }else{
       context.strokeStyle = '#FFB838';
        context.fillStyle = '#FFB838';
        context.fill();
        context.stroke();
    }
}

//requestAnimationFrame(initRedLine);

// Инициализация осей графика
function initScale(curent){
    // console.log('current', curent);
    clearCanvas();
    context.closePath();

    context.clearRect(0, 0, canvasWidth, canvasHeight);
    context.lineWidth = 0.5;
    canvasWidth = canvas.width;
    canvasHeight = canvas.height - 20;

    //context.beginPath();

    //Нижняя разметка цифр
    if(curent < 13){
        var m = 13;
    }else{
        //var m = Math.trunc(curent);
        var m = Math.trunc(curent);
    }
    var curX = getCrashGraphicY(curent);
    var mm = 0;
    var x_max = 200;
    var x0 = y0 = 15;
    var stepY = Math.round(canvasHeight / maxCount);
    var maxCount = 35 + 10;
    var maxCount = 35 + 10;
    var width = canvas.width - 80;
    var height = canvas.height + 10;
    var newStep;
    var simb;
    var x_stage;


    //Вертикальная разметка цифр
    var v = 0;
    var stepYv = 0;

    var h = canvasHeight;

    var height = 10;
    if(curX < 7){
        var _i = 7;
        var v_max = 7;
    }else{
        var _i = curX;
        var v_max = curX;
    }
    var nwst;
    var simbX;

    if(curX < 10){
        x_stage = 2;
    }else if(curX > 9 && curX < 50){
        x_stage = 10;
    }else if(curX > 49 && curX < 200){
        x_stage = 50;
    }else if(curX > 199 && curX < 400){
        x_stage = 100;
    }else{
        x_stage = 200;
    }

    if(x_stage == 2){
        nwst = curX;
        if(nwst < 3){
            nwst = 3;
        }else{
            nwst = curX;
        }
    }else{
        nwst = curX;
    }

    if(curX < 3){
        curX = 3;
    }

    var cwX = h / nwst;

    // console.log('cwx = ' + cwX + ' canvasHeight = ' + canvasHeight + ' NWST = ' + Math.trunc(nwst));

    while (v < curX){
        _i = _i - stepYv;
        v_max--;
        v++;
        context.font = "10px Tahoma";
        context.textAlign = "center";
        context.fillStyle = "#aaa";

        if(curX < 10){
            if(curX == 3){
                simbX = v + 'x';

                context.moveTo(border + 20 + 0.5, h - 10);
                context.lineTo(canvasWidth - border, h - 10);
            }else{
                if(v % x_stage){
                    simbX = v + 'x';

                    context.moveTo(border + 20 + 0.5, h - 10);
                    context.lineTo(canvasWidth - border, h - 10);
                }else{
                    simbX = '';
                }
            }

            context.fillText(simbX, 10, h - 10);
        }else{
            if(v % x_stage){
                simbX = '';
            }else{
                simbX = v + 'x';

                context.moveTo(border + 20 + 0.5, h);
                context.lineTo(canvasWidth - border, h);
            }
            context.fillText(simbX, 10, h);
        }
        h = h - cwX;
    }



    si = si + 0.025;

    if(curent < 15){
        newStep = 1;
    }else if(curent > 14 && curent < 30){
        newStep = 2;
    }else if(curent > 30 && curent < 70){
        newStep = 5;
    }else if(curent > 70 && curent < 150){
        newStep = 10;
    }

    if(newStep == 1){
        stage = curent / newStep;
        if(stage < 13){
            stage = 13;
        }
    }else{
        stage = curent;
    }

    var cw = canvasWidth / stage;
    //context.closePath();

    context.strokeStyle = '#aaa';
    //context.beginPath();

    //нижняя разметка и цифры
    for (var i = x0; mm < m; i += cw) {
        context.font = "10px";
        context.textAlign = "center";
        context.fillStyle = "#aaa";
        // context.moveTo(i, height + y0);
        // context.lineTo(i, height + y0 + 15);
        if(mm % newStep){
           simb = '';
        }else{
            simb = mm + 's';
            if(mm != 0){
                context.moveTo(i + 3, canvasHeight - border);
                context.lineTo(i + 3, border);
            }

            context.lineWidth = 0.1;
        }
        context.fillText(simb, i + 3, canvasHeight + 10);
        mm++;
    }

    context.strokeStyle = '#aaa';
    context.stroke();

    iniLineGr();
}

function iniLineGr(){
    context.closePath();
    context.beginPath();


    context.moveTo(border + 20 + 0.5, canvasHeight - border);
    context.lineTo(border + 20 + 0.5, border);
    context.moveTo(border + 20 + 0.5, canvasHeight - border);
    context.lineTo(canvasWidth - border, canvasHeight - border);

    context.lineWidth = 0.5;

    context.strokeStyle = '#aaa';
    context.stroke();

}

function clearCanvas(){
    canvasWidth = canvas.width;
    canvasHeight = canvas.height;
    context.clearRect(0, 0, canvasWidth, canvasHeight);
}

function crushGraph(curentX, timeout, stop){
    var profit = $('body').data('profit');
    context.closePath();
    $('#dp_bet').attr('disabled', true);
    canvasWidth = canvas.width - 20;
    canvasHeight = canvas.height - 25;

    if ((border * 2 + curentX * scaleX) > canvasWidth) {
        scaleX = (canvasWidth - border * 2) / curentX;
    }
    if ((border * 2 + getCrashGraphicY(curentX) * scaleY) > canvasHeight) {
        scaleY = (canvasHeight - border * 2) / getCrashGraphicY(curentX);
    }

    context.clearRect(0, 0, canvasWidth, canvasHeight);
    context.clearRect(0, 0, canvasWidth + 20, canvasHeight);
    context.clearRect(0, 0, canvasWidth + 20, canvasHeight);

    initScale(curentX);

    drawX = startX;
    drawXX = startX;
    var isFirst = true;
    var isFirstX = true;

    context.beginPath();
    context.lineWidth = 10;
    context.stroke();

    context.strokeStyle = '#E95E3F';
    while (drawX <= curentX) {
        drawY = getCrashGraphicY(drawX);
        if(drawY >= profit) stop_game(profit);
        drawX += graphStep;
        if (isFirst) {
            isFirst = false;
            context.moveTo(border + drawX * scaleX + 10, canvasHeight - 5 - border - (drawY - 1) * scaleY );
        } else {
            context.lineTo(border + drawX * scaleX + 10, canvasHeight - 5 - border - (drawY - 1) * scaleY);
        }
    }

    context.stroke();
    context.font = "50px Tahoma";
    context.textAlign = "center";
    context.fillStyle = "#E95E3F";
    var coeff = drawY.toFixed(2);
    if(coeff >= profit) coeff = profit.toFixed(2);
    context.fillText('Crashed @' + coeff + 'x', canvas.width/2, canvas.height/2);

    context.beginPath();

    drawYY = getCrashGraphicY(curentX);
    context.arc(border + curentX * scaleX + 10, canvasHeight - border - (drawYY - 1) * scaleY + 2, 8, 0, 2*Math.PI, false);

    context.strokeStyle = '#E95E3F';
    context.fill();
    context.stroke();

    $('.crash-table-body').each(function (index, element) {
        // index (число) - текущий индекс итерации (цикла)
        // данное значение является числом
        // начинается отсчёт с 0 и заканчивается количеству элементов в текущем наборе минус 1
        // element - содержит DOM-ссылку на текущий элемент

        if($(element).attr('data-cashout') > drawY){
            $(element).addClass('lose');
        }else{
            $(element).addClass('win');
        }

    });
    //
    // if(stop == 1) {
    //     startCrashTimer(curentX, 10);
    // }
    setTimeout(function(){
        console.log('destroy ' + coeff);
        startCrashTimer(curentX, 10);
    }, timeout);
}

function getCrashGraphicY(x) {
    return Math.pow(1.06, x);
}

paintCrashGraphic(startX);

function newCrash (i_int, curent, n, stop_game, stop){

    interval = setInterval(function(){

        if(i_int >= n){
            clearInterval(interval);
            var x = parseInt(stop) - parseInt(curent);

            console.log(x + ' - x');

            if(x > 10){
                var res = parseInt(x) - 10;
                var timer = res * 1000;
                console.log(i_int + ' - timer - ' + timer);
                crushGraph(i_int, timer);
            }else if(x <= 10){
                startCrashTimer(i_int, x);
            }

        }
        else{
            initGraph(i_int);
            i_int = i_int + 0.025;
        }

    }, 25);
}

function cgBetStop() {
    $('.cg_user_bet_panel .cg_bet_button').addClass('disable');
    $('.cg_user_bet_panel input').attr('disabled', 'disabled');
}

function cgBetStart() {
    $('.cg_user_bet_panel .cg_bet_button').removeClass('disable');
    $('.cg_user_bet_panel input').removeAttr('disabled');
}

function sendGraph(){
    $.ajax({
        type: 'GET',
        url: "/crash/new",
        data: $('#s_g').serialize(),
        success: function(result){
            console.log(result);
        }
    });
}

function infoCrash (){
    $.ajax({
        type: 'GET',
        url: "/crash/info",
        data: $('#s_g').serialize(),
        success: function(result){
            console.log(result);
        }
    });
}

function getLastGame (){
    $.ajax({
        type: 'GET',
        url: "/crash/last-game",
        data: $('#s_g').serialize(),
        success: function(result){


        }
    });
}

function updateUserBalanceCrash (user, balance){
    $.ajax({
        type: 'GET',
        url: "/crash/update-balance?id="+user+"&price="+balance,
        data: $('#s_g').serialize(),
        success: function(result){
            var bl = $('#blockBalance').text();
            $('#blockBalance').html(parseInt(bl) + parseInt(result['price']));
        }
    });
}

$('#bet_form').on('submit', function(e){
    e.preventDefault();
    var $bet = $('#bet');
    var bet = $('#bet').val();
    var $cashout = $('#cashout');
    var cashout = $('#cashout').val();
    $bet.removeClass('error');
    $cashout.removeClass('error');
    console.log(bet,cashout);
    if(bet < 10) {
        toastr.error('Ставка не может быть меньше 10');
        $bet.addClass('error');
    }
    else if(cashout < 1) {
        toastr.error('Коэфициент не может быть меньше 1.00x');
        $cashout.addClass('error');
    }
    else if(cashout > 1000) {
        toastr.error('Коэфициент не может быть больше 1000.00x');
        $cashout.addClass('error');
    }
    else {
        $.ajax({
            type: 'POST',
            url: "/crash/bet",
            data: $('#bet_form').serialize(),
            success: function(result){
                console.log(result);
                $('#dp_bet').attr('disabled', true);
                var bl = $('#blockBalance').text();
                $('#blockBalance').html(parseInt(bl) - parseInt(result.bet));
                if(result.status != 0) {
                    toastr.success(result.msg);
                    $('#bet').val(0);
                    $('#cashout').val(0);
                    if(result.ubets) {
                        $('.crash-player-table').replaceWith(result.ubets);
                    }
                }
                else {
                    toastr.error(result.msg);
                }
            }
        });
    }
});

$('.cashout ul li').click(function(){

    var ac = $(this).data('action');
    var input = $('#cashout');

    if(ac == '1'){
        input.val(1.01);
    }else{
        if(input.val() == ''){
            var val = 1.00;
        }else{
            if(input.val() == '1.01'){
                var val = 1.00;
            }else{
                var val = input.val();
            }
        }
        var vF = ac.substr(0,1);
        console.log(vF);
        if(vF == '+'){
            var s = ac.slice(1);
            var res = parseFloat(val) + parseFloat(s);
            input.val(res.toFixed(2));
        }else if(vF == '-'){
            var s = ac.slice(1);
            var res = parseFloat(val) - parseFloat(s);

            if(res > 1){
                input.val(res.toFixed(2));
            }else{
                input.val(1.01);
            }
        }else if(vF == 'X'){
            var res = ac.slice(1);
            console.log(res);
            input.val(res + ".00");
        }
    }

    $('#cashout_user').val(input.val());

});

$('.bet ul li').click(function(){
    var ac = $(this).data('action');
    var input = $('#bet');
    var balance = $('#balance_bet').val();

    if(input.val() == ''){
        if(ac == 'half'){
            input.val(5);
        }else if(ac == 'max'){
            input.val(balance);
        }else{
            var vF = ac.substr(0,1);
            if(vF == '+'){
                var s = ac.slice(1);
                var ten = parseInt(s) + 10;
                input.val(ten);
            }else if(vF == 'X'){
                input.val(20);
            }else if(vF == '%'){
               var pr = ac.slice(1);
               var tn = 10 * parseFloat(pr / 100);
               input.val(Math.floor(tn));
            }
        }
    }else{
        if(ac == 'half'){
            var half = parseInt(input.val()) / 2
            input.val(Math.floor(half));
        }else if(ac == 'max'){
            input.val(balance);
        }else{
            var vF = ac.substr(0,1);
            if(vF == '+'){
                var s = ac.slice(1);
                var ten = parseInt(s) + parseInt(input.val());
                input.val(ten);
            }else if(vF == 'X'){
                input.val(input.val() * 2);
            }else if(vF == '%'){
               var pr = ac.slice(1);
               var tn = parseInt(input.val()) * parseFloat(pr / 100);
               input.val(Math.floor(tn));
            }
        }
    }

    $('#bet_user').val(input.val());

});

$('#clear_cashout').click(function(){
    $('#cashout').val('');
});

$('.clear-bet').click(function(){
    $('#bet').val('');
});

$('.chat__close').click(function(){
    //setTimeout(resize, 500);
});

console.log($('#crash_block').width() + 'px');

function resize(){
    $('#crashGraphic').attr('width', $('#crash_block').width());
    console.log($('#crash_block').width() + 'px - resize');
}

function stop_game(r){
    if(r == undefined) var r = $('body').data('coeff');
    $.ajax({
        type: 'GET',
        url: "/admin/crash/stop-game?r="+r,
        data: $('#s_g').serialize(),
        success: function(result){
            console.log('График остановлен');
        }
    });
}


// $(document).on('crash-coeff', function(e, coeff) {
//     console.log(coeff);
//     $('body').data('coeff', coeff).attr('data-coeff', coeff);
//     // crushGraph(coeff,1);
//     // $.ajax({
//     //     type: 'GET',
//     //     url: "/crash/set-current-profit?profit=" + coeff,
//     //     success: function(result){
//     //         // console.log('Профит установлен: ', profit);
//     //     }
//     // });
// });
