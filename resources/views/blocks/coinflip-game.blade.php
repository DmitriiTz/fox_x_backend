@if(!$game->participants->first()->color)
    <div class="coin-game" data-game-id="{{ $game->id }}">
        @if($game->participants->count() != 2)
            <div class="coin-game__action coin-game__action--orange">
                <a href="javascript:void(0);" onclick="MainFunction.checkAuthUser(this);" class="coin-game__button">ИГРАТЬ</a>
            </div>
        @else
            <div class="coin-game__left">
                <div class="coin-game__wrap2">
                    <div class="coin-game__box">
                        <img src="{{ asset($game->participants->sortByDesc('created_at')->first()->account->image) }}" alt="avatar" class="coin-game__avatar">
                        <i class="ic-coin-orange"></i>
                    </div>
                    <p class="coin-game__name">{{ $game->participants->sortByDesc('created_at')->first()->account->name }}</p>
                </div>
                <div class="coin-game__wrap">
                    <p class="coin-game__text">сделал ставку </p>
                    <p class="coin-game__value">{{ $game->participants->sortByDesc('created_at')->first()->cash }} <span>COINS</span></p>
                </div>
            </div>
        @endif
        <div class="coin-game__eye">
          
          @if(auth()->check() && auth()->user()->id != 4)
            <a href="javascript:void(0);" data-izimodal-open="#waitPlayer" onclick="MainFunction.showGame(this);"
               data-izimodal-transitionin="fadeInDown" style="display:block; width:100%; height:100%;"> 
               @else
               <a data-izimodal-open="#auth" data-izimodal-transitionin="fadeInDown" style="display:block; width:100%; height:100%;">
               @endif
                <i class="ic-eye"></i></a>
        </div>
        <div class="coin-game__right">
            <div class="coin-game__wrap">
                <p class="coin-game__value">{{ $game->participants->first()->cash }} <span>COINS</span></p>
                <p class="coin-game__text">сделал ставку </p>
            </div>
            <div class="coin-game__wrap2">
                <p class="coin-game__name">{{ $game->participants->first()->account->name }}</p>
                <div class="coin-game__box">
                    <img src="{{ asset($game->participants->first()->account->image) }}" alt="avatar" class="coin-game__avatar">
                    <i class="ic-coin-blue"></i>
                </div>
            </div>
        </div>
    </div>

@else
    <div class="coin-game" data-game-id="{{ $game->id }}">
        <div class="coin-game__left">
            <div class="coin-game__wrap2">
                <div class="coin-game__box">
                    <img src="{{ asset($game->participants->first()->account->image) }}" alt="avatar" class="coin-game__avatar">
                    <i class="ic-coin-orange"></i>
                </div>
                <p class="coin-game__name">{{ $game->participants->first()->account->name }}</p>
            </div>
            <div class="coin-game__wrap">
                <p class="coin-game__text">сделал ставку </p>
                <p class="coin-game__value">{{ $game->participants->first()->cash }} <span>COINS</span></p>
            </div>
        </div>
        <div class="coin-game__eye">

            @if(auth()->check() && auth()->user()->id != 4)
<a href="#waitPlayer" data-izimodal-open="#waitPlayer" data-izimodal-transitionin="fadeInDown" onclick="MainFunction.showGame(this);"
               style="display:block; width:100%; height:100%;">
@else
               <a data-izimodal-open="#auth" data-izimodal-transitionin="fadeInDown" style="display:block; width:100%; height:100%;">
               @endif
               <i class="ic-eye"></i></a>
        </div>
        @if($game->participants->count() != 2)
            <div class="coin-game__action coin-game__action--blue">
                <a href="javascript:void(0);" onclick="MainFunction.checkAuthUser(this);" class="coin-game__button">ИГРАТЬ</a>
            </div>
        @else
            <div class="coin-game__right">
                <div class="coin-game__wrap">
                    <p class="coin-game__value">{{ $game->participants->sortByDesc('created_at')->first()->cash }} <span>COINS</span></p>
                    <p class="coin-game__text">сделал ставку </p>
                </div>
                <div class="coin-game__wrap2">
                    <p class="coin-game__name">{{ $game->participants->sortByDesc('created_at')->first()->account->name }}</p>
                    <div class="coin-game__box">
                        <img src="{{ asset($game->participants->sortByDesc('created_at')->first()->account->image) }}" alt="avatar" class="coin-game__avatar">
                        <i class="ic-coin-blue"></i>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif