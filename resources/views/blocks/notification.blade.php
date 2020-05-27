    <div class="notification">
        <p class="notification__title">Звуковые уведомления</p>
        <form class="notification__form">
            <label class="notification__radio">
                <input type="radio" id="isMusic" name="sound" @if(session('is_music') == 1) checked @endif>
                <span class="notification__check"></span>
                <span class="notification__name">Включить</span>
            </label>
            <label class="notification__radio">
                <input type="radio" id="isMusic" name="sound" @if(!session('is_music')) checked @endif>
                <span class="notification__check"></span>
                <span class="notification__name">Выключить</span>
            </label>
        </form>
    </div>
