<?php

namespace App\Http\Controllers;

use App\HistoryGame;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Laravel\Socialite\Facades\Socialite;
use Storage;
use Image;
use Validator;

class AuthController extends Controller
{

    public function redirectToProvider($driver) {
        try {
            return Socialite::driver($driver)->redirect();
        }
        catch (Exception $e) {

            return redirect ('/');
        }
    }

    public function handleProviderCallback(Request $request, $driver)
    {

        if (strripos(url()->previous(), env('APP_URL')) !== false) {
            session()->put('redirect-after-auth', url()->previous());
        }


        if($request->error) {
            return redirect ('/');
        }
        try {
            $user = Socialite::driver($driver)->user();

            if($user) {


                $account = User::where($driver.'_id', $user->id)->first();

                if(!$account) {


                    $account = new User;
                    $account->role_id = 1;
                    $account->{$driver.'_id'} = $user->id;
                    $nameAccount = explode(' ', $user->name);
                    $account->name = $nameAccount[0];
                    if(isset($user->avatar)) {
                        $folder = 'img/'.str_random(8);
                        $explodePath = explode('.', $user->avatar);
                        $type = false;

                        if($explodePath[1] == 'png') {
                            $type = 'png';
                        }
                        else {
                            $type = 'jpeg';
                        }


                        Image::make($user->avatar)->save($folder.'.'.$type);
                        $account->image = $folder.'.'.$type;
                    }

                    $account->last_name = $nameAccount[1];

                    if(isset($user->email)) {
                        $account->email = $user->email;
                    }


                    if(session('referral_id')) {
                        $account->referral_account_id = session('referral_id');
                    }

                    $account->save();

                    Auth::login($account);
                    return redirect()->route('home');
                }

                Auth::login($account);
                return redirect(session()->get('redirect-after-auth') ? : '/');
            }

        }

        catch (Exception $e) {

            return redirect ('/');
        }


    }

    public function logout() {
        auth()->logout();
        return redirect()->route('home');
    }

    public function authVk() {
        Auth::loginUsingId(1);
        return redirect()->back();
    }

    public function authFacebook() {
        Auth::loginUsingId(3);
        return redirect()->back();
    }

    public function referralLink(Request $request, $userId = false) {
        if($userId) {
            session(['referral_id' => $userId]);
        }

        return redirect('/');
    }


    public $rulesRegistration = [
        'login'         =>      'required|min:5|unique:users,login|string|regex:/^[A-Za-z][A-Za-z0-9]*$/',
        'password'      =>      'required|string|min:8|same:repeatPassword|string',
        'name'          =>      'required|string|min:2|string',
        'email'         =>      'required||email|unique:users,email',
        'repeatPassword'=>      'required|same:password',
    ];


    public $rulesAuth = [
        'email' => 'required|email|exists:users,email',
        'password' => 'required|min:8'
    ];

    public function registration(Request $request) {
        $data = $request->all();
        $validator = Validator::make($request->all(), $this->rulesRegistration);

        if($validator->fails()) {
            $view = view('account.blocks.registration', ['errors' => $validator->errors(), 'request' => $request])->render();
            return ['error' => 1, 'view' => $view];
        }
		$captcha = $this->captcha('6LfBW58UAAAAAI-Ne7uudafgsKVp9N0syULK397s', $request['g-recaptcha-response']);
		if($captcha == 'false')
		{
			$view = view('account.blocks.registration', ['recaptcha' => '1', 'request' => $request])->render();
            return ['error' => 1, 'view' => $view];
		}

        $password = $data['password'];
        $user = new User;
        $user->name = $data['name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        $user->password = bcrypt($password);

        if(session('referral_id')) {
            $user->referral_account_id = session('referral_id');
        }

        $user->save();

        Auth::loginUsingId($user->id);

        return ['error' => 0];

    }

    public function authUser(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8'
        ]);

        if($validator->fails()) {
            $view = view('account.blocks.registration', ['errors' => $validator->errors()])->render();
            return ['error' => 1, 'view' => $view];
        }


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return ['error' => 0];
        }
        else {
            $view = view('account.blocks.registration', ['errors' => ['email' => 'Неправильные данные']])->render();
            return ['error' => 1, 'view' => $view];
        }


    }

    public function checkAuthUser(Request $request) {

        $user = auth()->user();

        if($user->id == 4) {
            return 1;
        }

        if($request->gameId) {
            $game = HistoryGame::findOrFail($request->gameId);


            if($game->create_account_id == $user->id) {
                return 2;
            }

            if($game->participants->sum('cash') > getBalance($user)) {
                return 4;
            }

            if($game->participants->count() == 1) {
                return 5;
            }

            return 3;

        }

    }
	public function curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
	function captcha($secret, $resp)
	{
		$params = [
			'secret' => $secret,
			'response' => $resp,
			'remoteip' => $_SERVER['REMOTE_ADDR']
		];
		$curl = curl_init('https://www.google.com/recaptcha/api/siteverify?' . http_build_query($params));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$response = json_decode(curl_exec($curl));
		curl_close($curl);
		if (isset($response->success) && $response->success == true) {
			return 'true';
		} else {
			return 'false';
		}
	}

}
