@extends('layout')
@section('content')

	<body @if(checkSessionTheme()) class="body--dark" @endif>
	<!-- Start section -->
	<main class="main">
		<div class="main__left">
			
			<!-- Profile block -->
			<div class="profile">
				<div class="profile__avatar">
					<img src="{{ asset(auth()->user()->image) }}" alt="avatar">
				</div>
				<p class="profile__name">{{ auth()->user()->name }}</p>
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
		<div class="main__center main__center--king">

			<!-- Profile block -->
			<div class="profile mobile-show">
				<div class="profile__avatar">
					<img src="{{ asset(auth()->user()->image) }}" alt="avatar">
				</div>
				<p class="profile__name">{{ auth()->user()->name }}</p>
			</div>

			<div class="referral">
				<h4 class="referral__title">РЕФЕРАЛЬНАЯ СИСТЕМА</h4>
				<div class="referral__wrap">
					<div class="referral__left">

						@php
							$exp = getExperience(auth()->user());

                            if($exp % 1000 == 0 && $exp != 80000) {
                                $experienceHeader = 0;
                            }
                            elseif($exp > 1000 && $exp <= 10000) {
                                $experience = floor($exp / 1000) * 1000;
                                $experienceHeader = $exp - $experience;
                            }
                            elseif($exp > 10000 && $exp < 80000) {
                                 $experience = floor($exp / 1000) * 1000;
                                 $experienceHeader = $exp - $experience;
                            }

                            else {
                               $experienceHeader = 1000;
                            }


						@endphp


						<div class="ref-level">
							<div class="ref-level__top">
								<p class="ref-level__level">{{ getLevel(auth()->user()) }} LEVEL.</p>
								<p class="ref-level__exp">{{ $experienceHeader }} / <span>1000 EXP.</span></p>
							</div>
							<div class="ref-level__timeline">
								<span style="width:{{ 100 - color(getExperience(auth()->user())) }}%"></span>
							</div>
							<p class="ref-level__text">1 уровень = 200 руб.</p>



							<div class="set-profile-form">
								<div class="set-profile"><span>Профиль:</span></div>
							<div class="set-profile"><label>E-mail:</label><input type="text" name="email" value="{{auth()->user()->email}}" placeholder="{{auth()->user()->email}}"/></div>
							<div class="set-profile"><label>Пароль:</label> <input id="password" type="password" name="password"  placeholder="Введите новый пароль"/></div>

							<div class="set-profile"><label>Подтвердите:</label> <input id="password-check" type="password" name="re-type password" placeholder="Подтвердите пароль"/></div>

							<div class="set-profile"><label>Telegram:</label> <input type="text" value="{{auth()->user()->link_youtube}}" name="telegram" placeholder="{{auth()->user()->link_youtube}}"/></div>
							
							<div class="set-profile"><label>Вконтакте:</label> <input type="text" name="vk" value="{{auth()->user()->link_vk}}" placeholder="{{auth()->user()->link_vk}}" disabled /></div>

							<div class="set-profile"><label>Facebook:</label> <input type="text" name="facebook" value="{{auth()->user()->link_facebook}}"  placeholder="{{auth()->user()->link_facebook}}" disabled /></div>

						
							<div class="set-profile"><button id="saveProfile"  class="button ref-link__promo-button">Сохранить</button>
						</div>
						</div>



					
						</div>

						<div class="ref-value">
							<p class="ref-value__text">Кол-во ваших <br>
									рефералов:</p>
							<a class="ref-value__link" data-izimodal-open="#referralTable" data-izimodal-transitionin="fadeInDown">{{auth()->user()->referrals()->count() }}</a>
						</div>

					</div>
					<div class="referral__right ref-link">
						<form class="ref-link__promo">
							<input type="text" name="promo" class="ref-link__promo-field" placeholder="Введите промокод">
							<a id="promoActive" onclick="MainFunction.activatePromocode(this);" class="button ref-link__promo-button">Активировать</a>
						</form>
						<p class="ref-link__title">Получайте бонусы с рефералов!</p>
						<div class="ref-link__wrap">
							@php
								$lvl = getLevel(auth()->user());
                    			$percent = $lvl * 0.1;
							@endphp
							<p class="ref-link__percent">{{ $percent }}%</p>
							<p class="ref-link__text">С каждого приведенного 
									игрока на наш сервис</p>
						</div>
						<a id="showNotify" class="ref-link__link">http://fox-x.ru/referral-link/{{ auth()->user()->id }}</a>
						<input type="text" id="showNotifyInput" class="ref-link__link--hidden" value="http://fox-x.ru/referral-link/{{ auth()->user()->id }}">
						<p class="ref-link__stock">Делитесь ссылкой с друзьями и получайте реферальные начисления!</p>
					</div>
				</div>
			</div>

			<div id="history" class="history">
				<div class="history__top">
					<ul class="history__links">
						<li class="history__link history__link--active">ИСТОРИЯ БАЛАНСА</li>
						<li class="history__link">/ ИСТОРИЯ ИГР</li>
					</ul>
					
					<a class="history__help hide" data-izimodal-open="#outputRules" data-izimodal-transitionin="fadeInDown"><i class="ic-rules"></i>Правила вывода</a>
				</div>
				<div id="historyBodyOne" class="history__body history__body--active nano">
					<div class="nano-content">
						<ul class="history__list">
							@foreach($getHistoryBalance as $history)
								@if($history->payment_type_id == 1)
									<li class="history__item hist-balance hist-balance--green">
										<div class="hist-balance__left">
											<p class="hist-balance__sum"><i class="ic-money-green"></i>{{ $history->price }} Руб.</p>
										</div>
										<div class="hist-balance__right">
											<p class="hist-balance__source">{{ $history->payment_system->name }}</p>

											<p class="hist-balance__data">{{ date('d/m/Y', strtotime($history->created_at)) }}</p>
										</div>
									</li>
								@endif

								@if($history->payment_type_id == 2 && $history->payment_system)
										<li class="history__item hist-balance hist-balance--red">
											<div class="hist-balance__left">
												<p class="hist-balance__sum"><i class="ic-money-red"></i>{{ $history->price }} Руб.</p>
											</div>
											<div class="hist-balance__right">
												<p class="hist-balance__source">{{ $history->payment_system->name }}</p>
												<p class="hist-balance__data">{{ date('d/m/Y', strtotime($history->created_at)) }}</p>
											</div>
										</li>
								@endif

								@if($history->payment_type_id == 3 && $history->referral)
										<li class="history__item hist-balance hist-balance--yellow">
											<div class="hist-balance__left">
												<p class="hist-balance__sum"><i class="ic-money-yellow"></i>{{ $history->price }} Руб.</p>
											</div>
											<div class="hist-balance__right">
												<p class="hist-balance__source--user"><i class="ic-user-black"></i> Игрок <span>{{ $history->referral->name }}</span> пополнил баланс</p>
												<p class="hist-balance__data">{{ date('d/m/Y', strtotime($history->created_at)) }}</p>
											</div>
										</li>
								@endif

							@endforeach

						</ul>
					</div>
				</div>
				<div  id="historyBodyTwo" class="history__body nano">
					
					<div class="nano-content">
						<ul class="history__list">
							@foreach($listGames as $game)
								@if(!$game->game)
									@continue
								@else
									@if($game->payment_type_id == 3)
										<li class="history__item hist-balance hist-balance--yellow">
											<div class="hist-balance__left">
												<p class="hist-balance__sum"><i class="ic-money-yellow"></i>{{ $game->price }} Руб.</p>
											</div>
											<div class="hist-balance__right">
												@php
													$referral = \App\User::where('id', $game->referral_account_id)->first();
												@endphp

												<p class="hist-balance__source--user"><i class="ic-user-black"></i> Игрок <span>{{ $referral->name }}</span> пополнил баланс</p>
												<p class="hist-balance__data">{{ date('d/m/Y', strtotime($game->created_at)) }}</p>
											</div>
										</li>
									@else
										@if($game->game->winner_account_id == $game->account_id)
											<li class="history__item hist-balance hist-balance--game hist-balance--green">
												<div class="hist-balance__left">
													@php
														$cash = \App\Participant::where('history_game_id', $game->game->id)->sum('cash');
													@endphp
													<p class="hist-balance__sum"><i class="ic-money-green"></i>{{ $cash }} coins</p>
													<p class="hist-balance__game">{{ $game->game->nameGame->name }}</p>
												</div>
												<div class="hist-balance__right">
													<p class="hist-balance__data">{{ date('d/m/Y', strtotime($game->game->created_at)) }}</p>
												</div>
											</li>
										@else
											<li class="history__item hist-balance hist-balance--game hist-balance--red">
												<div class="hist-balance__left">

													<p class="hist-balance__sum"><i class="ic-money-red"></i>{{ $game->game->participants[0]->cash }} coins</p>
													<p class="hist-balance__game">{{ $game->game->nameGame->name }}</p>
												</div>
												<div class="hist-balance__right">
													<p class="hist-balance__data">{{ date('d/m/Y', strtotime($game->game->participants[0]->created_at)) }}</p>
												</div>
											</li>
										@endif
									@endif
								@endif


							@endforeach

						</ul>
					</div>
				</div>
				
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

	<div class="modal modal--help" id="referralTable">
		<div class="modal__top">
			<h6 class="modal__title">Список ваших рефералов</h6>
			<a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
		</div>
		<div class="modal__body">
			<div class="wrap-ref-list">
				@include('blocks.wrap-ref-list')
			</div>
			@if(count($listReferrals))
				{{ $listReferrals->links() }}
			@endif
		</div>
	</div>

	<div class="modal modal--help" id="outputRules">
		<div class="modal__top">
			<h6 class="modal__title">Правила вывода</h6>
			<a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
		</div>
		<div class="modal__body">
			<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi cum illo laborum corporis, sint itaque ullam temporibus nesciunt ex vel. Explicabo iste necessitatibus rem officiis enim quaerat perspiciatis provident qui?</p>
		</div>
	</div>
	<style>

	</style>
@endsection
