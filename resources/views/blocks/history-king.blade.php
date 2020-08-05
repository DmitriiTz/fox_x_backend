<li class="game-history__item">
    <div class="game-history__left">
        <div class="game-history__avatar">
            <img src="{{ asset($game->winner->image) }}" alt="avatar">
        </div>
        <p class="game-history__name">{{ $game->winner->name }} </p>
        <span class="game-history__text">выиграл</span>
        <p class="game-history__result">{{ $game->participants->sum('cash') }} <span>COINS</span></p>
    </div>
    <div class="game-history__right">
        <p class="game-history__title">{{ $game->type->name }} #{{ $game->id }}</p>
        <p class="game-history__time"><i class="ic-clock"></i>{{ \Carbon\Carbon::parse($game->end_game_at)->diffForHumans() }}</p>
    </div>
</li>