<div class="left">
    {{--
    @if(isset($info->id))
    <small style="color: brown">
        {{print_r(date('H:i:s', $info->stop_game),1)}}
        <br>
        {{print_r(date('H:i:s', time()),1)}}
        <br>
        <br>
        <b>stop_game:</b> {{print_r($info->stop_game,1)}}<br>
        <b>mode:</b> {{print_r($mode,1)}}<br>
        <b>status:</b> {{print_r($info->status,1)}}<br>
        <b>rand_number:</b> {{print_r($info->rand_number,1)}}<br>
        <b>profit:</b> {{print_r($info->profit,1)}}<br>
        <b>updated_at:</b> {{print_r($info->updated_at,1)}}<br>
    </small>
        <br>
    @endif
         --}}

    @if(isset($info->id))
        <div class="info">
            Игра №<b>{{$info->id}}</b>
        </div>
        <div class="total">
            @if(isset($info->rand_number) and $info->rand_number > 0)
                @php
                    if($info->rand_number < 10) $co = 0;
                    elseif($info->rand_number >= 5) $co = 0.2;
                    elseif($info->rand_number >= 10) $co = 1;
                    elseif($info->rand_number >= 20) $co = 2;
                    elseif($info->rand_number >= 50) $co = 4;
                    elseif($info->rand_number >= 60) $co = 8;
                    elseif($info->rand_number >= 90) $co = 10;
                    elseif($info->rand_number >= 150) $co = 20;
                    elseif($info->rand_number >= 250) $co = 30;
                    $rand_number = $info->rand_number + $co;

                    if($rand_number > $info->profit) $rand_number = $info->profit;
                    if($mode == 1 && $info->status == 3) $rand_number = $info->profit;

                @endphp
                ~<snan id="total_x" title="Текущий коэфициент">{{number_format((float)$rand_number, 2, '.', '')}}</snan>X
            @else
                <snan id="total_x" title="Текущий коэфициент">0.00</snan>X
            @endif
            <small id="total_x" style="color:
            @if($info->profit)
                #1f92bf
            @else
                red
            @endif
                " title="Профит игры">
                {{number_format((float)$info->profit, 2, '.', '')}}X
            </small>
        </div>
    @endif
    @if(isset($bets))
        <div class="info">Онлайн в Crash: {{count($bets)}}</div>
    @endif
    <script>
        var co = 0;
        var intS  = 0;
    </script>


        <button onclick="stop_game();" class="btn btn-stop-game">Остановить график?</button>

</div>
<div class="right">
    @if(isset($bets))
        @if(!$bets->isEmpty())
            <div class="table-bets">
                <table class="table table-hover" style="width: 100%; border-spacing: 0; margin-top: 0;">
                    <thead>
                    <tr>
                        <th scope="col">Пользователь</th>
                        <th scope="col">Коэффициент</th>
                        <th scope="col">Ставка</th>
                        <th scope="col">Возможный выигрыш</th>
                    </tr>
                    </thead>
                    <tbody id="result">
                    @foreach($bets as $bet)
                        <?php
                        if($info->profit > $bet->number){
                            $z = '+';
                        }else{
                            $z = '-';
                        }
                        if($info->rand_number > $bet->number) {
                            $status = 'text-success';
                        } else {
                            $status = 'text-danger';
                        }
                        $user =  \App\User::find($bet->user_id);
                        ?>

                        <tr>
                            <td scope="row">{{$user->name}} ({{$bet->user_id}})</td>
                            <td>{{$bet->number}}X</td>
                            <td>{{$bet->price}}</td>
                            <td class="{{$status}}">{{$z}}{{$bet->number * $bet->price - $bet->price}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif
</div>


