<li class="coin-game-history__item">
    <div class="coin-game-history__left">
        <div class="coin-game-history__box">
            @if($game->winner_ticket <= 50)
                <i class="ic-coin-orange"></i>
            @else
                <i class="ic-coin-blue"></i>
            @endif
            <img src="{{ asset($game->winner->image) }}" alt="avatar" class="coin-game-history__winner">
            <img src="{{ asset($game->looser->account->image) }}" alt="avatar" class="coin-game-history__loser">
        </div>
        <p class="coin-game-history__name">{{ $game->winner->name.' '.$game->winner->last_name }}</p>
        <div class="coin-game-history__wrap">
            <p class="coin-game-history__text">выиграл </p>
            <p class="coin-game-history__value">{{ $game->participants()->sum('cash') }} <span>COINS</span></p>
        </div>
    </div>
    <div class="coin-game-history__right">
        <a href="#" class="coin-game-history__number">Classic @if($game) #{{ $game->id }} @endif</a>
         @if(auth()->check() && auth()->user()->id != 4)
        <a class="coin-game-history__see" href="#waitPlayer" data-izimodal-open="#waitPlayer"
           data-izimodal-transitionin="fadeInDown" onclick="MainFunction.showGame(this, {{ $game->id }});">
           @else
               <a class="coin-game-history__see" data-izimodal-open="#auth" data-izimodal-transitionin="fadeInDown">
               @endif<i class="ic-eye"></i></a>
    </div>
</li>