<div class="choose-winner__slider">
    @if($game)

    @php
        $bank = $game->participants->sum('cash');

        $tempList2 = array();
        //$gameTemp2 = array();

        $gameTemp2 = json_decode(json_encode($game));

        foreach ($gameTemp2->participants as $key => $participant) {

            if(in_array($participant->account->id,$tempList2))
            {
               
                $gameTemp2->participants[array_search($participant->account->id,$tempList2)]->cash +=  $participant->cash;

                unset($gameTemp2->participants[$key]);
            }
             array_push($tempList2, $participant->account->id);
        }

    @endphp

    @foreach($gameTemp2->participants as $key => $participant)

                <div class="choose-winner__slider-item choose-winner__slider-item--" data-count="{{ $participant->account->id }}">
                    <img src="{{ asset($participant->account->image) }}" alt="avatar" data-percent="{{ $bank ? round($participant->cash * 100 / $bank, 2) : 0 }}" class="choose-winner__slider-avatar--" style="border-bottom:3px solid {{$participant->color}}">
                </div>
     

        @endforeach
    @endif
</div>