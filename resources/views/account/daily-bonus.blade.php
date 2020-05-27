@extends('layout')
@section('content')

	<body @if(checkSessionTheme()) class="body--dark" @endif>	
	<!-- Start section -->
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
		<div class="main__center main__center--full">
			<div class="starting">
				<h2 class="starting__title">ЕЖЕДНЕВНЫЙ БОНУС</h2>
				<p class="starting__text">Заходите на сайт каждый день и получайте различные бонусы совершенно бесплатно! У Вас есть шанс выиграть до 1000 coins, не упустите удачу!!!</p>
			</div>
			<div class="padding-wrap">
				@if(getExperience(auth()->user()) <= 3000)
					<div class="hint">
						<span class="hint__icon">!</span>
						<div class="hint__wrap">
							<p class="hint__title">Внимание!</p>
							<p class="hint__text">Для открытия ежедневного бонуса достигните 3 уровня</p>
						</div>
					</div>
					<div class="get-bonus">
						<div class="get-bonus__body">
							<p class="disabled-bonus__button">
								Получить <br>
								БОНУС
							</p>
						</div>
					</div>
				@else

					@if((!$isDailyBonus || $isDailyBonus->created_at < \Carbon\Carbon::parse($isDailyBonus->created_at)->subDay()))
						<div class="get-bonus">
							<div class="get-bonus__body">
								<a href="javascript:void(0);" onclick="MainFunction.getBonus();" class="get-bonus__button">
									Получить <br>
									БОНУС
								</a>
							</div>
							<div class="get-bonus__bottom">
								<p class="get-bonus__info">У вас есть 1 бесплатный спин!</p>
							</div>
						</div>
						<div class="bonus">
							<div class="bonus__slider">
								@foreach($arr as $a)
									@if($a == 0)
										<div class="bonus__slider-item bonus-item" data-count="{{ $a }}">
											<span class="bonus-item__value">{{ $a }}</span>
											<span class="bonus-item__coins">COINS</span>
										</div>
									@endif

									@if($a == 100)
										<div class="bonus__slider-item bonus-item bonus-item--orange" data-count="{{ $a }}">
											<span class="bonus-item__value">{{ $a }}</span>
											<span class="bonus-item__coins">COINS</span>
										</div>
									@endif

									@if($a == 20 || $a == 50)
										<div class="bonus__slider-item bonus-item bonus-item--lose" data-count="{{ $a }}">
											<span class="bonus-item__value">{{ $a }}</span>
											<span class="bonus-item__coins">COINS</span>
										</div>
									@endif

									@if($a == 200 || $a == 500)
										<div class="bonus__slider-item bonus-item bonus-item--green" data-count="{{ $a }}">
											<span class="bonus-item__value">{{ $a }}</span>
											<span class="bonus-item__coins">COINS</span>
										</div>
									@endif

									@if($a == 1000 || $a == 5000)
										<div class="bonus__slider-item bonus-item bonus-item--yellow" data-count="{{ $a }}">
											<span class="bonus-item__value">{{ $a }}</span>
											<span class="bonus-item__coins">COINS</span>
										</div>
									@endif

									@if($a == 10000)
										<div class="bonus__slider-item bonus-item bonus-item--blue" data-count="{{ $a }}">
											<span class="bonus-item__value">{{ $a }}</span>
											<span class="bonus-item__coins">COINS</span>
										</div>
									@endif
								@endforeach
							</div>
							<div class="bonus__point">
								<i class="ic-roulette-arrow"></i>
							</div>
						</div>
					@else
						<div class="get-bonus">
						<div class="get-bonus__body">
							<p class="disabled-bonus__button">
								Получить <br>
								БОНУС
							</p>
						</div>
						<div class="get-bonus__bottom">
							<p class="get-bonus__info">Следующий бонус будет доступен через: {{ \Carbon\Carbon::parse($isDailyBonus->created_at)->subDay()->diffForHumans(null, true) }}</p>
							{{--<p id="bonus-timer" class="get-bonus__timer"></p>--}}
						</div>
					</div>
					@endif
				@endif


			</div>

			@include('blocks.balance', ['isMobile' => 1])

			<!-- Help block -->
			<div class="help mobile-show">
				<p class="help__title">Помощь</p>
				<a class="help__link" data-izimodal-open="#helpModal" data-izimodal-transitionin="fadeInDown">
					<i class="ic-rules"></i>
					Правила игры
				</a>
			</div>

			<!-- Application block -->
			<div class="application mobile-show">
				<p class="application__title">Мобильное приложение</p>
				<a href="http://play.google.com/store/apps/details?id=​package_name" class="application__link">
					<img src="{{ asset('img/google-play.png') }}" alt="google-play">
				</a>
			</div>
		</div>
		@include('blocks.chat')
	</main>
	<!-- End section -->


	@include('account.blocks.popups')

@endsection
