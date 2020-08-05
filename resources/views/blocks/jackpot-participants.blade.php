
@if($game)

    @php
        $bank = $game->participants->sum('cash');

        $tempList = array();
        //$gameTemp = array();

        $gameTemp = json_decode(json_encode($game));

        foreach ($gameTemp->participants as $key => $participant) {

            if(in_array($participant->account->id,$tempList))
            {
               
                $gameTemp->participants[array_search($participant->account->id,$tempList)]->cash +=  $participant->cash;

                unset($gameTemp->participants[$key]);
            }
             array_push($tempList, $participant->account->id);
        }

    @endphp

    @foreach($gameTemp->participants as $key => $participant)

   
        <div class="winners__slider-item winners__slider-item--" style="border-bottom:3px solid {{$participant->color}}">
            <img src="{{ asset($participant->account->image) }}" alt="avatar" class="winners__slider-avatar">
            <span class="winners__slider-percent">{{ $bank ? round($participant->cash * 100 / $bank, 2) : 0 }}%</span>
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
