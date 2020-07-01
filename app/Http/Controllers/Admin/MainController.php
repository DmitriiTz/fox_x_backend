<?php

namespace App\Http\Controllers\Admin;

use App\Commission;
use App\HistoryGame;
use App\Payment;
use App\Promocode;
use App\User;
use App\WithdrawMoneyAccountApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

use App\Events\StopCrash;

use App\CrashGame;
use DB;
use Illuminate\Support\Facades\Artisan;

class MainController extends Controller
{

    public $paginate = 12;

    // Метод отвечающий за вывод страницы со статистикой
    public function home() {

        $page = 'Статистика';

        $paymentsInputToday = Payment::where('created_at', '>', Carbon::today())
            ->where('created_at', '<', Carbon::now())
            ->where('payment_type_id', 1)
            ->where('is_admin', 0)
            ->sum('price');

        $paymentsInputYesterday = Payment::where('created_at', '>', Carbon::yesterday())
            ->where('created_at', '<', Carbon::today())
            ->where('payment_type_id', 1)
            ->where('is_admin', 0)->sum('price');

        $paymentsInput7days = Payment::where('created_at', '>', Carbon::today()->subDays(7))
            ->where('created_at', '<', Carbon::now())
            ->where('payment_type_id', 1)->where('is_admin', 0)
            ->sum('price');

        $paymentsInputMonth = Payment::where('created_at', '>', Carbon::today()->subMonth())
            ->where('created_at', '<', Carbon::now())
            ->where('payment_type_id', 1)->where('is_admin', 0)
            ->sum('price');
        $paymentsInput3Months = Payment::where('created_at', '>', Carbon::today()->subMonths(3))->where('created_at', '<', Carbon::now())->where('payment_type_id', 1)->where('is_admin', 0)->sum('price');

        $paymentsOutputToday = Payment::where('created_at', '>', Carbon::today())->where('created_at', '<', Carbon::now())->where('payment_type_id', 2)->where('is_admin', 0)->sum('price');
        $paymentsOutputYesterday = Payment::where('created_at', '>', Carbon::yesterday())->where('created_at', '<', Carbon::today())->where('payment_type_id', 2)->where('is_admin', 0)->sum('price');
        $paymentsOutput7days = Payment::where('created_at', '>', Carbon::today()->subDays(7))->where('created_at', '<', Carbon::now())->where('payment_type_id', 2)->where('is_admin', 0)->sum('price');
        $paymentsOutputMonth = Payment::where('created_at', '>', Carbon::today()->subMonth())->where('created_at', '<', Carbon::now())->where('payment_type_id', 2)->where('is_admin', 0)->sum('price');
        $paymentsOutput3Months = Payment::where('created_at', '>', Carbon::today()->subMonths(3))->where('created_at', '<', Carbon::now())->where('payment_type_id', 2)->where('is_admin', 0)->sum('price');


        $incomeToday = Commission::where('created_at', '>', Carbon::today())->where('created_at', '<', Carbon::now())->sum('price');
        $incomeYesterday = Commission::where('created_at', '>', Carbon::yesterday())->where('created_at', '<', Carbon::today())->sum('price');
        $income7days = Commission::where('created_at', '>', Carbon::today()->subDays(7))->where('created_at', '<', Carbon::now())->sum('price');
        $incomeMonth = Commission::where('created_at', '>', Carbon::today()->subMonth())->where('created_at', '<', Carbon::now())->sum('price');
        $income3Months = Commission::where('created_at', '>', Carbon::today()->subMonths(3))->where('created_at', '<', Carbon::now())->sum('price');

        $newUsersToday = User::where('created_at', '>', Carbon::today())->where('created_at', '<', Carbon::now())->count();
        $newUsersYesterday = User::where('created_at', '>', Carbon::yesterday())->where('created_at', '<', Carbon::today())->count();
        $newUsers7days = User::where('created_at', '>', Carbon::today()->subDays(7))->where('created_at', '<', Carbon::now())->count();
        $newUsersMonth = User::where('created_at', '>', Carbon::today()->subMonth())->where('created_at', '<', Carbon::now())->count();
        $newUsers3Months = User::where('created_at', '>', Carbon::today()->subMonths(3))->where('created_at', '<', Carbon::now())->count();



        $paymentsReferralToday = Payment::where('created_at', '>', Carbon::today())->where('created_at', '<', Carbon::now())->where('payment_type_id', 3)->sum('price');
        $paymentsReferralYesterday = Payment::where('created_at', '>', Carbon::yesterday())->where('created_at', '<', Carbon::today())->where('payment_type_id', 3)->sum('price');
        $paymentsReferral7days = Payment::where('created_at', '>', Carbon::today()->subDays(7))->where('created_at', '<', Carbon::now())->where('payment_type_id', 3)->sum('price');
        $paymentsReferralMonth = Payment::where('created_at', '>', Carbon::today()->subMonth())->where('created_at', '<', Carbon::now())->where('payment_type_id', 3)->sum('price');
        $paymentsReferral3Months = Payment::where('created_at', '>', Carbon::today()->subMonths(3))->where('created_at', '<', Carbon::now())->where('payment_type_id', 3)->sum('price');


        $jackpotStatisticsToday = Commission::where('created_at', '>', Carbon::today())->where('created_at', '<', Carbon::now())->where('game_id', 3)->sum('price');
        $jackpotStatisticsYesterday = Commission::where('created_at', '>', Carbon::yesterday())->where('created_at', '<', Carbon::today())->where('game_id', 3)->sum('price');
        $jackpotStatistics7days = Commission::where('created_at', '>', Carbon::today()->subDays(7))->where('created_at', '<', Carbon::now())->where('game_id', 3)->sum('price');
        $jackpotStatisticsMonth = Commission::where('created_at', '>', Carbon::today()->subMonth())->where('created_at', '<', Carbon::now())->where('game_id', 3)->sum('price');
        $jackpotStatistics3Months = Commission::where('created_at', '>', Carbon::today()->subMonths(3))->where('created_at', '<', Carbon::now())->where('game_id', 3)->sum('price');

        $coinflipStatisticsToday = Commission::where('created_at', '>', Carbon::today())->where('created_at', '<', Carbon::now())->where('game_id', 4)->sum('price');
        $coinflipStatisticsYesterday = Commission::where('created_at', '>', Carbon::yesterday())->where('created_at', '<', Carbon::today())->where('game_id', 4)->sum('price');
        $coinflipStatistics7days = Commission::where('created_at', '>', Carbon::today()->subDays(7))->where('created_at', '<', Carbon::now())->where('game_id', 4)->sum('price');
        $coinflipStatisticsMonth = Commission::where('created_at', '>', Carbon::today()->subMonth())->where('created_at', '<', Carbon::now())->where('game_id', 4)->sum('price');
        $coinflipStatistics3Months = Commission::where('created_at', '>', Carbon::today()->subMonths(3))->where('created_at', '<', Carbon::now())->where('game_id', 4)->sum('price');

        $kingStatisticsToday = Commission::where('created_at', '>', Carbon::today())->where('created_at', '<', Carbon::now())->where('game_id', 2)->sum('price');
        $kingStatisticsYesterday = Commission::where('created_at', '>', Carbon::yesterday())->where('created_at', '<', Carbon::today())->where('game_id', 2)->sum('price');
        $kingStatistics7days = Commission::where('created_at', '>', Carbon::today()->subDays(7))->where('created_at', '<', Carbon::now())->where('game_id', 2)->sum('price');
        $kingStatisticsMonth = Commission::where('created_at', '>', Carbon::today()->subMonth())->where('created_at', '<', Carbon::now())->where('game_id', 2)->sum('price');
        $kingStatistics3Months = Commission::where('created_at', '>', Carbon::today()->subMonths(3))->where('created_at', '<', Carbon::now())->where('game_id', 2)->sum('price');




        $data = [
            'page' => $page,
            'paymentsInput3Months' => $paymentsInput3Months,
            'paymentsInput7days' => $paymentsInput7days,
            'paymentsInputMonth' => $paymentsInputMonth,
            'paymentsInputToday' => $paymentsInputToday,
            'paymentsInputYesterday' => $paymentsInputYesterday,
            'paymentsOutput3Months' => $paymentsOutput3Months,
            'paymentsOutput7days' => $paymentsOutput7days,
            'paymentsOutputMonth' => $paymentsOutputMonth,
            'paymentsOutputToday' => $paymentsOutputToday,
            'paymentsOutputYesterday' => $paymentsOutputYesterday,
            'income3Months' => $income3Months,
            'income7days' => $income7days,
            'incomeMonth' => $incomeMonth,
            'incomeYesterday' => $incomeYesterday,
            'incomeToday' => $incomeToday,
            'newUsers3Months' => $newUsers3Months,
            'newUsers7days' => $newUsers7days,
            'newUsersMonth' => $newUsersMonth,
            'newUsersYesterday' => $newUsersYesterday,
            'newUsersToday' => $newUsersToday,
            'paymentsReferralYesterday' => $paymentsReferralYesterday,
            'paymentsReferralToday' => $paymentsReferralToday,
            'paymentsReferral7days' => $paymentsReferral7days,
            'paymentsReferralMonth' => $paymentsReferralMonth,
            'paymentsReferral3Months' => $paymentsReferral3Months,
            'jackpotStatisticsToday' => $jackpotStatisticsToday,
            'jackpotStatisticsYesterday' => $jackpotStatisticsYesterday,
            'jackpotStatistics7days' => $jackpotStatistics7days,
            'jackpotStatisticsMonth' => $jackpotStatisticsMonth,
            'jackpotStatistics3Months' => $jackpotStatistics3Months,
            'coinflipStatisticsToday' => $coinflipStatisticsToday,
            'coinflipStatisticsYesterday' => $coinflipStatisticsYesterday,
            'coinflipStatistics7days' => $coinflipStatistics7days,
            'coinflipStatisticsMonth' => $coinflipStatisticsMonth,
            'coinflipStatistics3Months' => $coinflipStatistics3Months,
            'kingStatisticsToday' => $kingStatisticsToday,
            'kingStatisticsYesterday' => $kingStatisticsYesterday,
            'kingStatistics7days' => $kingStatistics7days,
            'kingStatisticsMonth' => $kingStatisticsMonth,
            'kingStatistics3Months' => $kingStatistics3Months];

        return response()->json($data);

//        return view('admin.home', compact('page', 'paymentsInput3Months', 'paymentsInput7days', 'paymentsInputMonth', 'paymentsInputToday', 'paymentsInputYesterday',
//            'paymentsOutput3Months', 'paymentsOutput7days', 'paymentsOutputMonth', 'paymentsOutputToday', 'paymentsOutputYesterday', 'income3Months', 'income7days',
//            'incomeMonth', 'incomeYesterday', 'incomeToday', 'newUsers3Months', 'newUsers7days', 'newUsersMonth','newUsersYesterday', 'newUsersToday',
//            'paymentsReferralYesterday', 'paymentsReferralToday', 'paymentsReferral7days', 'paymentsReferralMonth', 'paymentsReferral3Months', 'jackpotStatisticsToday',
//            'jackpotStatisticsYesterday', 'jackpotStatistics7days', 'jackpotStatisticsMonth', 'jackpotStatistics3Months', 'coinflipStatisticsToday',
//            'coinflipStatisticsYesterday', 'coinflipStatistics7days', 'coinflipStatisticsMonth', 'coinflipStatistics3Months', 'kingStatisticsToday',
//            'kingStatisticsYesterday', 'kingStatistics7days', 'kingStatisticsMonth', 'kingStatistics3Months'));
    }

    // Выводит список пользователей
    public function users(Request $request) {

        $page = 'Пользователи';
        $paginate = $this->paginate;
        $users = User::with('payments')->where('id', '!=', 4); // список пользователей без профиля гостя

        $search = '';
        if($request->search) { // сортировка по входным данным
            $search = $request->search; // поиск по имени, фамилии или идентификатору социальной сети
            $users->where('name', 'LIKE', '%'. $request->search .'%')->orWhere('last_name', 'LIKE', '%'. $request->search .'%')
                ->orWhere('vkontakte_id', 'LIKE', '%'. $request->search .'%')->orWhere('facebook_id', 'LIKE', '%'. $request->search .'%');
        }

        $sort = json_decode($request->sort);
        if($request->sort) { // сортировка по полям
            $json = json_decode($request->sort);
            $json = array_reverse($json);
            foreach ($json as $j) {
                $users->orderBy($j->column, $j->order);
            }
        }

        $arraySort = [
            'sort' => $request->sort,
            'search' => $search
        ];

        $users = $users->paginate($paginate); // пагинация

        $data = [
            'users' => $users,
            'paginate' => $paginate,
            'search' => $search,
            'page' => $page,
            'sort' => $sort,
            'arraySort' => $arraySort,
        ];
        return response()->json($data);

        //return view('admin.users', compact('users', 'paginate', 'search', 'page', 'sort', 'arraySort'));
    }

    // Страница отвечающая за вывод списка пополнений пользователей
    public function wallet(Request $request) {

        $page = 'Пополнение';
        $paginate = 20;
        $payments = Payment::where('payment_type_id', 1)->with(['account', 'payment_system']);

        if($request->calendar_range) {
            if(strpos($request->calendar_range, '—') !== false) {
                $explode = explode('—', $request->calendar_range);
                $dateFrom = Carbon::parse(trim(implode('-', array_reverse(explode('.', $explode[0])))));
                $dateTo = Carbon::parse(trim(implode('-', array_reverse(explode('.', $explode[1])))));
                $payments = $payments->where('created_at', '>', $dateFrom)->where('created_at', '<', $dateTo);
            }
            else {
                $date = implode('-', array_reverse(explode('.', $request->calendar_range)));
                $dateFrom = Carbon::parse($date);
                $dateTo = Carbon::parse($date)->addDay();
                $payments = $payments->where('created_at', '>', $dateFrom)->where('created_at', '<', $dateTo);
            }
        }


        if($request->search) {
            $search = $request->search;
            $payments->whereHas('account', function ($query) use($search) {
                $query->where(function ($query) use($search) {
                    $query->where('name', 'LIKE', '%'. $search .'%')->orWhere('last_name', 'LIKE', '%'. $search .'%');
                });
            });
        }

        $sort = json_decode($request->sort);
        if($request->sort) {
            $json = json_decode($request->sort);
            foreach ($json as $j) {
                $payments = $payments->orderBy($j->column, $j->order);
            }
        }

        $payments = $payments->paginate($paginate);

        $arraySort = [
            'sort' => $request->sort
        ];

        $data = [
            'payments' => $payments,
            'paginate' => $paginate,
            'page' => $page,
            'sort' => $sort,
            'arraySort' => $arraySort,
        ];

        return response()->json($data);

        //return view('admin.wallet', compact('payments', 'paginate', 'page', 'sort', 'arraySort'));
    }

    // Метод отвечающий за страницу заявок на вывод денег из сервиса
    public function output(Request $request) {
        $page = 'Вывод';

        $paginate = 20;
        $search = false;
        $applications = new WithdrawMoneyAccountApplication;

        if($request->calendar_range) { // сортировка по дате
            if(strpos($request->calendar_range, '—') !== false) {
                $explode = explode('—', $request->calendar_range);
                $dateFrom = Carbon::parse(trim(implode('-', array_reverse(explode('.', $explode[0])))));
                $dateTo = Carbon::parse(trim(implode('-', array_reverse(explode('.', $explode[1])))));
                $applications = $applications->where('created_at', '>', $dateFrom)->where('created_at', '<', $dateTo);
            }
            else {
                $date = implode('-', array_reverse(explode('.', $request->calendar_range)));
                $dateFrom = Carbon::parse($date);
                $dateTo = Carbon::parse($date)->addDay();
                $applications = $applications->where('created_at', '>', $dateFrom)->where('created_at', '<', $dateTo);
            }
        }


        if($request->search) { // сортировка по вхождению
            $search = $request->search;
            $applications->whereHas('account', function ($query) use($search) {
                $query->where(function ($query) use($search) {
                    $query->where('name', 'LIKE', '%'. $search .'%')->orWhere('last_name', 'LIKE', '%'. $search .'%');
                });
            });
        }


        $sort = json_decode($request->sort); // сортировка по полям
        if($request->sort) {
            $json = json_decode($request->sort);
            foreach ($json as $j) {
                $applications = $applications->orderBy($j->column, $j->order);
            }
        }

        $applications = $applications->paginate($paginate);

        $data = [
            'applications' => $applications,
            'paginate' => $paginate,
            'page' => $page,
            'sort' => $sort,
            'search' => $search,
        ];

        return response()->json($data);

        //return view('admin.output', compact('page', 'paginate', 'applications', 'search', 'sort'));
    }

    // Страница отвечающая за вывод списка промокодов
    public function promocodes(Request $request) {

        $page = 'Промокоды';
        if($request->isMethod('post')) { // создание промокодов
            $data = $request->all();
            if(isset($data['promocode-value']) && isset($data['promocode-sum'])) {

                for($i=0; $i < $data['promocode-value']; $i++) {
                    $promocode = str_random(10); //имя промокода
                    $isPromocode = Promocode::where('code', $promocode)->first(); // проверка на существование такого промокода в базе
                    if($isPromocode) {
                        $data['promocode-value'] = $data['promocode-value'] + 1;
                    }
                    else {
                        $newPromocode = new Promocode; // создание промокода
                        $newPromocode->code = $promocode;
                        $newPromocode->price = $data['promocode-sum'];
                        $newPromocode->save();
                    }

                }
            }
        }

        $paginate = 20;
        $promocodes = new Promocode();

        if($request->search) {
            $promocodes->where('code', 'LIKE', '%'. $request->search .'%'); // поиск промокодов
        }

        $sort = json_decode($request->sort);
        if($request->sort) { // сортировка по полям
            $json = json_decode($request->sort);
            foreach ($json as $j) {
                $promocodes = $promocodes->orderBy($j->column, $j->order);
            }
        }

        $arraySort = [
            'sort' => $request->sort
        ];

        $promocodes = $promocodes->where('accrual', 0)->paginate($paginate);

        $data = [
            'promocodes' => $promocodes,
            'paginate' => $paginate,
            'page' => $page,
            'sort' => $sort,
            'arraySort' => $arraySort,
        ];

        return response()->json($data);

        //return view('admin.promo', compact('promocodes', 'paginate', 'page', 'sort', 'arraySort'));
    }

    // Страница отвечающая за вывод игр и информации по играм
    public function games() {
        $page = 'Игры';

        $jackpotGames = HistoryGame::with(['participants' => function($query) {
            $query->with('account');
        }, 'type', 'winner'])
            ->orderBy('id', 'asc')
            ->where('game_id', 3)
            ->where(function($query) {
                $query->whereNull('end_game_at')->orWhere('end_game_at', '>', now());
            })
            ->limit(100)
            ->get(); // вывод игр по игре jackpot

        $coinflipGames = HistoryGame::with(['participants' => function($query) {
            $query->with('account');
        }, 'winner'])
            ->orderBy('id', 'asc') // сортировка по id
            ->where('game_id', 4) // тип игры
            ->where('end_game_at','=', NULL)
          //  ->where('status_id','!=',1)
            ->whereNotNull('winner_ticket_big')
            ->has('participants', '<', 2)
            ->limit(100) // количество
            ->get(); // вывод игр по игре coinflip

        $data = [
            'jackpotGames' => $jackpotGames,
            'coinflipGames' => $coinflipGames,
            'page' => $page,
        ];

        return response()->json($data);

        //return view('admin.games', compact('page', 'jackpotGames', 'coinflipGames'));
    }

    // Страница отвечающая за вывод игр и информации по играм Crash
    public function crash() {
        $page = 'Crash';
        $game = CrashGame::orderBy('id', 'desc')->first();

        if($game->stop_game < time() OR $game->status == 1){
            $info = 'Онлайн в Crash: 0';
            $bets = null;
            $mode = 0;
        }else{
            $info = $game;
            $bets = DB::table('crashbets')->where('crash_game_id', $game->id)->orderBy('price', 'desc')->get();
            $mode = 1;

            $x = time() - $game->create_game;

            $i = 0;
            $index = 1.06;
            $profit = $x;


        }

        $ubets = array();
        foreach ($game->bets as $key) {
            $ubets[] = [
                'bet' => $key,
                'user' => User::Where('id', $key->user_id)->first()
            ];
        }

        $data = [
            'game' => $game,
            'info' => $info,
            'bets' => $bets,
            'ubets' => $ubets,
            'mode' => $mode,
            'page' => $page,
            'profit' => $game->profit,
        ];

        return response()->json($data);

        //return view('admin.crash', compact('page', 'info', 'bets', 'mode', 'profit'));
    }

    // Метод возвращающий информацию по текущим ставкам Crash
    public function crash_bets(){
        $game = CrashGame::orderBy('id', 'desc')->first();
        $bets = DB::table('crashbets')->where('crash_game_id', $game->id)->orderBy('price', 'desc')->get();

        if(count($bets) == 0){
            $content = 'Ставок нет';
        }else{
            $content = '';
        }
        $data = [];
        foreach ($bets as $bet) {
            if($game->profit > $bet->number){
                $z = '+';
            }else{
                $z = '-';
            }
            $bts = $bet->number * $bet->price - $bet->price;
            $content .= '<tr>';
            $content .=    '<th scope="row">'.$bet->number.'X</th>';
            $content .=    '<td>'.$bet->price.'</td>';
            $content .=    '<td>'.$z.$bts.'</td>';
            $content .= '</tr>';

            $data[] = [
                'bts' => $bts,
                'bet_number' => $bet->number,
                'bet_price' => $bet->price,
                'z_bts' => $z.$bts,
            ];
        }

        return response()->json($data);

        //return $content;
    }

    // Метод останавливает график Crash
    public function crash_stop(Request $request){

        Artisan::call('queue:clear', ['connection' => 'redis']);

        $game = CrashGame::orderBy('id', 'desc')->first();
        $stop = time();
        DB::table('crashgames')->where('id', $game->id)->update([
            'stop_game' => $stop,
            'status' => 3,
            'profit' => $request->get('r')
        ]);

        StopCrash::dispatch('true');
    }

    // Метод возвращающий информацию по определенному пользователю
    public function getInfoUser(Request $request) {

        $user = User::with(['payments',])->findOrFail($request->userId);
        $total_price_output = $user->payments->where('payment_type_id', 2)->sum('price');
        $total_price_input = $user->payments->where('payment_type_id', 1)->sum('price');
        $total_price_referal = $user->payments->where('payment_type_id', 3)->sum('price');

        $data = [
            'total_price_output' => $total_price_output,
            'total_price_input' => $total_price_input,
            'total_price_referal' => $total_price_referal,
            'user' => $user->payments()->where('payment_type_id', 1)->orWhere('payment_type_id', 2)->get(),
        ];

        return response()->json($data);

        //$view = view('admin.blocks.popup-get-info-user', compact('user'))->render();

        //return $view;
    }

    // Метод возвращает информацию по заявке на вывод
    public function getInfoUserOutput(Request $request) {
        $user = User::with(['payments',])->findOrFail($request->userId);

        $total_price_output = $user->payments->where('payment_type_id', 2)->sum('price');
        $total_price_input = $user->payments->where('payment_type_id', 1)->sum('price');
        $total_price_referal = $user->payments->where('payment_type_id', 3)->sum('price');

        $application = WithdrawMoneyAccountApplication::findOrFail($request->applicationId);

        $data = [
            'total_price_output' => $total_price_output,
            'total_price_input' => $total_price_input,
            'total_price_referal' => $total_price_referal,
            'user' => $user->payments()->where('payment_type_id', 1)->orWhere('payment_type_id', 2)->get(),
            'application' => $application,
        ];

        return response()->json($data);

        //$view = view('admin.blocks.output-info-user', compact('user', 'application'))->render();


        //return $view;
    }

    // Метод выводит пагинацию по оплатам для определенного пользователя
    public function usersNextPage(Request $request) {

        $user = User::findOrFail($request->userId);
        $payments = Payment::where('account_id', $request->userId)->where('is_admin', 0)->whereIn('payment_type_id', [1,2,3])->paginate(10);

        $data = [
            'payments' => $payments,
            'user' => $user,
        ];

        return response()->json($data);

        //$view = view('admin.blocks.paginate-user', compact('payments', 'user'))->render();

        //return $view;

    }

    // Метод сохраняет информацию по пользователю из попапа
    public function saveInfoUser(Request $request) {
        $isAppBal= false;
        $data = $request->all();

        $user = User::findOrFail($data['id']);
        $user->name = $data['name'];
        $user->last_name = $data['last_name'];
        $user->role_id = $data['role_id'];
        $user->is_referral_power = $data['is_referral_power'];
        $user->is_blocked = $data['is_blocked'];

        if($request->balance > 0) 
		{

            $currentBalance = getBalance($user) / 10; // Получаем баланс игрока до сохранения
            $difference = $currentBalance; // приравниваем difference к этому числу
            if($request->balance > $currentBalance) { // Если запрашиваемый баланс больше настоящего
                $isAppBal= true; // Ставим метку true
                $difference = $request->balance - $currentBalance; // Приравниваем дифференс к запрашиваемому балансу минус настоящему
            }

            if($request->balance < $currentBalance) { // Если баланс меньше настоящего балансва
                $isAppBal= false; 
                $difference = -($currentBalance - $request->balance);
            }

			if($request->balance != $currentBalance)
			{
				$payment = new Payment;
				$payment->account_id = $user->id;
				$payment->price = $difference;
				$payment->payment_system_id = 1;
				$payment->payment_type_id = 1;
				$payment->is_admin = 1;
				$payment->save();
			}

            if($user->referral_account_id) {
                $referralAccount = User::find($user->referral_account_id);

                if($referralAccount->is_referral_power) {
                    $lvl = getLevel($referralAccount);
                    $percent = $lvl * 0.1;
                    $referralSum = ($payment->price * $percent) / 100;

                    $payment = new Payment;
                    $payment->account_id = $referralAccount->id;
                    $payment->referral_account_id = $user->id;
                    $payment->price = $referralSum;
                    $payment->payment_type_id = 3;
                    $payment->save();

                }

            }

			if($request->balance != $currentBalance)
			{
				$experience = $payment->price * 10 / 2;
			}
			else
			{
				$experience = 0;
			}

            if($user->experience < 80000) {
                $experience = $user->experience + $experience;
            }
            else {
                $experience = 80000;
            }
            if($isAppBal)
            {
            $user->experience = $experience;

            if($user->experience >= 80000) {
                $user->experience = 80000;
            }
        }

        }

        $user->save();

    }

    // Ajax сохранение выпадающих списков
    public function saveAjaxInfo(Request $request) {

        $user = User::findOrFail($request->id);
        $user->role_id = $request->role;
        if($request->ref == 1) {
            $user->is_referral_power = $request->ref; // поле отвечающее за реферальное награждение
        }
        else {
            $user->is_referral_power = 0;
        }

        if($request->blocked == 1) {
            $user->is_blocked = $request->blocked;
        }
        else {
            $user->is_blocked = 0;
        }


        $user->save();
    }

    // метод сохраняет информацию о заявке и создает платеж
    public function saveApplicationInfo(Request $request) {
        $application = WithdrawMoneyAccountApplication::findOrFail($request->applicationId);

        if($application->status_id == 2) {
            $application->status_id = $request->status_id;
            $application->save();

            if($request->status_id == 5) {
                $payment = new Payment;
                $payment->account_id = $application->account_id;
                $payment->price = -$application->price;
                $payment->payment_type_id = 2;
                $payment->payment_system_id = $application->payment_system_id;
                $payment->save();
                return response()->json();
            }

        }
        if($application->status_id === 1 && $request->status_id === 3) {
            $application->status_id = $request->status_id;
            $application->save();
            Payment::destroy($application->payment_id);
            return response()->json();
        }
        $application->status_id = $request->status_id;
        $application->save();
        return response()->json();
    }


    // Меняет статус для группы заявок
    public function changeStatus(Request $request) {

        foreach ($request->arr as $item) {

            $application = WithdrawMoneyAccountApplication::where('id', $item)->where('status_id', '!=', 4)->first();

            if(!$application) {
                continue;
            }

            $application->status_id = $request->statusId;
            $application->save();

            if($request->statusId == 5) {
                $this->outputPayment($application->id); // вызывает метод оплаты
            }
            elseif($request->statusId == 6)
            {
                /*
                $payment = new Payment;
                $payment->account_id = $application->account_id;
                $payment->price = -$application->price;
                $payment->payment_type_id = 2;
                $payment->payment_system_id = $application->payment_system_id;
                $payment->save();
                */
            }
            elseif($request->statusId == 3)
            {
                $payment = Payment::where('id', $application->payment_id)->delete();
            }



        }

    }

    // Метод отвечающий за вывод денег из сервиса
    public function outputPayment($applicationId) {

        $application = WithdrawMoneyAccountApplication::where('id', $applicationId)->where('status_id', '!=', 4)->first(); // находит заявку

        if($application->payment_system_id == 1) { // проверяет тип платежки и дает определенный код
            $codeCurrency = 63;
        }

        if($application->payment_system_id == 2) {
            $codeCurrency = 1;
        }

        if($application->payment_system_id == 3) {
            $codeCurrency = 45;
        }

        if($application->payment_system_id == 4) {
            $codeCurrency = 94;
        }


        $data = array( // реализация вывода денег через freekassa
            'wallet_id'=>'F105511048',
            'purse'=> $application->phone,
            'amount'=> $application->price,
            'desc'=>'Cashout',
            'currency'=>$codeCurrency,
            'sign'=>md5('F105511048'.$codeCurrency.$application->price.$application->phone.'03D62C57AC7642900F3D3DCA5BC1C099'),
            'action'=>'cashout',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.fkwallet.ru/api_v1.php');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = json_decode(trim(curl_exec($ch)),true);
        $c_errors = curl_error($ch);
        curl_close($ch);
        if($result['status'] != 'error') { // проверка статус оплаты
          
          /*
            $payment = new Payment;
            $payment->account_id = $application->account_id;
            $payment->price = -$application->price;
            $payment->payment_type_id = 2;
            $payment->save();
    */
            $application->status_id = 7;
            $application->save();
        }
        else
        {
            $application->status_id = 9;
            $application->comment = json_encode($result);
            $application->save();
        }

    }

}
