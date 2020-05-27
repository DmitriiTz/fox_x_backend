<div class="crash-player-table" id="app">
    @if($ubets)
        <div class="crash-table-body-name name">
            <div class="crash-body-title">Пользователь</div>
            <div class="crash-body-title">Ставка</div>
            <div class="crash-body-title">Коэффициент</div>
            <div class="crash-body-title">Выигрыш</div>
        </div>
        <div class="block-state-crash">
            @foreach($ubets as $bet)
                <div class="result-table crash-table-body user-{{$bet['user']->id}}" data-cashout="{{$bet['bet']->number}}" data-bet="{{$bet['bet']->price}}" data-user="{{$bet['user']->id}}">
                    <div class="crash-body-title img">
                        <img src="{{$bet['user']->image}}" alt="avatar" width="20" height="20">
                        <div class="name-user">{{$bet['user']->name}}</div>
                    </div>
                    <div class="crash-body-title bet">{{$bet['bet']->price}}</div>
                    <div class="crash-body-title cash">-</div>
                    <div class="crash-body-title profit">-</div>
                </div>
            @endforeach
        </div>
        <users-online></users-online>
        <stop-game></stop-game>
    @else
        <div style="text-align: center;">
            Нет активных игроков
        </div>
    @endif

</div>


