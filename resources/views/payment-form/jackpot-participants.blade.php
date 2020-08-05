
@if($game)

    @php
        $bank = $game->participants->sum('cash');
        $tempList = array();
        foreach ($game->participants as $key => $participant) {

            if(!in_array((string)$participant->account->id,$tempList))
            {
                array_push($tempList, $participant->account->id);
            }
            else
            {
                $game->participants[array_search($participant->account->id,$tempList)]->cash +=  $participant->cash;
                unset($game->participants[$key]);
            }
        }
    @endphp

    @foreach($game->participants as $key => $participant)

   
        <div class="winners__slider-item winners__slider-item--" style="border-bottom:3px solid {{$participant->color}}">
            <img src="{{ asset($participant->account->image) }}" alt="avatar" class="winners__slider-avatar">
            <span class="winners__slider-percent">{{ round($participant->cash * 100 / $bank, 2) }}% {{json_encode($tempList)}}</span>
        </div>
    @endforeach
@else
    
        <div class="winners__slider-item winners__slider-item--">
            <img src="https://fox-x.ru/img/fox.png" alt="avatar" class="winners__slider-avatar">
            <span class="winners__slider-percent"></span>
        </div>
        <div class="winners__slider-item winners__slider-item--">
            <img src="https://fox-x.ru/img/fox.png" alt="avatar" class="winners__slider-avatar">
            <span class="winners__slider-percent"></span>
        </div>
@endif
