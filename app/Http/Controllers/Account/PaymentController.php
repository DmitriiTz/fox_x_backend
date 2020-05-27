<?php

namespace App\Http\Controllers\Account;

use App\Order;
use App\Payment;
use App\User;
use App\WithdrawMoneyAccountApplication;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function toUpAccount(Request $request) {


        $summa = $request->summa;
        $user = auth()->user();
        $payment = new Order;
        $payment->account_id = $user->id;
        $payment->price = $summa;
        $payment->payment_system_id = $request->paymentType;
        $payment->save();

        $merchantId = 115943;

        if($request->paymentType == 1) {
            $codeCurrency = 63;
        }

        if($request->paymentType == 2) {
            $codeCurrency = 80;
        }

        if($request->paymentType == 3) {
            $codeCurrency = 45;
        }

        if($request->paymentType == 4) {
            $codeCurrency = 160;
        }


        $orderId = $payment->id;
        $sign = md5($merchantId.':'.$summa.':nio9ahlh:'.$orderId);

        $html = view('payment-form.free-kassa', ['merchantId' => $merchantId, 'price' => $summa, 'orderId' => $orderId, 'sign' => $sign, 'codeCurrency' => $codeCurrency])->render();

        return $html;


    }

    public function withdrawalOfFundsAccount(Request $request) {

        $frozenapp = WithdrawMoneyAccountApplication::where('account_id',auth()->user()->id)->where('status_id',8)->first();
        if(!$frozenapp)
        {
        if(getBalance(auth()->user()) >= $request->summa)
        {
        $payment = new Payment;
        $payment->account_id = auth()->user()->id;
        $payment->price = -$request->summa;
        $payment->payment_system_id = $request->paymentType;
        $payment->payment_type_id = 2;
        $payment->save();

        $application = new WithdrawMoneyAccountApplication;
        $application->account_id = auth()->user()->id;
        $application->status_id = 1;
        $application->price = $request->summa;
        $application->payment_system_id = $request->paymentType;
        $application->phone = $request->phone;
        $application->payment_id = $payment->id;
        $application->save();

        $balance = getBalance(auth()->user());
        return $balance;
        }
        else
        {
            $application = new WithdrawMoneyAccountApplication;
        $application->account_id = auth()->user()->id;
        $application->price = $request->summa;
        $application->payment_system_id = $request->paymentType;
        $application->phone = $request->phone;
        $application->payment_id = $payment->id;
        $application->save();

        $balance = getBalance(auth()->user());
        return $balance;

        }
        }


    }

    public function successPayment(Request $request) {

        $data = $request->all();
        info(json_encode($data));

        if(isset($data['MERCHANT_ORDER_ID'])) {
            $order = Order::findOrFail($data['MERCHANT_ORDER_ID']);

            if($order->status_id == 1) {
                return redirect('/');
            }

            $order->status_id = 1;
            $order->save();
            $experience = $order->price * 10 / 2;
            $user = User::find($order->account_id);

            if($user->experience < 80000) {
                $experience = $user->experience + $experience;
            }
            else {
                $experience = 80000;
            }


            $payment = new Payment;
            $payment->account_id = $order->account_id;
            $payment->price = $order->price;
            $payment->payment_system_id = $order->payment_system_id;
            $payment->payment_type_id = 1;
            $payment->save();

            if($user->referral_account_id) {
                $referralAccount = User::find($user->referral_account_id);

                if($referralAccount->is_referral_power) {
                    $lvl = getLevel($referralAccount);
                    $percent = $lvl * 0.1;
                    $referralSum = ($order->price * $percent) / 100;

                    $payment = new Payment;
                    $payment->account_id = $referralAccount->id;
                    $payment->referral_account_id = $user->id;
                    $payment->price = $referralSum;
                    $payment->payment_type_id = 3;
                    $payment->save();

                }

            }

            $user->experience = $experience;

            if($user->experience >= 80000) {
                $user->experience = 80000;
            }

            $user->save();


            return redirect('/');
        }

    }


    public function paymentCallback() {
        return 'YES';
    }


}
