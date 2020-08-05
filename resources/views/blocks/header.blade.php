<!-- Start header -->
<header class="header">
    <div class="header__left">
        <a href="{{ route('home') }}" class="header__logo">
            @if(checkSessionTheme())
                <img src="{{ asset('img/logo-dark.png') }}" alt="logo">
            @else
      
          <img src="{{ asset('img/logo.png') }}" alt="logo">
            @endif
        </a>

        @if(auth()->check() && auth()->user()->id != 4)
            <a class="menu-open">
                <i class="ic-burger"></i>
            </a>
            <a class="menu-close">
                <i class="ic-close"></i>
            </a>
        @else
            <a class="button chat__bottom-button btn-registration mobile-show" data-izimodal-open="#auth" data-izimodal-transitionin="fadeInDown">ВОЙТИ</a>
        @endif
    </div>
     <nav class="nav mobile-show" >
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="{{ route('home') }}" class="nav__link @if(Route::currentRouteName() == 'home') nav__link--active @endif"><i class="ic-nav1"></i>JACKPOT</a>
                </li>
                <li class="nav__item">
                    <a href="{{ route('coinflip') }}" class="nav__link @if(Route::currentRouteName() == 'coinflip') nav__link--active @endif"><i class="ic-nav2"></i>COINFLIP</a>
                </li>
                <li class="nav__item">
                    <a href="{{ route('king-of-the-hill') }}" class="nav__link @if(Route::currentRouteName() == 'king-of-the-hill') nav__link--active @endif">
                        <i class="ic-nav3"></i>KING OF THE HILL</a>
                </li>
                <li class="nav__item">
                    <a href="{{ route('crash') }}" class="nav__link @if(Route::currentRouteName() == 'crash') nav__link--active @endif">
                        <i class="ic-nav5"></i>CRASH</a>
                </li>
            </ul>
        </nav>
    <div class="header__center">
        <nav class="nav">
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="{{ route('home') }}" class="nav__link @if(Route::currentRouteName() == 'home') nav__link--active @endif"><i class="ic-nav1"></i>JACKPOT</a>
                </li>
                <li class="nav__item">
                    <a href="{{ route('coinflip') }}" class="nav__link @if(Route::currentRouteName() == 'coinflip') nav__link--active @endif"><i class="ic-nav2"></i>COINFLIP</a>
                </li>
                <li class="nav__item">
                    <a href="{{ route('king-of-the-hill') }}" class="nav__link @if(Route::currentRouteName() == 'king-of-the-hill') nav__link--active @endif">
                        <i class="ic-nav3"></i>KING OF THE HILL</a>
                </li>
                <li class="nav__item">
                    <a href="{{ route('crash') }}" class="nav__link @if(Route::currentRouteName() == 'crash') nav__link--active @endif">
                        <i class="ic-nav5"></i>CRASH</a>
                </li>
                <li class="nav__item">
                    <a href="{{ route('help') }}" class="nav__link @if(Route::currentRouteName() == 'help') nav__link--active @endif"><i class="ic-nav4"></i>HELP</a>
                </li>
            </ul>
        </nav>
        <div class="social-menu__wrap--adaptive">
            <a class="social-menu__open">
                <span></span>
            </a>
            <a class="social-menu__close">
                <i class="ic-close"></i>
            </a>
            <ul class="social-menu">
                <li class="social-menu__item">
                    <a href="https://t.me/fox_x_ru" class="social-menu__link" title="telegram">
                        <i class="ic-telegram"></i>
                    </a>
                </li>
                <li class="social-menu__item" title="vk">
                    <a href="https://vk.com/foxxru" class="social-menu__link">
                        <i class="ic-vk"></i>
                    </a>
                </li>
                <li class="social-menu__item" title="vk-chat">
                    <a href="https://vk.com/write-177183494" class="social-menu__link">
                        <i class="ic-vk-blue"></i>
                    </a>
                </li>
                <li class="social-menu__item">
                    <a href="javascript:void(0);" onclick="MainFunction.activateTheme(this);"
                       data-current-theme="{{ session('theme') ? session('theme') : 'light' }}" class="social-menu__link">
                        @if(checkSessionTheme())
                            <i class="ic-sun"></i>
                        @else
                            <i class="ic-moon"></i>
                        @endif
                    </a>
                </li>
                <li class="social-menu__item">
                    <a class="social-menu__link">
                        <i class="ic-user"></i>
                        <span></span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="social-menu__wrap">
            <ul class="social-menu">
                <li class="social-menu__item">
                    <a href="https://t.me/fox_x_ru" class="social-menu__link" title="telegram">
                        <i class="ic-telegram"></i>
                    </a>
                </li>
                <li class="social-menu__item" title="vk">
                    <a href="https://vk.com/foxxru" class="social-menu__link">
                        <i class="ic-vk"></i>
                    </a>
                </li>
                <li class="social-menu__item" title="vk-chat">
                    <a href="https://vk.com/write-177183494" class="social-menu__link">
                        <i class="ic-vk-blue"></i>
                    </a>
                </li>
                <li class="social-menu__item">
                    <a href="javascript:void(0);" onclick="MainFunction.activateTheme(this);"
                       data-current-theme="{{ session('theme') ? session('theme') : 'light' }}" class="social-menu__link">
                        @if(checkSessionTheme())
                            <i class="ic-sun"></i>
                        @else
                            <i class="ic-moon"></i>
                        @endif
                    </a>
                </li>
                <li class="social-menu__item">
                    <a class="social-menu__link">
                        <i class="ic-user"></i>
                        <span></span>
                    </a>
                </li>
            </ul>
        </div>

    </div>
    @if(auth()->check() && auth()->user()->id != 4)

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

        <div class="header__right user">
            <div class="user__ava">
                <img src="{{ asset(auth()->user()->image) }}" alt="avatar">
            </div>
            <div class="user__info">
                <span class="user__level">{{ getLevel(auth()->user()) }} LVL.</span>
                <span class="user__exp">{{ $experienceHeader }} EXP.</span>
            </div>
            <div class="user__left">
                <a class="user__open">
                    <i class="ic-burger"></i>
                </a>
                <a class="user__close">
                    <i class="ic-close"></i>
                </a>
            </div>
            <div class="user__dropdown dropdown">
                <div class="dropdown__top">
                    <p class="dropdown__level">{{ getLevel(auth()->user()) }} LEVEL.</p>

                    <p class="dropdown__exp">{{ $experienceHeader }} / <span>1000 EXP.</span></p>
                    <div class="dropdown__timeline" style="width:{{ color(getExperience(auth()->user())) }}%"></div>
                </div>
                <ul class="dropdown__menu">
                    <li><a href="{{ route('account.profile') }}">Профиль</a></li>
                    <li><a href="{{ route('account.profile') }}#history">История игр</a></li>
                    <li><a href="{{ route('account.daily-bonus') }}">Ежедневный бонус</a></li>
                    <li><a data-izimodal-open="#outputModal" data-izimodal-transitionin="fadeInDown">Вывести средства <i class="ic-coin"></i></a></li>
                    <li><a href="{{ route('account.profile') }}">Промокоды</a></li>
                    <li><a href="{{ route('logout') }}">Выйти</a></li>
                </ul>
            </div>
        </div>
        <div class="user--mobile">
            <div class="user__top user__top--active">
                <div class="user__ava">
                    <img src="{{ asset(auth()->user()->image) }}" alt="avatar">
                </div>
                <div class="user__info">
                    <span class="user__level">{{ getLevel(auth()->user()) }} LVL.</span>
                    <span class="user__exp">{{ $experienceHeader }} EXP.</span>
                </div>
                 <div class="user__info coins">
                    <span>{{getBalance(auth()->user())}} <i class="ic-coin" ></i></span>
                 </div>
                <div class="user__left"  style="display: none;">
                    <a class="user__arrow"></a>
                </div>
            </div>
            <div class="dropdown--mobile dropdown--mobile--active">
                <div class="dropdown__top">
                    <p class="dropdown__level">{{ getLevel(auth()->user()) }} LEVEL.</p>
                    <p class="dropdown__exp">{{ getExperience(auth()->user()) }} / <span>1000 EXP.</span></p>
                    <div class="dropdown__timeline" style="width:{{ color(getExperience(auth()->user())) }}%"></div>
                </div>
                <ul class="dropdown__menu">
                    <li><a href="{{ route('account.profile') }}">Профиль</a></li>
                    <li><a href={{ route('account.profile') }}#history">История игр</a></li>
                    <li><a href="{{ route('account.daily-bonus') }}">Ежедневный бонус</a></li>
                    <li><a data-izimodal-open="#inputModal" data-izimodal-transitionin="fadeInDown">Пополнить <i class="ic-coin"></i></a></li>
                    <li><a data-izimodal-open="#outputModal" data-izimodal-transitionin="fadeInDown">Вывести средства <i class="ic-coin"></i></a></li>
                    <li><a href="{{ route('account.profile') }}">Промокоды</a></li>
                    
                    <li><a href="{{ route('logout') }}">Выйти</a></li>
                </ul>
            </div>
        </div>
    @else
        <a class="button chat__bottom-button btn-registration" data-izimodal-open="#auth" data-izimodal-transitionin="fadeInDown">ВОЙТИ</a>
    @endif
</header>
<!-- End header -->