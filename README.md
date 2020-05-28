# README

# Поднимаем сервер на локалке

Клонирование репозитория два способа:
* Скачать репозиторий в новую папку в текущей директории <br>
`git clone https://github.com/DmitriiTz/fox_x_backend.git `

* Если папку для проекта существует в ней выполняем команду <br>
`git clone https://github.com/DmitriiTz/fox_x_backend.git .`

Создание домена для проекта. Есть два способа:
* Установить Homestead с vagrant и использовать виртуальную машину для эмуляции ubuntu сервера на windows
* Установить OS Panel и добавить в папку domains этот проект и в настройках создать домен на путь app/public

Проще запустить через OS Panel

Установка зависимостей и прочего:
* Создаём базу в mysql и импортируем туда дамп базы из файла `fox.mysql.sql` он лежит в корне проекта
* Дальше нужно настроить `.env` файл на подлключение к базе данных mysql. У OS Panel будут такие параметры:
```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE= имя созданной базы
    DB_USERNAME=root
    DB_PASSWORD=
```
* В этом же файле нужно указать домен который был создан при настройке сервера, например:
`APP_URL=http://fox`

* Устанавливаем зависимости <br>
`composer i`
* На всякий случай установить laravel/passport если не установился предыдущей командой <br>
`composer require laravel/passport "7.5.1"`
* Генерируем ключи для api <br>
 `php artisan passport:install`
 
 Проект установлен и должен работать.
## Require

#### AuthController

 - POST /login
```
Info:
    Method - login

Response:
    'data' => [
        'token_type' => 'Bearer',
        'token' => $token->accessToken,
        'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
    ]
```

 - POST /register
```
Info:
    Method - register

Response:
    'data' => [
        'token' => $user->createToken(config('app.name')),
        'name' => $user->name,
    ]
```

 - POST /logout
```
Info:
    Method - logout

Response:
    'data' => [
        'message' => 'You are successfully logged out',   
    ]
```

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