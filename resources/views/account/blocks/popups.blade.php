	@if(auth()->check())
    <div class="modal modal--conclusion" id="outputModal">
        <input type="hidden" name="balance" value="{{ getBalance(auth()->user()) }}">
        <div class="modal__top">
            <h6 class="modal__title">Вывод средств</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Текущий баланс:</p>
                <p class="conclusion__balance-value">{{ getBalance(auth()->user()) }} <span>coins</span></p>
            </div>
            <div class="output-box">
                <div class="output-box__top">
                    <p class="output-box__text">Сумма</p>
                    <input type="number" class="output-box__field">
                    <p class="output-box__hint">
                        Максимальная сумма за раз: 15000р <br>
                        Минимальная сумма: 200р
                    </p>
                </div>
                <div class="output-box__bottom">
                    <p class="output-box__text">Реквизиты</p>
                    <p class="output-box__requisites">+799379992</p>
                </div>
                <div class="warning__balance hide">
                    <p class="warning__balance-text">* Неккоректные данные</p>
                </div>
            </div>

            <div class="conclusion__where">
                <p class="conclusion__where-title">КУДА ВЫВЕСТИ</p>
                <ul class="conclusion__where-list">
                    <li>
                        <label class="output-radio">
                            <input type="radio" checked="checked" value="1" name="outputType">
                            <span class="output-radio__box">
                                    <i class="ic-qiwi"></i>
                                </span>
                        </label>
                    </li>
                    <li>
                        <label class="output-radio">
                            <input type="radio" name="outputType" value="2">
                            <span class="output-radio__box">
                                    <i class="ic-sber"></i>
                                </span>
                        </label>
                    </li>
                    <li>
                        <label class="output-radio">
                            <input type="radio" name="outputType" value="3">
                            <span class="output-radio__box">
                                    <img src="{{ asset('img/yandex.png') }}" alt="" style="width:120px; margin-top: 10px;">
                                </span>
                        </label>
                    </li>
                    <li>
                        <label class="output-radio">
                            <input type="radio" name="outputType" value="4">
                            <span class="output-radio__box">
                                    <img src="{{ asset('img/mv.png') }}" alt="" style="width:120px; margin-top: 10px;">
                                </span>
                        </label>
                    </li>
                </ul>
                <div id="fieldRequisites" style="display: block;" class="output-requisites">
                    <p class="output-requisites__text">Реквизиты:</p>
                    <input type="text" name="requisites" class="output-requisites__input" placeholder="Введите номер">
                </div>
            </div>
            <div class="modal__buttons">
                <a href="javascript:void(0);" onclick="Payment.withdrawalOfFunds(this);" class="button button--border button--wide">Вывод средств</a>
            </div>
        </div>
    </div>

    <div class="modal conclusion" id="inputModal">
        <div class="modal__top">
            <h6 class="modal__title">Пополнение баланса</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Текущий баланс:</p>
                <p class="conclusion__balance-value">{{ getBalance(auth()->user()) }} <span>coins</span></p>
            </div>
            <div class="output-box">
                <div class="output-box__top">
                    <p class="output-box__text output-box__text--two">Сумма <br>пополнения</p>
                    <input type="number" class="output-box__field">
                    <p class="output-box__hint">
                        Максимальная сумма платежа: 15 000Р <br>
                        Минимальная сумма: 50Р
                    </p>
                </div>
            </div>
            <div class="conclusion__where">
                <p class="conclusion__where-title">Способы оплаты</p>
                <ul class="conclusion__where-list">
                    <li>
                        <label class="output-radio">
                            <input type="radio" checked="checked" name="inputType" value="1" onclick="show1();">
                            <span class="output-radio__box">
                                    <i class="ic-qiwi"></i>
                                </span>
                        </label>
                    </li>
                    <li>
                        <label class="output-radio">
                            <input type="radio" name="inputType" value="2" onclick="show2();">
                            <span class="output-radio__box">
                                    <i class="ic-sber"></i>
                                </span>
                        </label>
                    </li>
                    <li>
                        <label class="output-radio">
                            <input type="radio" name="inputType" value="3" onclick="show1();">
                            <span class="output-radio__box">
                                     <img src="{{ asset('img/yandex.png') }}" alt="" style="width:120px; margin-top: 10px;">
                                </span>
                        </label>
                    </li>
                    <li>
                        <label class="output-radio">
                            <input type="radio" name="inputType" value="4" onclick="show1();">
                            <span class="output-radio__box">
                                     <img src="{{ asset('img/mv.png') }}" alt="" style="width:120px; margin-top: 10px;">
                                </span>
                        </label>
                    </li>
                </ul>
                <div id="outputRequisites" class="output-requisites">
                    <p class="output-requisites__text">Реквизиты:</p>
                    <input type="text" name="requisites" class="output-requisites__input" placeholder="Введите номер">
                </div>
            </div>
            <div class="modal__buttons">
                <a href="javascript:void(0);" onclick="Payment.toUpAccount();" class="button">Пополнить</a>
            </div>
        </div>
    </div>

@endif

<div class="modal modal--help" id="helpModal">
    <div class="modal__top">
        <h6 class="modal__title">Правила игры Classic</h6>
        <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
    </div>
    <div class="modal__body">
        <div class="modal-help nano">
            <div class="nano-content rules-of-the-game">            	
                <p><b>Правила jackpot:</b><br>Игра имеющая общий банк, в который игроки делают производят взносы в виде "Coins" за 1 раунд можно внести не более 3 взносов. По окончанию таймера, разыгрывается общий банк между участниками игры. При внесении " Coins" в игру, игрок получает виртуальные билеты, один из которых становится выигрышным, за 1 внесенный "Coins" вы получите 1 билет, за 100 внесенных "Coins" вы получите 100 билетов в банке. Победитель определяется с помощью прокрутки ролла с номерами билетов, победитель забирает весь банк раунда. Данная игра имеет 2 режима Classic с внесением минимальных взносов от 300 coins и Small c внесением взносов до 300 коинс.</p>
				<p><b>Правила Coinflip:</b><br>Это игра, где происходит сражение двух игроков между собой, ставки вносятся в виде "Coins" у обоих имеются одинаковые шансы на победу 50/50. т.е если один игрок создал игру на 100 "Coins" то присоединиться к игре можно только сделав аналогичный взнос 100 "Coins" не больше и не меньше. По окончанию таймера разыгрывается банк между двумя участниками игры с помощью прокрутки ролла. При внесении "Coins" в игру, игрок получает виртуальные билеты, один из которых будет выигрышным, за 1 внесенный "Coins" вы получите 1 билет, за 100 внесенных "Coins" вы получите 100 билетов в банке.</p>
				<p><b>Правила King of the Hill:</b><br>Игра аукционного типа в которой после взноса каждого участника включается таймер с отчетом 20 секунд. Каждый последующий взнос должен быть больше предыдущего. Победителем объявляется участник сделавший последний взнос и по окончанию 20 секунд которого никто так и не перебил взносом более крупной суммы. Победитель забирает все сливки игры состоящие из суммы всех взносов участников данного раунда Classic - установлен фиксированный взнос с каждым последующим увеличением шага на 10 coins Senyor - установлен произвольный взнос с последующим увеличением шага на любую сумму.</p>
            </div>
        </div>
    </div>
</div>

<div class="modal modal--help" id="chatRules">
    <div class="modal__top">
        <h6 class="modal__title">Правила чата</h6>
        <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
    </div>
    <div class="modal__body">
        <div class="modal-chat nano">
            <div class="nano-content rules-chat">
                <ol>
					<li>Условия действия правил чата
						<ol>
							<li>Пользователи, заходя в чат, принимают на себя добровольные обязательства беспрекословно соблюдать нижеперечисленные правил.</li>
							<li>Незнание правил не освобождает от ответственности за их нарушения, убедительно рекомендуем с ними ознакомиться.</li>
						</ol>
					</li>
					<li>В чате разрешается:
						<ol>
							<li>Общаться, делиться информацией и обсуждать любые темы, а также совершать любые другие действия не нарушающие п3.</li>
							<li>Предлагать улучшения работы чата, жаловаться на плохую работу чата.</li>
						</ol>					
					</li>
					<li>В чате категорически запрещены:
						<ol>
							<li>Мошенничество и запрещенные программы.
								<ol>
									<li>Мошенничество. 
									<ul>- сообщения о любых финансовых сделках, продажах, обмене, дарении, передачи аккаунта во владения другого участника;
									<br>- создание фейков;
									<br>- категорически запрещено выдавать себя за представителя Администрации проекта.</li>
									<li>Запрещенные программы, распространение, использование.
									<br>- сообщения, содержащие в себе распространение, спрос и обсуждение запрещенных программ в чате.</li>
								</ol>
							</li>
							<li>Мат и завуалированный мат.
							<br>Открытый мат (слова, фразы) и завуалированный мат (эвфемизмы, недописанные слова, сокращения, слова с заменой одной или более букв, так и слова, со звездочками и т.п.), ненормативная лексика на любых языках, в любой кодировке, а так же слова оскорбительного характера - запрещены во всех их проявлениях!
								<ol>
									<li>Открытый мат.</li>
									<li>Завуалированный мат и слова оскорбительного характера </li>
								</ol>
							</li>
							<li>Дискриминация по расовому, национальному, религиозному или иному признаку/Националистические, расовые лозунги и высказывания.
								<ol>
									<li>Сообщения, призывающие к каким-либо действиям против других национальностей.</li>
									<li>Сообщения, в которых говорится о превосходстве одной нации или народа над другими.</li>
									<li>Сообщения, ведущие к межнациональной, расовой ненависти и розни, а также ведущие к провоцированию конфликтов на религиозной почве.</li>
								</ol>
							</li>
							<li>Пропаганда насилия, оружия, наркотиков, алкоголя и табакокурения и т.д.
							<br>сообщения, содержащие в себе пропаганду насилия, оружия, рекламу и открытые обсуждения любых наркотических средств, а так же пропаганда алкогольных напитков и табачных изделий, сообщения сексуально-порнографического характера, в том числе текстовые смайлы анатомического характера в чате запрещены.</li>
							<li>Запрещается прямо и косвенно оскорблять других пользователей в чате (использование литературных разговорных слов при оскорблении или хамстве пользователей чата), хамить пользователям и администрации чата, устраивать разборки/выяснения отношений.</li>
							<li>Спам, флуд, реклама.
							<li>Флуд
								<ol>
									<br>- отправка большого количества однотипных сообщений в течение 3 минут;
									<br>- использование более 3 (трех) любых символов или отдельных букв, повторение более 3 (трех) одинаковых слов подряд в одном сообщении;
									<br>- отправка нескольких сообщений, которые могут быть объединены в одно, написанных по одному слову или по одной букве.</li>
									<li>Спам/ Реклама/Ссылки:
									<br>- коммерческая и некоммерческая реклама.</li>
									<li>Троллинг, флейм, распространение личной информации.
									<br>Троллинг - сообщения провокационного характера, написанные с целью вызвать конфликты, споры, взаимные оскорбления.
									<br>Троллинг/обсуждение действий модераторов:
									<br>- множественные вопросы Модераторам, задаваемые за небольшой период времени, с целью затруднить работу модераторов;
									<br>- сообщения, содержащие в себе обсуждения действий Модератора и/или Администрации проекта или несогласие с ними.
									<br>Спам – реклама какого либо ресурса в явной форме.</li>
								</ol>
							</li>
							<li>Разглашение личной информации:
							<br>- сообщения, разглашающие любую личную информацию участника, Модератора, Администрации проекта без его согласия.</li>
							<li>Угрозы и оскорбления.
								<ol>
									<li>Угрозы:
									<br>- сообщения, содержащие в себе прямые или косвенные угрозы расправы в реальной жизни, вымогательство и шантаж.</li>
									<li>Оскорбления:
									<br>- оскорбление в любой форме, унижение человеческого достоинства и слова</li>
									<li>Оскорбление модератора:
									<br>- оскорбление Модератора, представителей Администрации или компании
									<br>- угрозы расправы или угрозы снятия с модераторского поста.</li>
								</ol>
							</li>
							<li>Заниматься попрошайничеством.</li>							
						</ol>
					</li>
					<li>В чате не рекомендуется
						<ol>
						<li>Злоупотреблять «Caps Lock»ом. </li>
						<li>Злоупотреблять смайлами (например, отправлять 5 или более сообщений подряд, содержащих только смайлы).</li>
						<li>Отправлять объемные цитаты, в том числе порезанные на части в основной чат. При необходимости Вы можете указать ссылку на источник информации.</li>
						<li>Наш чат - русскоязычный, следовательно, официальный язык общения – русский, не рекомендуются использовать при общении в нашем чате другие языки, допускаются исключения в некоторых случаях: написание текста на транслите, или международном языке (английский язык).</li>
						<li>Игнорировать предупреждения просьбы модераторов чата.</li>
						</ol>
					</li>
				</ol>
            </div>
        </div>
    </div>
</div>


<div class="modal modal--help" id="referralInfo">
</div>


<style>
    #auth span, #authLogin span, #authRegistration span, #authLogin a, #authRegistration a {
        display: block;
        text-align: center;
        padding-top: 20px;
        font-size: 24px;
        margin-bottom: 15px;
    }

    #authLogin a, #authRegistration a {
        cursor: pointer;
        padding-bottom: 15px;
    }

    #auth, #authLogin, #authRegistration {
        margin-top: auto !important;
    }

    #auth, #authRegistration {
        padding: 20px;
    }

    #authLogin input[type="text"], #authLogin input[type="password"], #authRegistration input[type="text"], #authRegistration input[type="password"] {
        width:200px;
        border-radius: 3px;
        margin-bottom: 5px;
        border:1px solid #ccc;
        outline: none;
        height: 35px;
        display: inline-block;
        outline: none;
        padding-left: 5px;
    }

    #authLogin li, #authRegistration li {
        list-style: none;
        display: inline-block;
        vertical-align: top;
        width:200px;
    }

    #authRegistration {
        padding-bottom: 40px;
        height: 450px !important;
    }

    #authRegistration input[type="submit"], #authLogin input[type="submit"] {
        max-width: 100px;
        margin-left: 10px;
        margin-bottom: 0;
        text-align: center;
        color: #fff;
        font-family: MyriadPro-Bold;
        font-size: 13px;
        line-height: 49px;
        height: 49px;
        padding: 0 15px;
        letter-spacing: .33px;
        -webkit-box-shadow: 0 4px 18px rgba(235,180,47,.15);
        box-shadow: 0 4px 18px rgba(235,180,47,.15);
        -webkit-border-radius: 2px;
        border-radius: 2px;
        background-color: #ffb83a;
        display: inline-block;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        text-transform: uppercase;
        -webkit-transition: .3s;
        transition: .3s;
        border:none;
        margin:0 auto;
        display: block;
        margin-top: 10px;
    }

    #authLogin form, #authRegistration form {
        text-align: center;
    }

    .error-message {
        color:red;
        font-size: 13px;
        display: block;
        padding-bottom: 10px;

    }

    #auth ul {
        text-align: center;
        margin-bottom: 30px;
    }

    #auth ul li {
        display: inline-block;
    }

    #auth {
        height:250px !important;
    }
    .hide-icon img {
        -webkit-filter: grayscale(100%);
        filter: grayscale(100%);
        -webkit-transition: .3s;
        transition: .3s;  
    }

</style>
<div class="modal modal--bet" id="auth">
    <span>Авторизация</span>
    <ul>
        <li>
            <a href="/oauth/vkontakte">
                <img src="{{ asset('img/icons-vk-96.png') }}" alt="">
            </a>
        </li>
        <li style="display: none"><a href="/oauth/facebook"><img src="{{ asset('img/icons-facebook-96.png') }}" alt=""></a></li>
        <li>
            <a href="javascript:void(0);" class="standart-auth"><img src="{{ asset('img/icons-email-96.png') }}" alt=""></a>
        </li>
    </ul>

    <label for="checkAuth" style="text-align: center; display: block; margin-top: -10px;"><input id="checkAuth" type="checkbox" checked> Я подтверждаю что мне есть 18 лет и я согласен с
        <a href="/terms-of-use.pdf" target="_blank">условиями сайта</a></label>


</div>


<div class="modal modal--help" id="authRegistration">
    @include('account.blocks.registration')
</div>

<div class="modal modal--help" id="authLogin">
    @include('account.blocks.auth')
</div>




