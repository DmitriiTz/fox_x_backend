@if(auth()->check() && auth()->user()->id != 4)
    <!-- Balance block -->
    <div class="balance @if(isset($isMobile)) mobile-show @endif">
        <p class="balance__title">Баланс:</p>
        <p class="balance__value"><i id="blockBalance">{{ getBalance(auth()->user()) }}</i><span>COINS</span></p>
        <div class="balance__action">
            <a class="button balance__input" data-izimodal-open="#inputModal" data-izimodal-transitionin="fadeInDown">ПОПОЛНИТЬ</a>
            <a class="button button--gray balance__output @if(checkSessionTheme()) button--dark @endif" data-izimodal-open="#outputModal" data-izimodal-transitionin="fadeInDown">ВЫВЕСТИ</a>
        </div>
    </div>
@endif
