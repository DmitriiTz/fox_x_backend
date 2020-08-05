<?php

use Illuminate\Support\Facades\Route;

/** Auth api register */
Route::group([
    'namespace' => 'ApiAuth'
], function () {
    Route::get('social/{driver}', 'AuthController@redirectToProvider');
    Route::get('social/{driver}/callback', 'AuthController@handleProviderCallback');
    Route::post('register', 'AuthController@register')->name('user.register');
    Route::post('login', 'AuthController@login')->name('user.login');
    Route::get('logout', 'AuthController@logout')->name('user.logout')->middleware('auth:api');
    Route::get('check-auth', 'AuthController@checkAuth')->name('user.check_user');
});

Route::get('get-message', 'MainController@getMessage');

/** Main api route */
Route::match(['get', 'post'], '/', ['as' => 'home', 'uses' => 'MainController@home']);

/** Account api routes */
Route::group(['as' => 'account.', 'prefix' => 'account', 'namespace' => 'Account', 'middleware' => 'auth:api'], function () {

    Route::post('/get-balance', ['uses' => 'ProfileController@getBalance']);

    Route::post('/set-participants-king', ['uses' => 'KingOfTheHillController@setParticipant']);
    Route::get('/create-senior', ['uses' => 'KingOfTheHillController@create_senior']);
    Route::get('/create-classic', ['uses' => 'KingOfTheHillController@create_classic']);

    Route::post('/success-payment0707', ['uses' => 'PaymentController@successPayment']);
    Route::post('/withdrawal-of-funds-account', ['uses' => 'PaymentController@withdrawalOfFundsAccount']);
    Route::post('/to-up-account', ['uses' => 'PaymentController@toUpAccount']);

    Route::post('/setting-music', ['uses' => 'ProfileController@settingMusic']);
    Route::post('/update-profile', ['uses' => 'ProfileController@updateProfile']);
    Route::get('/profile', ['as' => 'profile', 'uses' => 'ProfileController@profile']);
    Route::get('/daily-bonus', ['as' => 'daily-bonus', 'uses' => 'ProfileController@dailyBonus']);
    Route::post('/activate-promocode', ['uses' => 'ProfileController@activatePromocode']);
    Route::get('/daily-bonus-generate', ['uses' => 'ProfileController@dailyBonusGenerate']);

    Route::post('/create-game-coinflip', ['uses' => 'CoinflipController@createGame']);
    Route::post('/show-game-coinflip', ['uses' => 'CoinflipController@showGameCoinflip']);
    Route::post('/set-participant-coinflip', ['uses' => 'CoinflipController@setParticipantCoinflip']);
    Route::post('/get-result-coinflip', ['uses' => 'CoinflipController@getResultCoinflip']);
    Route::get('/bank-user', ['uses' => 'CoinflipController@userBank']);

    Route::post('/send-message', ['uses' => 'MessageController@sendMessage']);

    Route::post('/set-participant-game', ['uses' => 'JackpotController@setParticipant']);
    Route::get('/create-games/small', function () {
        $gameBefore = new \App\HistoryGame();
        $gameBefore->game_id = 3;
        $gameBefore->game_type_id = 2;
        $random = 0 + mt_rand() / mt_getrandmax() * (1 - 0);
        $gameBefore->winner_ticket_big = $random;
        $gameBefore->hash = hash('sha224', strval($gameBefore->winner_ticket_big));
        $gameBefore->link_hash = 'http://sha224.net/?val=' . $gameBefore->hash;
        $gameBefore->status_id = 1;
        $gameBefore->animation_at = now()->addYear();
        $gameBefore->save();
        return response()->json($gameBefore);
    });

    Route::get('/create-games/classic', function () {
        $gameBefore = new \App\HistoryGame();
        $gameBefore->game_id = 3;
        $gameBefore->game_type_id = 1;
        $random = 0 + mt_rand() / mt_getrandmax() * (1 - 0);
        $gameBefore->winner_ticket_big = $random;
        $gameBefore->hash = hash('sha224', strval($gameBefore->winner_ticket_big));
        $gameBefore->link_hash = 'http://sha224.net/?val=' . $gameBefore->hash;
        $gameBefore->status_id = 1;
        $gameBefore->animation_at = now()->addYear();
        $gameBefore->save();
        return response()->json($gameBefore);
    });
});


Route::group([
], function () {
});

Route::get('/coinflip', ['as' => 'coinflip', 'uses' => 'MainController@coinflip']);

//Маршруты игры Crash
Route::get('/crash', ['as' => 'crash', 'uses' => 'CrashController@index']);
Route::get('/crash/new', ['as' => 'crash-new', 'uses' => 'CrashController@newGame']);
Route::get('/crash/set-profit', ['as' => 'set-profit', 'uses' => 'CrashController@setProfit']);
Route::get('/crash/info', ['as' => 'crash-info', 'uses' => 'CrashController@info']);
Route::get('/crash/get-info', ['as' => 'get-info', 'uses' => 'CrashController@getInfo']);
Route::get('/crash/set-current-profit', ['as' => 'set-current-profit', 'uses' => 'CrashController@setCurrentProfit']);
Route::get('/crash/last-game', ['as' => 'crash-last', 'uses' => 'CrashController@getLastGame']);

Route::post('/crash/new-bet', function (Illuminate\Http\Request $request) {
    App\Events\JoinCrash::dispatch($request->input('body'), 1, 2);
});

Route::post('/crash/bet', ['as' => 'crash-bet', 'uses' => 'CrashController@newBet']);
Route::post('/crash/flash-cashout', 'CrashController@flashCashOut');
Route::post('/crash/cashout', 'CrashController@cashout');
Route::get('/crash/update-balance', ['as' => 'crash-last', 'uses' => 'CrashController@updateBalace']);

//Тестовые маршруты
Route::get('/crash/test', function () {
    App\Jobs\CreateCrash::dispatch("Test")->delay(now()->addMinutes(2))->onQueue('processing');
});

Route::get('/king-of-the-hill', ['as' => 'king-of-the-hill', 'uses' => 'MainController@kingOfTheHill']);
Route::get('/help', ['as' => 'help', 'uses' => 'PageController@help']);
Route::get('/change-theme', ['uses' => 'GlobalController@changeTheme']);
Route::post('/payment-callback0707', ['uses' => 'Account\PaymentController@paymentCallback']);

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['checkAdmin']], function () {
    Route::get('/', ['as' => 'admin', 'uses' => 'MainController@home']);
    Route::get('/users', ['as' => 'users', 'uses' => 'MainController@users']);
    Route::get('/wallet', ['as' => 'wallet', 'uses' => 'MainController@wallet']);
    Route::get('/output', ['as' => 'output', 'uses' => 'MainController@output']);
    Route::post('/change-status', ['uses' => 'MainController@changeStatus']);
    Route::match(['get', 'post'], '/promocodes', ['as' => 'promocodes', 'uses' => 'MainController@promocodes']);
    Route::get('/games', ['as' => 'games', 'uses' => 'MainController@games']);
    Route::get('/crash', ['as' => 'crash', 'uses' => 'MainController@crash']);
    Route::get('/crash/bets', ['as' => 'crash-bet', 'uses' => 'MainController@crash_bets']);
    Route::get('/crash/stop-game', ['as' => 'crash-bet', 'uses' => 'MainController@crash_stop']);
    Route::post('/crash/set-profit', 'MainController@setProfit');
    Route::post('/get-info-user', ['uses' => 'MainController@getInfoUser']);
    Route::post('/get-info-user-output', ['uses' => 'MainController@getInfoUserOutput']);
    Route::get('/users/next-page', ['uses' => 'MainController@usersNextPage']);
    Route::post('/save-info-user', ['uses' => 'MainController@saveInfoUser']);
    Route::post('/save-ajax-info', ['uses' => 'MainController@saveAjaxInfo']);
    Route::post('/search-users', ['uses' => 'MainController@searchUsers']);
    Route::post('/save-application-info', ['uses' => 'MainController@saveApplicationInfo']);
});


//Route::post('/check-auth-user', ['uses' => 'AuthController@checkAuthUser']);
Route::get('/referral-link/{userId}', ['as' => 'referral-link','uses' => 'AuthController@referralLink']);
//Route::post('/registration', ['uses' => 'AuthController@registration']);
//Route::post('auth-user', ['uses' => 'AuthController@authUser']);
//Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
//Route::match(['get', 'post'], '/oauth/{driver}', 'AuthController@redirectToProvider');
//Route::match(['get', 'post'], '/oauth/{driver}/callback', 'AuthController@handleProviderCallback');




