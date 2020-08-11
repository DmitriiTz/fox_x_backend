<?php

namespace App\Http\Controllers\ApiAuth;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $input = $request->validated();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        return response()->json([
            'token' => $user->createToken(config('app.name')),
            'name' => $user->name,
        ]);
    }
    public function redirectToProvider(Request $request, string $driver)
    {
        return Socialite::driver($driver)->stateless()->redirect();
    }
    public function handleProviderCallback(Request $request, string $driver)
    {
        //try {
            $auth_user = Socialite::driver($driver)->stateless()->user();
        //}
        //catch (\Exception $exception)
        //{
        //    $auth_user = null;
        //}
        if(!$auth_user)
            return redirect()->to(config('app.front_url').'?error');
        if($auth_user->email)
            $user = User::query()->where('email',$auth_user->email)->first();
        else
            $user = User::query()->where('provider',$driver)->where('provider_id',$auth_user->id)->first();
        if(!$user)
        {
            $user = new User();
            $user->name = $auth_user->name;
            $user->email = $auth_user->email;
            $user->image = $auth_user->avatar;
        }
        if($driver == 'vkontakte')
        {
            $user->link_vk = 'https://vk.com/id'.$auth_user->id;
            $user->vkontakte_id = $auth_user->id;
        }
        $user->provider = $driver;
        $user->provider_id = $auth_user->id;
        $user->save();
        $token = $user->createToken('social')->accessToken;
        return redirect()->to(config('app.front_url') .'?token='.$token);
    }
    public function login(Request $request)
    {
        Auth::logout();

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'You cannot sign with those credentials',
                'errors' => 'Unauthorised'
            ], 401);
        }

        $token = Auth::user()->createToken(config('app.name'));
        $token->token->expires_at = $request->remember_me ?
            Carbon::now()->addMonth() :
            Carbon::now()->addDay();

        $token->token->save();

        return response()->json([
            'token_type' => 'Bearer',
            'token' => $token->accessToken,
            'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString(),
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'You are successfully logged out',
        ]);
    }

    public function checkAuth()
    {
        if (Auth::check()) {
            $data = [
                'user' => Auth::user()
            ];

            return response()->json($data);
        }
        return response()->json('No Auth');
    }
}
