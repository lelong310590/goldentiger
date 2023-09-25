<?php

namespace App\Services;

use App\Http\Traits\Notify;
use App\Models\Investment;
use App\Models\ManageTime;
use App\Models\ReferralBonus;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Image;

class BasicService
{
    use Notify;

    public function validateImage(object $getImage, string $path)
    {
        if ($getImage->getClientOriginalExtension() == 'jpg' or $getImage->getClientOriginalName() == 'jpeg' or $getImage->getClientOriginalName() == 'png') {
            $image = uniqid() . '.' . $getImage->getClientOriginalExtension();
        } else {
            $image = uniqid() . '.jpg';
        }
        Image::make($getImage->getRealPath())->resize(300, 250)->save($path . $image);
        return $image;
    }

    public function validateDate(string $date)
    {
        if (preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{2,4}$/", $date)) {
            return true;
        } else {
            return false;
        }
    }

    public function validateKeyword(string $search, string $keyword)
    {
        return preg_match('~' . preg_quote($search, '~') . '~i', $keyword);
    }

    public function cryptoQR($wallet, $amount, $crypto = null)
    {

        $varb = $wallet . "?amount=" . $amount;
        return "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$varb&choe=UTF-8";
    }

    public function preparePaymentUpgradation($order)
    {
        $basic = (object) config('basic');
        $gateway = $order->gateway;


        if ($order->status == 0) {
            $order['status'] = 1;
            $order->update();

            $user = $order->user;

            $amount = getAmount($order->amount);
            $trx = $order->transaction;

            if ($order->plan_id) {
                $plan  = $order->plan;
                $remarks = 'Invested On ' . optional($order->plan)->name;
                $this->makeTransaction($user, $amount, getAmount($order->charge), $trx_type = '-', $balance_type = 'payment',  $trx, $remarks);

                $profit = ($plan->profit_type == 1) ? ($amount * $plan->profit) / 100 : $plan->profit;
                $maturity = ($plan->is_lifetime == 1) ? '-1' : $plan->repeatable;

                $timeManage = ManageTime::where('time', $plan->schedule)->first();

                //// For Fixed Plan
                if ($plan->fixed_amount != 0 && ($plan->fixed_amount == $amount)) {
                    $this->makeInvest($user, $plan, $amount, $profit, $maturity, $timeManage, $trx);
                } elseif ($plan->fixed_amount == 0) {
                    $this->makeInvest($user, $plan, $amount, $profit, $maturity, $timeManage, $trx);
                }

                if ($basic->investment_commission == 1) {
                    $this->setBonus($user, $amount, $type = 'invest');
                }

                $this->sendMailSms($user, $type = 'PLAN_PURCHASE', [
                    'transaction_id' => $trx,
                    'amount' => getAmount($amount),
                    'currency' => $basic->currency_symbol,
                    'profit_amount' => $profit,
                ]);


                $msg = [
                    'username' => $user->username,
                    'amount' => getAmount($amount),
                    'currency' => $basic->currency_symbol,
                    'plan_name' => $plan->name
                ];
                $action = [
                    "link" => route('admin.user.plan-purchaseLog',$user->id),
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->adminPushNotification('PLAN_PURCHASE', $msg, $action);

            } else {
                $user->balance += $order->amount;
                $user->save();

                $this->makeTransaction($user, getAmount($order->amount), getAmount($order->charge), $trx_type = '+', $balance_type = 'deposit', $order->transaction, $remarks = 'Deposit Via ' . $gateway->name);

                if ($basic->deposit_commission == 1) {
                    $this->setBonus($user, getAmount($order->amount), $type = 'deposit');
                }

                $msg = [
                    'username' => $user->username,
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'gateway' => $gateway->name
                ];
                $action = [
                    "link" => route('admin.user.fundLog', $user->id),
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->adminPushNotification('PAYMENT_COMPLETE', $msg, $action);
                $this->sendMailSms($user, 'PAYMENT_COMPLETE', [
                    'gateway_name' => $gateway->name,
                    'amount' => getAmount($order->amount),
                    'charge' => getAmount($order->charge),
                    'currency' => $basic->currency,
                    'transaction' => $order->transaction,
                    'remaining_balance' => getAmount($user->balance)
                ]);
            }
            session()->forget('amount');
            session()->forget('plan_id');
        }
    }




    public function setBonus($user, $amount, $commissionType = ''){

        $basic = (object) config('basic');
        $userId = $user->id;
        $i = 1;
        $level = \App\Models\Referral::where('commission_type', $commissionType)->count();
        while ($userId != "" || $userId != "0" || $i < $level) {
            $me = \App\Models\User::with('referral')->find($userId);
            $refer = $me->referral;
            if (!$refer) {
                break;
            }
            $commission = \App\Models\Referral::where('commission_type', $commissionType)->where('level', $i)->first();
            if (!$commission) {
                break;
            }
            $com = ($amount * $commission->percent) / 100;
            $new_bal = getAmount($refer->interest_balance + $com);
            $refer->interest_balance = $new_bal;
            $refer->save();

            $trx = strRandom();
            $balance_type = 'interest_balance';

            $remarks = ' level ' . $i . ' Referral bonus From ' . $user->username;

            $this->makeTransaction($refer, $com, 0, '+', $balance_type, $trx, $remarks);

            $bonus = new \App\Models\ReferralBonus();
            $bonus->from_user_id = $refer->id;
            $bonus->to_user_id = $user->id;
            $bonus->level = $i;
            $bonus->amount = getAmount($com);
            $bonus->main_balance = $new_bal;
            $bonus->transaction = $trx;
            $bonus->type = $commissionType;
            $bonus->remarks = $remarks;
            $bonus->save();


            $this->sendMailSms($refer, $type = 'REFERRAL_BONUS', [
                'transaction_id' => $trx,
                'amount' => getAmount($com),
                'currency' => $basic->currency_symbol,
                'bonus_from' => $user->username,
                'final_balance' => $refer->interest_balance,
                'level' => $i
            ]);


            $msg = [
                'bonus_from' => $user->username,
                'amount' => getAmount($com),
                'currency' => $basic->currency_symbol,
                'level' => $i
            ];
            $action = [
                "link" => route('user.referral.bonus'),
                "icon" => "fa fa-money-bill-alt"
            ];
            $this->userPushNotification($refer,'REFERRAL_BONUS', $msg, $action);

            $userId = $refer->id;
            $i++;
        }
        return 0;

    }


    /**
     * @param $user
     * @param $amount
     * @param $charge
     * @param $trx_type
     * @param $balance_type
     * @param $trx_id
     * @param $remarks
     */
    public function makeTransaction($user, $amount, $charge, $trx_type = null, $balance_type, $trx_id, $remarks = null): void
    {
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = getAmount($amount);
        $transaction->charge = $charge;
        $transaction->trx_type = $trx_type;
        $transaction->balance_type = $balance_type;
        $transaction->final_balance = $user[$balance_type];
        $transaction->trx_id = $trx_id;
        $transaction->remarks = $remarks;
        $transaction->save();
    }


    /**
     * @param $user
     * @param $plan
     * @param $amount
     * @param $profit
     * @param $maturity
     * @param $timeManage
     * @param $trx
     */
    public function makeInvest($user, $plan, $amount, $profit, $maturity, $timeManage, $trx): void
    {
        $invest = new Investment();
        $invest->user_id = $user->id;
        $invest->plan_id = $plan->id;
        $invest->amount = $amount;
        $invest->profit = $profit;
        $invest->maturity = $maturity;
        $invest->point_in_time = $plan->schedule;
        $invest->point_in_text = $timeManage->name;
        $invest->afterward = Carbon::parse(now())->addHours($plan->schedule);
        $invest->status = 1;
        $invest->capital_back = $plan->is_capital_back;
        $invest->trx = $trx;
        $invest->save();
    }

    public function isUserInvested($user) {
        $invest = Investment::where('user_id', $user->id)->where('type', 1)->first();
        return $invest ? true : false;
    }

    public function treeComission($user, $profit)
    {
        $id = $user->id;
        // Find ref level 1 to 6
        $level = 1;
        while ($id != "" || $id != "0") {
            if (self::isUserExists($id)) {
                $user = User::find($id);
                $userRef = $user->referral ?? null;
                if (!$userRef) {
                    break;
                }
                if($level == 1) {
                    // 30% and no rule
                    $percent = 30;
                    self::saveInterestAndTransaction($user, $userRef, $percent, $profit, $level);
                } elseif ($level == 2) {
                    // 20% and have one F1
                    if(self::countValidF1($userRef->id) >= 1) {
                        $percent = 20;
                        self::saveInterestAndTransaction($user, $userRef, $percent, $profit, $level);
                    } else {
                        break;
                    }
                } elseif ($level == 3) {
                    // 10% with no rule
                    $percent = 10;
                    if(self::countValidF1($userRef->id) >= 2) {
                        self::saveInterestAndTransaction($user, $userRef, $percent, $profit, $level);
                    } else {
                        break;
                    }
                } elseif ($level == 4) {
                    // 5% and have more then 2 F1
                    $percent = 5;
                    if(self::countValidF1($userRef->id) >= 2) {
                        self::saveInterestAndTransaction($user, $userRef, $percent, $profit, $level);
                    } else {
                        break;
                    }
                } elseif ($level == 5) {
                    // 5% with no rule
                    $percent = 5;
                    if(self::countValidF1($userRef->id) >= 3) {
                        self::saveInterestAndTransaction($user, $userRef, $percent, $profit, $level);
                    } else {
                        break;
                    }
                } elseif ($level == 6) {
                    // 5% and have more then 3 F1
                    $percent = 5;
                    if(self::countValidF1($userRef->id) >= 3) {
                        self::saveInterestAndTransaction($user, $userRef, $percent, $profit, $level);
                    } else {
                        break;
                    }
                }
                $id = $userRef->id;
                $level++;
            } else {
                break;
            }
        }
    
    }
    public function calculateComission($user, $profit) {
        if($user->f1_of) {
            $userF1 = User::find($user->f1_of);
            // 30% and no rule
            $percent = 30;
            $level = 1;
            self::saveInterestAndTransaction($user, $userF1, $percent, $profit, $level);
        }
        if($user->f2_of) {
            $userF2 = User::find($user->f2_of);
            // 20% and have one F1
            $level = 2;
            if(self::countValidF1($userF2->id) >= 1) {
                $percent = 20;
                self::saveInterestAndTransaction($user, $userF2, $percent, $profit, $level);
            }
        }
        if($user->f3_of) {
            $userF3 = User::find($user->f3_of);
            // 10% with no rule
            $level = 3;
            if(self::countValidF1($userF3->id) >= 2) {
                $percent = 10;
                self::saveInterestAndTransaction($user, $userF3, $percent, $profit, $level);
            }
        }
        if($user->f4_of) {
            $userF4 = User::find($user->f4_of);
            // 10% with no rule
            $level = 4;
            if(self::countValidF1($userF4->id) >= 2) {
                $percent = 5;
                self::saveInterestAndTransaction($user, $userF4, $percent, $profit, $level);
            }
        }
        if($user->f5_of) {
            $userF5 = User::find($user->f5_of);
            // 10% with no rule
            $level = 5;
            if(self::countValidF1($userF5->id) >= 3) {
                $percent = 5;
                self::saveInterestAndTransaction($user, $userF5, $percent, $profit, $level);
            }
        }
        if($user->f6_of) {
            $userF6 = User::find($user->f6_of);
            // 10% with no rule
            $level = 6;
            if(self::countValidF1($userF6->id) >= 3) {
                $percent = 5;
                self::saveInterestAndTransaction($user, $userF6, $percent, $profit, $level);
            }
        }
    }

    function countValidF1($userRefId){
        $totalF1 = User::where('referral_id', $userRefId)->pluck('id')->toArray();
        $investValidF1 = Investment::whereIn('user_id', $totalF1)->where('type', 1)->groupBy('user_id')->get()->count();

        return $investValidF1;
    }
    public function saveInterestAndTransaction($user, $userRef, $percent, $profit, $level){
        $basic = (object) config('basic');
        $amount = $profit * $percent/100;
        $userRef->referral_balance = getAmount($userRef->referral_balance + $amount);
        $userRef->save();

        $remarks =  getAmount($amount) . ' ' . $basic->currency . ' Interest Commission ' . $percent . '% From F' . $level;
        self::makeTransaction($userRef, $amount, 0, $trx_type = '+', $balance_type = 'referral_balance',  $trx = strRandom(), $remarks);

        // Add bonus log
        $bonus = new ReferralBonus();
        $bonus->from_user_id = $user->id;
        $bonus->to_user_id = $userRef->id;
        $bonus->level = $level;
        $bonus->amount = getAmount($amount);
        $bonus->main_balance = $userRef->referral_balance;
        $bonus->transaction = $trx;
        $bonus->type = $balance_type;
        $bonus->remarks = $remarks;
        $bonus->save();
    }

    function isUserExists($id)
    {
        $user = User::find($id);
        if ($user) {
            return true;
        } else {
            return false;
        }
    }
}
