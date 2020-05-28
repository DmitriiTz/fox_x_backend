# README

## Require

#### MainController

 - GET|POST /home
```
Info:
    Method - home
    View:
        jackpot

Response:
    'data' => [
            'game' => $game,
            'games' => $games,
            'gameTypeId' => $gameTypeId,
            'result' => $result,
            'timer' => $timer,
            'time' => $time,
            'gameType' => $gameType,
            'typeRequest' => $typeRequest,
            'pageName' => $pageName,
            'luckyOfTheDay' => $luckyOfTheDay,
            'biggestBank' => $biggestBank,
            'lastWinner' => $lastWinner,
    ]
    
if ($request->isMethod('post')) {
    Info:
        View - blocks.change-game-wrap-jackpot
    
    Response:
        'data' => [
            'game' => $game,
            'games' => $games,
            'gameTypeId' => $gameTypeId,
            'result' => $result,
            'timer' => $timer,
            'time' => $time,
            'gameType' => $gameType,
            'typeRequest' => $typeRequest,
            'luckyOfTheDay' => $luckyOfTheDay,
            'biggestBank' => $biggestBank,
        ]
}
```

 - GET /coinflip
```
Info:
    Method - coinflip
    View:
        coin-flip

Response:
    'data' => [
            'game' => $game,
            'games' => $games,
            'gameTypeId' => $gameTypeId,
            'result' => $result,
            'timer' => $timer,
            'time' => $time,
            'gameType' => $gameType,
            'typeRequest' => $typeRequest,
            'pageName' => $pageName,
            'luckyOfTheDay' => $luckyOfTheDay,
            'biggestBank' => $biggestBank,
            'lastWinner' => $lastWinner,
    ]
```

 - GET /king-of-the-hill
```
Info:
    Method - kingOfTheHill
    View:
        king-of-the-hill

Response:
    'data' => [
            'games' => $games,
            'gameClassic' => $gameClassic,
            'gameSenyor' => $gameSenyor,
            'timer' => $timer,
            'pageName' => $pageName,
            'lastGame' => $lastGame,
    ]
```

 - GET /crash
```
Info:
    Method - 
    View:

Response:
    'data' => [
    ]
```

#### ProfileController

 - GET account/profile
```
Info:
    Method - dailyBonus
    View - account.profile

Response:
    'data' => [
        'getHistoryBalance' => $getHistoryBalance,
        'listReferrals' => $listReferrals,
        'listGames' => $listGames,
        'pageName' => $pageName,
    ]
    
if($request->page){
    Info:
        View - blocks.wrap-ref-list
    
    Response:
        'data' => [
            'listReferrals' => $listReferrals,
        ]
}
```

 - POST account/update-profile
```
Info:
    Method - updateProfile
    View - NONE

Response:
    ['error' => 0]
    
if($validator->fails()){
    Info:
        View - account.blocks.registration
    
    Response:
        'data' => [
            'errors' => $validator->errors(),
            'request' => $request,
        ],
        'error' => 1
}
```

 - GET account/dailyBonus
```
Info:
    Method - dailyBonus
    View - account.daily-bonus

Response:
    'data' => [
        'arr' => $arr,
        'isDailyBonus' => $isDailyBonus,
        'pageName' => $pageName
    ]
```