<!DOCTYPE html>
<html lang="ru">

<head>

    <meta charset="utf-8">

    <title>Статистика</title>
    <meta name="description" content="">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:image" content="path/to/image.jpg">
    <link rel="shortcut icon" href="{{ asset('img/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon/apple-touch-icon.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('img/favicon/apple-touch-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('img/favicon/apple-touch-icon-114x114.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/main.min.css') }}">

    <script src="https://use.fontawesome.com/edf72e88c6.js"></script>

    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}?v={{ time() }}">

</head>

<body>

<!-- Start header -->
<header class="header">
    <div class="header__left">
        <a href="/" class="header__logo">Logo</a>
        <ul class="header__breadcrumbs breadcrumbs">
            <li class="breadcrumbs__item">
                <a href="{{ route('admin.admin') }}" class="breadcrumbs__link">Главная /</a>
            </li>
            <li class="breadcrumbs__item">
                <a class="breadcrumbs__link breadcrumbs__link--active">{{ $page }}</a>
            </li>
        </ul>
        <h3 class="header__page">{{ $page }}</h3>
    </div>
    @if(Route::currentRouteName() == 'admin.output' || Route::currentRouteName() == 'admin.wallet')
        <div class="header__right">

        <form class="search">
            <input type="text" name="search" value="{{ request()->search }}" class="search__inp" placeholder="Введите запрос">
            <button class="search__btn">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.8616 15.184L11.9725 11.2958C13.0157 10.0967 13.6474 8.53215 13.6474 6.82217C13.6474 3.05809 10.5851 0 6.82369 0C3.05877 0 0 3.06164 0 6.82217C0 10.5827 3.06232 13.6443 6.82369 13.6443C8.53404 13.6443 10.0989 13.0129 11.2983 11.9698L15.1874 15.8581C15.2797 15.9503 15.4039 16 15.5245 16C15.6452 16 15.7693 15.9539 15.8616 15.8581C16.0461 15.6736 16.0461 15.3685 15.8616 15.184ZM0.954535 6.82217C0.954535 3.5867 3.58749 0.957871 6.82014 0.957871C10.0563 0.957871 12.6857 3.59024 12.6857 6.82217C12.6857 10.0541 10.0563 12.69 6.82014 12.69C3.58749 12.69 0.954535 10.0576 0.954535 6.82217Z" fill="white"/>
                </svg>
            </button>
        </form>

        <div class="calendar">
            <form class="calendar__form">
                <input type="text" name="calendar_range" id="" autocomplete="off" class="calendar__inp" placeholder="Выбор периода">
                <button class="calendar__btn">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.125 1.25H13.375V0H12.125V1.25H3.875V0H2.625V1.25H1.875C0.841125 1.25 0 2.09113 0 3.125V14.125C0 15.1589 0.841125 16 1.875 16H14.125C15.1589 16 16 15.1589 16 14.125V3.125C16 2.09113 15.1589 1.25 14.125 1.25ZM14.75 14.125C14.75 14.4696 14.4696 14.75 14.125 14.75H1.875C1.53038 14.75 1.25 14.4696 1.25 14.125V5.875H14.75V14.125ZM14.75 4.625H1.25V3.125C1.25 2.78038 1.53038 2.5 1.875 2.5H2.625V3.75H3.875V2.5H12.125V3.75H13.375V2.5H14.125C14.4696 2.5 14.75 2.78038 14.75 3.125V4.625Z" fill="white"/>
                        <path d="M3.625 7.1875H2.375V8.4375H3.625V7.1875Z" fill="white"/>
                        <path d="M6.125 7.1875H4.875V8.4375H6.125V7.1875Z" fill="white"/>
                        <path d="M8.625 7.1875H7.375V8.4375H8.625V7.1875Z" fill="white"/>
                        <path d="M11.125 7.1875H9.875V8.4375H11.125V7.1875Z" fill="white"/>
                        <path d="M13.625 7.1875H12.375V8.4375H13.625V7.1875Z" fill="white"/>
                        <path d="M3.625 9.6875H2.375V10.9375H3.625V9.6875Z" fill="white"/>
                        <path d="M6.125 9.6875H4.875V10.9375H6.125V9.6875Z" fill="white"/>
                        <path d="M8.625 9.6875H7.375V10.9375H8.625V9.6875Z" fill="white"/>
                        <path d="M11.125 9.6875H9.875V10.9375H11.125V9.6875Z" fill="white"/>
                        <path d="M3.625 12.1875H2.375V13.4375H3.625V12.1875Z" fill="white"/>
                        <path d="M6.125 12.1875H4.875V13.4375H6.125V12.1875Z" fill="white"/>
                        <path d="M8.625 12.1875H7.375V13.4375H8.625V12.1875Z" fill="white"/>
                        <path d="M11.125 12.1875H9.875V13.4375H11.125V12.1875Z" fill="white"/>
                        <path d="M13.625 9.6875H12.375V10.9375H13.625V9.6875Z" fill="white"/>
                    </svg>
                </button>
            </form>
            <div class="calendar__modal calendar-box">
                <div class="calendar-box__top">
                    <p class="calendar-box__title">Выберите диапзон дат</p>
                    <a class="calendar-box__close"></a>
                </div>
                <div class="calendar-box__links">
                    <p class="calendar-box__text">Быстрый выбор</p>
                    <form class="calendar-box__choose">
                        <input type="radio" name="calendar-period" value="{{ \Carbon\Carbon::today()->format('d.m.Y') }}  {{ \Carbon\Carbon::now()->format('d.m.Y') }}"
                               id="period-today" class="choose-period__inp">
                        <label for="period-today" class="choose-period__title">Сегодня</label>
                        <input type="radio" name="calendar-period"
                               value="{{ \Carbon\Carbon::yesterday()->format('d.m.Y') }} — {{ \Carbon\Carbon::yesterday()->addDay(1)->format('d.m.Y') }}"
                               id="period-yesterday"
                               class="choose-period__inp"><label for="period-yesterday" class="choose-period__title">Вчера</label>
                        <input type="radio" name="calendar-period"
                               value="{{ \Carbon\Carbon::now()->subWeek()->format('d.m.Y') }} — {{ \Carbon\Carbon::now()->format('d.m.Y') }}"
                               id="period-week" class="choose-period__inp"><label for="period-week" class="choose-period__title">Эта неделя</label>
                        <input type="radio"
                               value="{{ \Carbon\Carbon::now()->subMonth()->format('d.m.Y') }} — {{ \Carbon\Carbon::now()->format('d.m.Y') }}"
                               name="calendar-period" id="period-month" class="choose-period__inp"><label for="period-month" class="choose-period__title">Этот месяц</label>
                        <input type="radio" name="calendar-period"
                               value="{{ \Carbon\Carbon::now()->subYear()->format('d.m.Y') }} — {{ \Carbon\Carbon::now()->format('d.m.Y') }}"
                               id="period-year" class="choose-period__inp"><label for="period-year" class="choose-period__title">Этот год</label>
                    </form>
                </div>
                <div class="calendar-box__body"></div>
            </div>
        </div>
    </div>
    @endif

    @if(Route::currentRouteName() == 'admin.users')
        <div class="header__right">

            <form class="search" action="{{ route('admin.users') }}">
                <input type="text" name="search" value="{{ request()->search }}" class="search__inp" placeholder="Введите запрос">
                <button class="search__btn">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.8616 15.184L11.9725 11.2958C13.0157 10.0967 13.6474 8.53215 13.6474 6.82217C13.6474 3.05809 10.5851 0 6.82369 0C3.05877 0 0 3.06164 0 6.82217C0 10.5827 3.06232 13.6443 6.82369 13.6443C8.53404 13.6443 10.0989 13.0129 11.2983 11.9698L15.1874 15.8581C15.2797 15.9503 15.4039 16 15.5245 16C15.6452 16 15.7693 15.9539 15.8616 15.8581C16.0461 15.6736 16.0461 15.3685 15.8616 15.184ZM0.954535 6.82217C0.954535 3.5867 3.58749 0.957871 6.82014 0.957871C10.0563 0.957871 12.6857 3.59024 12.6857 6.82217C12.6857 10.0541 10.0563 12.69 6.82014 12.69C3.58749 12.69 0.954535 10.0576 0.954535 6.82217Z" fill="white"/>
                    </svg>
                </button>
            </form>


        </div>
    @endif

    @if(Route::currentRouteName() == 'admin.promocodes')
        <div class="header__right">
            <div class="promocode">
                <p class="promocode__title">Генерация <br>	промокодов</p>
                <form class="promocode__form" method="post" action="{{ route('admin.promocodes') }}">
                    {{ csrf_field() }}
                    <input type="text" name="promocode-sum" id="" class="promocode__inp" placeholder="Введите сумму">
                    <input type="text" name="promocode-value" id="" class="promocode__inp" placeholder="Введите количество промокодов">
                    <button class="promocode__btn">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.7657 0.319082C15.4533 -0.106361 14.9468 -0.106361 14.6343 0.319082L5.04983 13.3703L1.3657 8.35369C1.0533 7.92824 0.546797 7.92829 0.234328 8.35369C-0.0781094 8.77909 -0.0781094 9.46878 0.234328 9.89422L4.48414 15.6811C4.79645 16.1065 5.30333 16.1062 5.61552 15.6811L15.7657 1.85966C16.0781 1.43426 16.0781 0.744525 15.7657 0.319082Z" fill="white"/>
                        </svg>
                    </button>
                </form>
            </div>
            <form class="search" action="{{ route('admin.promocodes') }}">
                <input type="text" name="search" class="search__inp" placeholder="Поиск промокодов">
                <button class="search__btn">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.8616 15.184L11.9725 11.2958C13.0157 10.0967 13.6474 8.53215 13.6474 6.82217C13.6474 3.05809 10.5851 0 6.82369 0C3.05877 0 0 3.06164 0 6.82217C0 10.5827 3.06232 13.6443 6.82369 13.6443C8.53404 13.6443 10.0989 13.0129 11.2983 11.9698L15.1874 15.8581C15.2797 15.9503 15.4039 16 15.5245 16C15.6452 16 15.7693 15.9539 15.8616 15.8581C16.0461 15.6736 16.0461 15.3685 15.8616 15.184ZM0.954535 6.82217C0.954535 3.5867 3.58749 0.957871 6.82014 0.957871C10.0563 0.957871 12.6857 3.59024 12.6857 6.82217C12.6857 10.0541 10.0563 12.69 6.82014 12.69C3.58749 12.69 0.954535 10.0576 0.954535 6.82217Z" fill="white"/>
                    </svg>
                </button>
            </form>
        </div>
    @endif

</header>

@yield('content')

<script src="{{ asset('js/admin/scripts.min.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script src="{{ asset('js/admin/common.js') }}"></script>

<script>
//    $('#table1-body select').selectric({
//        onChange: function() {
//            console.log('test');
//            var el = $(this);
//            var name = el.attr('name');
//            var val = el.val();
//
//            $('#table1-body input[name="user[]"]:checked').each(function(indx, element) {
//                var currentElement = $(element);
//                currentElement.closest('tr').find('select[name="'+ name +'"]').val(val);
//                $('#table1-body select[name="'+ name +'"]').selectric('refresh');
//            });
//
//        }
//    });
</script>
@yield('js')
</body>
</html>
