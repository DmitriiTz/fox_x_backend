@extends('layout')

@section('content')

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

			<!-- Notification block -->
			<!-- @include('blocks.notification') -->

			<!--Copyright block -->
			<div class="copyright">
				<p class="copyright__text">Копирование элементов дизайна с сайта без согласия запрещено! <br>
						&copy;FOX-X.RU 2018</p>
			</div>

			<!-- Application block -->
			<!-- @include('blocks.application') -->

		</div>
		<div class="main__center main__center--full">
			<div class="starting">
				<h2 class="starting__title">РАЗДЕЛ ПОМОЩИ</h2>
				<p class="starting__text">Мы постораемся помочь вам с проблемами, но сперва
					посмотрите уже готовые решения ниже..</p>
			</div>
			<div class="padding-wrap">
				
				<div class="answers">
					<div class="answer">
						<div class="answer__top">
							<h6 class="answer__title">Что такое FOX-X.RU</h6>
							<span class="answer__arrow"></span>
						</div>
						<div class="answer__body">
							<p class="answer__text">
								FOX-X - это развлекательный, животрепещущий интернет ресурс с различными играми на реальные деньги. Наше преимущества перед аудиторией в том, что мы не зарабатываем на проигрышах людей, как другие сайты. Сайт получает 10% комиссию с игр и администрация нацелена на стабильное увеличение посещаемости, рейтинга и доверия со стороны участников, все игры происходят только между игроками.
							</p>
						</div>
					</div>
					<div class="answer">
						<div class="answer__top">
							<h6 class="answer__title">Что такое hash?</h6>
							<span class="answer__arrow"></span>
						</div>
						<div class="answer__body">
							<p class="answer__text">
								При желании каждый посетитель ресурса может проверить честность игры с помощью Хэш-раунда (sha224).<br>
								Что такое hash? — безопасный алгоритм хеширования семейство криптографических алгоритмов Хеш-функции предназначены для создания «отпечатков» или «дайджестов» для сообщений произвольной длины. Применяются в различных приложениях или компонентах, связанных с защитой информации, в нашем случае защита результата игры до ее окончания чем не могут похвастаться наши конкуренты! Что это значит? то что игра на нашем сайте абсолютно честная и никто не может узнать результат до окончания раунда.<br>
								Хэш-функия sha224 разработана агентством национальной безопасности США.
							</p>
						</div>
					</div>
					<div class="answer">
						<div class="answer__top">
							<h6 class="answer__title">Финансы</h6>
							<span class="answer__arrow"></span>
						</div>
						<div class="answer__body">
							<p class="answer__text">
								1). Для того чтобы играть на нашем сайте вам нужно авторизироваться через социальную сеть Вконтакте. После регистрации с помощью кнопки "пополнить" свой баланс можно через Qiwi, Яндекс Visa, Master Card. Так же доступно пополнение с остальных систем через AnyPay.<br>
								1.1). Заявки на вывод обрабатываются в течении 24 часов.<br>
								1.2). В случае если в течении этого времени вывод не пришёл, обращайтесь в тех поддержку-https://vk.com/im?sel=-177183494<br>
								P.S. Но только если у вас есть номер заказа , в случае если его нет — то обращайтесь сюда http://www.free-kassa.ru/support.php иначе мы не сможем ничем вам помочь.
							</p>
						</div>
					</div>
					<div class="answer">
						<div class="answer__top">
							<h6 class="answer__title">Ежедневный бонус</h6>
							<span class="answer__arrow"></span>
						</div>
						<div class="answer__body">
							<p class="answer__text">
								Ежедневный бонус - начиная с 3 LEVEL вы можете каждый день крутить коины и получать их в виде бонусов как отдельный участник нашей гильдии. В группе ВК и на канале Telegram мы будем проводить различные конкурсы для поощрения наших участников.
							</p>
						</div>
					</div>
					<div class="answer">
						<div class="answer__top">
							<h6 class="answer__title">Jackpot</h6>
							<span class="answer__arrow"></span>
						</div>
						<div class="answer__body">
							<p class="answer__text">
								Jackpot. Это простой, но и очень интересный режим. Все участники вносят любую ставку коинами и образуется общий банк. Каждый участник получает свой шанс на выигрыш зависящий от размера его ставки. Чем больше ставка - тем больше шанс выиграть, но и с маленьким шансом есть возможность выиграть весь банк!
							</p>
						</div>
					</div>
					<div class="answer">
						<div class="answer__top">
							<h6 class="answer__title">Coinflip</h6>
							<span class="answer__arrow"></span>
						</div>
						<div class="answer__body">
							<p class="answer__text">
								Coinflip - Это игра, где происходит сражение двух игроков между собой, ставки вносятся в виде "Coins" у обоих имеются одинаковые шансы на победу 50/50. т.е если один игрок создал игру на 100 "Coins" то присоединиться к игре можно только сделав аналогичный взнос 100 "Coins" не больше и не меньше. По окончанию таймера разыгрывается банк между двумя участниками игры с помощью прокрутки ролла. При внесении "Coins" в игру, игрок получает виртуальные билеты, один из которых будет выигрышным, за 1 внесенный "Coins" вы получите 1 билет, за 100 внесенных "Coins" вы получите 100 билетов в банке.
							</p>
						</div>
					</div>
					<div class="answer">
						<div class="answer__top">
							<h6 class="answer__title">King of the Hill</h6>
							<span class="answer__arrow"></span>
						</div>
						<div class="answer__body">
							<p class="answer__text">
								King of the Hill - Игра аукционного типа в которой после взноса каждого участника включается таймер с отчетом 20 секунд. Каждый последующий взнос должен быть больше предыдущего. Победителем объявляется участник сделавший последний взнос и по окончанию 20 секунд которого никто так и не перебил взносом более крупной суммы. Победитель забирает все сливки игры состоящие из суммы всех взносов участников данного раунда Classic - установлен фиксированный взнос с каждым последующим увеличением шага на 10 coins Senyor - установлен произвольный взнос с последующим увеличением шага на любую сумму.
							</p>
						</div>
					</div>
					<div class="answer">
						<div class="answer__top">
							<h6 class="answer__title">Чат</h6>
							<span class="answer__arrow"></span>
						</div>
						<div class="answer__body">
							<p class="answer__text">
								Чат - открывается при достижении 3 LEVEL, рекомендуется не нарушать правила чата, быть вежливым во избежания банов.
							</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Balance block -->
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
			<!-- @include('blocks.application', ['isMobile' => 1]) -->
			
		</div>
		@include('blocks.chat')
	</main>
	<!-- End section -->

	@include('account.blocks.popups')

@endsection
