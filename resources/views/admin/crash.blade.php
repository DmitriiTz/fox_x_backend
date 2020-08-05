@extends('admin.layout')
@section('content')

	<!-- Start main -->
	<main class="main">

		<!-- Start sidebar -->
	@include('admin.blocks.left-menu')
		<!-- End sidebar -->

		<!-- Start content -->
		<div class="base">
			<div class="base__wrapper base__wrapper--games">
				<div class="nano base__games">
					<div class="nano-content">
						<div class="games__wrap">

							<div class="container-crash crash-actions" id="cr">
                                <div class="user-info" style="width: 200px">
                                    <div class="user-info__top">
                                        <div class="user-info__box">
                                            <input type="number" min="0" max="999" step="0.1" class="user-info__field"
                                                   name="profit"
                                                   value=""
                                                   placeholder="Коэфициент игры">
                                        </div>
                                        <button class="btn btn-stop-game btn-crash-coef">Установить</button>
                                        <span class="output-coeff"></span>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener("DOMContentLoaded", function(){
                                        $(document).on('click', '.btn-crash-coef', function() {
                                            $('.btn-crash-coef').next().text('');
                                            var profit = $('[name=profit]').val();

                                            if(profit.length) {
                                                if(profit > 1000) {
                                                    coef_msg('danger', 'Коэффициент не может быть больше 1000!');
                                                    return;
                                                }
                                                $.ajax({
                                                    type: 'GET',
                                                    url: "/crash/set-profit?profit=" + profit,
                                                    success: function(result){
                                                        console.log('Профит установлен: ', profit);
                                                        coef_msg('success', 'Профит установлен');
                                                    },
                                                    error: function(){
                                                        coef_msg('danger', 'Профит должен быть больше текущего!');
                                                    },
                                                });
                                            }
                                            else {
                                                coef_msg('danger', 'Укажите коэфициент!');
                                            }
                                        });
                                    });
                                    function coef_msg(type, msg) {
                                        var $output = $('.btn-crash-coef').next();
                                        $output
                                            .html('<div class="text-' + type + '">' + msg + '</div>');
                                        setTimeout(function () {
                                            $output.text('');
                                        }, 2000);
                                    }
                                </script>

                                <hr>

                                <div class="crash-game" style="display: flex !important; flex-wrap: wrap;">
                                    @include('admin.crash_result', [
                                    'mode' => null,
                                    'info' => $info,
                                    'bets' => null,
                                    ])
								</div>
							</div>


							<div id="app">
								<admin-crash></admin-crash>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End content -->

	</main>
	<!-- End main -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function gameInfoInterval(){
            $.ajax({
                type: 'GET',
                url: '/crash/get-info?admin=1',
                success: function(result){
                    console.log('Game info updated');
                    $('.crash-game').html(result);
                }
            });
        }
        var int = setInterval(gameInfoInterval, 300);
        function stop_game(){
            var r = $('#total_x').text();
            $('.btn-stop-game').attr('disabled','disabled');
            console.log('Нажато Stop', r);
            $.ajax({
                type: 'GET',
                url: "/admin/crash/stop-game?r="+r,
                data: $('#s_g').serialize(),
                success: function(result){
                    coef_msg('success', 'Игра остановлена');
                }
            });
        }

    </script>

@endsection
