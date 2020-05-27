<?php

namespace App\Http\Controllers\Account;

use App\HistoryGame;
use App\Payment;
use App\Promocode;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class ProfileController extends Controller
{
    public function profile(Request $request) {
        $pageName = 'Профиль';
        $user = auth()->user();
        $getHistoryBalance = Payment::where('account_id', $user->id)
            ->with('payment_system')
            ->where('is_admin', 0)
            ->orderBy('created_at', 'desc')
            ->get();


//        $listReferrals = Payment::with('referral')->where('account_id', $user->id)->whereNotNull('referral_account_id')->paginate(10);
        $listReferrals = User::where('referral_account_id', $user->id)->paginate(10);

        if($request->page) {
            $view = view('blocks.wrap-ref-list', compact('listReferrals'))->render();
            return $view;
        }

        $listGames = Payment::where('account_id', $user->id)
            ->with(['game' => function($query) use($user){
                $query->with(['participants' => function($query) use($user) {
                    $query->where('account_id', $user->id);
                }, 'nameGame']);
            }])
            ->where('is_admin', 0)
            ->where('payment_type_id', 6)
            ->orWhere('payment_type_id', 3)
            ->whereNotNull('history_game_id')
            ->whereHas('game', function($query) {
                $query->whereNotNull('winner_account_id');
            })
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();


        $isDailyBonus = Payment::where('payment_type_id', 5)->orderby('created_at', 'desc')->first();

        return view('account.profile', compact('getHistoryBalance', 'listReferrals', 'listGames', 'pageName'));
    }



    public $rulesRegistration = [
        'email'         =>      'required||email'
    ];


    public function updateProfile(Request $request)
    {
             $data = $request->all();
        $validator = Validator::make($request->all(), $this->rulesRegistration);

        if($validator->fails()) {
            $view = view('account.blocks.registration', ['errors' => $validator->errors(), 'request' => $request])->render();
            return ['error' => 1, 'view' => $view];
        }

        $user = auth()->user();
      //  $user->name = $data['name'];
       // $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        if($data['password'] != '')
        {
        $user->password = bcrypt($data['password']);
         }
        $user->link_vk = $data['vk'];
        $user->link_telegram = $data['telegram'];
        $user->link_facebook = $data['facebook'];
        $user->save();


        return ['error' => 0];
    }

    public function dailyBonus() {

        $pageName = 'Бонус';
        $arr = [100, 50, 0, 20, 150, 200, 500, 1000, 5000, 10000, 100, 50, 0, 20, 150, 200, 500, 1000, 5000, 10000];
        shuffle($arr);

        $isDailyBonus = Payment::where('payment_type_id', 5)
            ->orderby('created_at', 'desc')
            ->where('account_id', auth()->user()->id)
            ->where('created_at', '>', now()->subDay())
            ->first();


        return view('account.daily-bonus', compact('arr', 'isDailyBonus', 'pageName'));

    }

    public function activatePromocode(Request $request) {
        $user = auth()->user();
        $allSumma = Payment::where('account_id', auth()->user()->id)->sum('price') * 10;

		if($user->bad_time_promocode > now()) {
            return ['error' => 1, 'sum' => $allSumma, 'text' => 'Активировать промокод можно раз в 24 часа!'];
        }

        $isPromocode = Promocode::where('code', $request->code)->first();
		if($isPromocode == false)
		{
			return ['error' => 1, 'sum' => $allSumma, 'text' => 'Промокод не найден!'];
		}
		else
		{
			#whereNull('account_id')->where('accrual', 0)
			if($isPromocode->accrual != 0)
			{
				return ['error' => 1, 'sum' => $allSumma, 'text' => 'Данный промокод активировал кто-то другой'];
			}
			
			$isPromocode->account_id = $user->id;
            $isPromocode->accrual = 1;
            $isPromocode->save();

            $payment = new Payment;
            $payment->account_id = $user->id;
            $payment->price = $isPromocode->price;
            $payment->payment_type_id = 4;
            $payment->promocode_id = $isPromocode->id;
            $payment->save();
            $error = 0;

			$user->bad_time_promocode = now()->addDay(1);
            #$user->bad_count_promocode = 0;
            $user->save();
		}

       /* $error = 1;
        if($isPromocode) {
            $isPromocode->account_id = $user->id;
            $isPromocode->accrual = 1;
            $isPromocode->save();

            $payment = new Payment;
            $payment->account_id = $user->id;
            $payment->price = $isPromocode->price;
            $payment->payment_type_id = 4;
            $payment->promocode_id = $isPromocode->id;
            $payment->save();
            $error = 0;

			$user->bad_time_promocode = now()->addDay(1);
            #$user->bad_count_promocode = 0;
            $user->save();

        }*/

       /* if($error) {
            $user->bad_count_promocode = $user->bad_count_promocode + 1;
            $user->bad_time_promocode = now();
            $user->save();
        }
		*/

		$allSumma = Payment::where('account_id', auth()->user()->id)->sum('price') * 10;
        return ['error' => $error, 'sum' => $allSumma];

    }

    public function dailyBonusGenerate() {
        $winnerArray = [1000, 500,150,150,150,200,200,200,100,100,100,100,100,100,50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,20,20,20,20,20,20,20,20,20,20,20,20,20,20,
		0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20];
        srand(mt_rand() + rand() - 73266188 + 127565362 - rand() + mt_rand() * 1.5322 / 10);
        shuffle($winnerArray);


        $payment = new Payment;
        $payment->account_id = auth()->user()->id;
        $payment->price = $winnerArray[0] / 10;
        $payment->payment_type_id = 5;
        $payment->save();


        $allSumma = Payment::where('account_id', auth()->user()->id)->sum('price') * 10;

        return ['winner' => $winnerArray[0], 'sum' => $allSumma];
    }

    public function settingMusic(Request $request) {
        $user = auth()->user();
        $data = $request->all();

        if($data['result'] == 1) {
            session(['is_music' => 1]);
        }

        if($data['result'] == 2) {
            session()->forget('is_music');
        }

        $user->save();
    }

}
