# Поднимаем сервер на локалке или на сервере

### Клонирование репозитория
Есть два способа:
* Скачать репозиторий в новую папку в текущей директории <br>
`git clone https://github.com/DmitriiTz/fox_x_backend.git `

* Если папку для проекта существует в ней выполняем команду <br>
`git clone https://github.com/DmitriiTz/fox_x_backend.git .`

### Создание домена
Есть два способа:
* Установить Homestead с vagrant и использовать виртуальную машину для эмуляции ubuntu сервера на windows
* Установить OS Panel и добавить в папку domains этот проект и в настройках создать домен на путь app/public

Проще запустить через OS Panel

## Начальная настройка проекта
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
### Настройка Passport
* На всякий случай установить laravel/passport если не установился предыдущей командой <br>
`composer require laravel/passport "7.5.1"`
именно такой версии, так как изначально laravel в этом проекте старой версии 5.7

Все следующие команды кроме последней актуальны для нового проекта, в этом уже должно всё быть изменено, но если не работает - проверь.
* Проверяем что в папке /database/migration нет никаких миграций и мигрируем стандартные таблицы для laravel/passport <br>
`php artisan migrate`
* Для модели `User` нужно добавить трейт (внутри класса в поле use) - `use HasApiTokens`
* В `App/Providers/AuthServiceProvider` после `$this->registerPolicies();` добавить `Passport::routes();`
* В `config/auth.php` поменять драйвер для `guard -> api` на такое значение
```$xslt
    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
```

* Генерируем ключи для api (Обязательная команда)<br>
 `php artisan passport:install`
 
### Настройка Redis
Для websocket и для очередей нам понадобится redis.
* Устанавливаем пакет для связи laravel с redis<br>
`composer require predis/predis`
* Добавим/обновим переменные в файле .env.
```
    BROADCAST_DRIVER=redis
    
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
```
* Подключим сервис-провайдер BroadcastServiceProvider. Для этого найдем и раскомментируем в config/app.php строку <br>
`App\Providers\BroadcastServiceProvider::class,`

## Настройка NPM
* Возможно ошибка связанная с cross-env при установки зависимостей. Исправить это можно командой <br>
`sudo npm install --global cross-env`
* Ещё могут быть ошибки из за недостатка пакета <br>
`npm install  —save toastr`

### Laravel Echo Server
Для работы websocket в этом проекте используется обёртка laravel-echo-server.
* Сначала нужно его установить <br>
`npm i -g laravel-echo-server` Сам пока не знаю обязательно ли устанавливать глобально его. Но везде так говорят. 

* Если нет доступа установить глобально делаем так <br>
`npm i laravel-echo-server`

* И когда понадобится использовать laravel echo server вызываем его в директории проекта командой <br>
`npx laravel-echo server`

* Инициализируем Laravel Echo Server <br>
`laravel-echo-server init` Выполняем настройку конфига. <br>
>При выборе `https` нужно будет указать пути до сертификатов. <br>
Если не выбрать dev mode, то в статусе не будет писаться `Channels are ready.`<br>
Для разделённого фронта и бэка по `REST API`, нужно генерировать `ID/Key` и передавать их с запросом в заголовке 

* Пробуем запустить <br>
`laravel-echo-server start`

Работает если так:
```$xslt
L A R A V E L  E C H O  S E R V E R

version 1.6.3

Starting server...

✔  Running at localhost on port 6001
✔  Channels are ready.
✔  Listening for http events...
✔  Listening for redis events...

Server ready!
```
>Если не пишет `Listening for redis events...` и выдаёт ошибку начинающуюся с `[ioredis]`, значит `redis-server` не работает. Проверь работу редиса командой `redis-cli` и введи `ping` в консоль. Если ответ `PONG` значит всё работает. 

* Теперь запустим очереди
`php artisan queue:work` здесь будет показываться выполнение событий. В начале будет пустая строка, это нормально.


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

 - GET|POST /
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