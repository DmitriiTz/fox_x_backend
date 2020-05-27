$(document).ready( function() {
    console.log('crash ready');
	// var socketCG = io.connect(':6002', {
    //     secure: true,
    //     'force new connection': true
    // });
    var socketCG = io.connect(':6001');
	var usersList = $('.betshist');
	//usersList.mCustomScrollbar();
    // socketCG.broadcast.emit('crash-coef', 'test coef');
    // console.log('coef');
    // $(document).on('crash-coef', function(e, coeff) {
    //     console.log('coef: ', coeff);
    //     socketCG.broadcast.emit('crash-coef', coeff);
    // });

		socketCG.on('timeLeft', function(data) {
			$('.cg_graph_block .time_left').removeClass('disable');
			$('.cg_graph_block .time_left span').html(data);
		});
		socketCG.on('winnerCrash', function(message) {
        if (message) {
            data = JSON.parse(message);
            var n = message.indexOf(USER_ID);
            if (n !== -1) {
                updateBalance();
                notify({ type: "success",title: "Краш",message: data.msg,position: {x: "right",y: "top"},autoHide: true,delay: 5000});
            }
        }
    });
		socketCG.on('startGame', function(data) { 
			startX = 0;
			scaleX = 20;
			scaleY = 150;
			cgBetStop();
			$('#gameid').html(data.id);
			$('.cg_hash_secret .secret').html('Скрыто');
			$('.cg_hash_secret .hash').html(data.hash);
		});
		
		socketCG.on('endGame', function(data) { 
		
			paintCrashGraphic(data.x, data.randomNumber);
			$('.cg_bet').each(function() {
				var val = parseFloat($(this).find('.bet_num').html());
				if (val <= data.randomNumber) {
					$(this).addClass('win');
				}
			});
			$('.cg_bet:not(.win)').addClass('lose');
			$('.cg_hash_secret .secret').html(data.num);
			setTimeout(function () {
				$('#roundBank4').text('0');
		$('#roundBank5').text('0');
						$('.betshist').html('');
						cgBetStart();
					}, 3500);
			setTimeout(function () {
						$('.cg_graph_block .time_left span').html(10);
						$('.cg_graph_block .time_left').removeClass('disable');
					}, 4500);
			var hist = '<div class = "game clearfix"><div class = "num">'+data.id+'</div><div class = "secret">'+data.num+'</div><div class = "number">'+data.randomNumber+'</div><div class = "hash">'+$('.cg_hash_secret .hash').html()+'</div></div>'
			$('.gameshist').prepend($(hist));
			$('.gameshist .game').eq(8).remove();
		});
		
		socketCG.on('betCrash', function(data) {
			data = JSON.parse(data);
			var html = '<div class = "cg_bet clearfix" id = "u'+data.userid+'">'
							+'<div class = "img">'
								+ '<img src = "' + data.userava + '" alt = "' + data.username + '">'
							+'</div>'
							+'<div class = "name">'
								+ data.username
							+'</div>'
							+'<div class = "bet_num">'
								+ data.betnumber
							+'</div>'
							+'<div class = "bet_sum">'
								+ data.betsum
							+'</div>'
						+'</div>';
			$('.betshist').prepend(html); 
			usersList.mCustomScrollbar('update');
        setTimeout(function() {
            usersList.mCustomScrollbar('scrollTo', 'bottom');
        }, 200);
		
		$('#roundBank4').text(data.price);
		$('#roundBank5').text(data.price);
		});
		
		socketCG.on('crashGraph', function(x) {
			paintCrashGraphic(x);
			$('.cg_graph_block .time_left').addClass('disable');
			cgBetStop();
		});
		
		
		var graphStep = 0.1;
		var startX = 0;
		var canvas = $('#crashGraphic')[0];
		var context = canvas.getContext('2d'); 
		var canvasWidth;
		var canvasHeight;
		var scaleX = 30;
		var scaleY = 200;
		var border = 5;
		var drawX, drawY;
	
		function paintCrashGraphic(curentX, randomNumber) {	
			canvasWidth = canvas.width;
			canvasHeight = canvas.height;
			context.lineWidth = 2;
			if ((border * 2 + curentX * scaleX) > canvasWidth) {
				scaleX = (canvasWidth - border * 2) / curentX;
			}
			if ((border * 2 + getCrashGraphicY(curentX) * scaleY) > canvasHeight) {
				scaleY = (canvasHeight - border * 2) / getCrashGraphicY(curentX);
			}
			context.strokeStyle = '#fff';	
			context.clearRect(0, 0, canvasWidth, canvasHeight);	
			context.beginPath();
			context.moveTo(border, canvasHeight - border );
			context.lineTo(border, border);	
			context.moveTo(border, canvasHeight - border);
			context.lineTo(canvasWidth - border, canvasHeight - border);
			
			drawX = startX;
			var isFirst = true;
			context.stroke();
			context.beginPath();				
			context.lineWidth = 3;
			
			context.strokeStyle = '#a0a1a7';		
			while (drawX <= curentX) {
				drawY = getCrashGraphicY(drawX);
				drawX += graphStep;
				if (isFirst) {
					isFirst = false;
					context.moveTo(border + drawX * scaleX, canvasHeight - border - (drawY - 1) * scaleY);
				} else {
					context.lineTo(border + drawX * scaleX, canvasHeight - border - (drawY - 1) * scaleY);
				}
			}
			
			if (!randomNumber) {
				context.stroke();	
				context.font = "50px Tahoma";
				context.textAlign = "center";
				context.fillStyle = "#ffcd05";
				context.fillText(drawY.toFixed(2) + 'x', canvas.width/2, canvas.height/2); 
			} else {
				context.stroke();
				context.font = "50px Tahoma";
				context.textAlign = "center";
				context.fillStyle = "red";
				context.fillText(randomNumber + 'x', canvas.width/2, canvas.height/2); 
			}
		}
		
		function getCrashGraphicY(x) {
			return Math.pow(1.06, x);
		}
					
		function getCrashGraphicX(y) {
			return Math.log(y) / Math.log(1.06);
		}
		
		paintCrashGraphic(startX);
		

		
		$(document).on('click', '.cg_user_bet_panel .cg_bet_button', function() {
			if (!$(this).hasClass('disable')) {
					var val = $('#cg_bet').val()
					if (val <= parseInt($('.userBalance').html())) { 
						if (val > 0) {
							var number = !isNaN(parseFloat($('#cg_number').val()))? parseFloat($('#cg_number').val()) : 0; 
							$.post('/crash/bet/make',{price:val,number : number}, function(data){
								if(data.type == 'success'){
									cgBetStop();
									updateBalance();
								}
			notify({type: data.type, title: "Краш", message: data.text,position: {x: "right", y: "top" },autoHide: true, delay: 5000});
			
        });
						} else {
							popUp('<div class = "popUpText">Вы ввели не верную ставку</div>', function() {});
						}
					} else {
						popUp('<div class = "popUpText">На Вашем балансе недостаточно средства для ставки. Пополните баланс и повторите попытку</div>', function() {});
					}

			}
		});

	
	function cgBetStop() {
		$('.cg_user_bet_panel .cg_bet_button').addClass('disable');
		$('.cg_user_bet_panel input').attr('disabled', 'disabled');
	}
	
	function cgBetStart() {
		$('.cg_user_bet_panel .cg_bet_button').removeClass('disable');
		$('.cg_user_bet_panel input').removeAttr('disabled');
	}
	
	
});
