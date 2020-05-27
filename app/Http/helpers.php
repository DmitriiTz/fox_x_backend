<?php

function checkSessionTheme() {
    if(session('theme') && session('theme') == 'dark') {
        return false;
    }
    else {
        return true;
    }
}

function getLevel($user) {

    $lvl = floor($user->experience / 1000) ? floor($user->experience / 1000) : 1;
    if($user->experience % 1000 == 0) {

        /*if($lvl != 80) {
            $lvl += 1;
        }*/

    }


    return $lvl;
}

function getExperience($user) {
    return $user->experience ? $user->experience : 0;
}

function getBalance($user, $onlyChat = false) {

    $allSum = \App\Payment::where('account_id', $user->id);

    if($onlyChat) {
        $allSum->whereIn('payment_type_id', [1,4]);
    }

    $allSum = $allSum->sum('price') * 10;
    return $allSum;
}

function getOutputSum($user) {
    return \App\Payment::where('account_id', $user->id)->where('payment_type_id', 2)->sum('price');
}

function getInputSum($user) {
    return \App\Payment::where('account_id', $user->id)->where('payment_type_id', 1)->sum('price');
}

function getReferralSum($user) {
    return \App\Payment::where('account_id', $user->id)->where('payment_type_id', 3)->sum('price');
}

function commission($game) {

    $commission = new \App\Commission;
    $commission->game_id = $game->game_id;
    $commission->price = ($game->participants()->sum('cash') / 10) * 10 / 100;

    $commission->save();

    return $commission->price;

}

function color($experience) {

    if($experience % 1000 == 0 && $experience != 80000) {
        return 0;
    }


    if($experience <= 1000) {
        $width = $experience * 100 / 1000;
    }
    elseif($experience > 1000 && $experience <= 10000) {
        $exp = floor($experience / 1000) * 1000;
        $experience = $experience - $exp;
        $width = $experience * 100 / 1000;
    }
    elseif($experience > 10000 && $experience < 80000) {
        $exp = floor($experience / 1000) * 1000;
        $experience = $experience - $exp;
        $width = $experience * 100 / 1000;
    }
    else {
        $width = 100;
    }

    return $width;

}
