<?php

namespace App\Http\Controllers\User;

use App\Helper\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\Configure;
use App\Models\ContentDetails;
use App\Models\Fund;
use App\Models\Gateway;
use App\Models\IdentifyForm;
use App\Models\Investment;
use App\Models\KYC;
use App\Models\Language;
use App\Models\ManagePlan;
use App\Models\ManageTime;
use App\Models\MoneyTransfer;
use App\Models\PayoutLog;
use App\Models\PayoutMethod;
use App\Models\ReferralBonus;
use App\Models\Template;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Stevebauman\Purify\Facades\Purify;
use Facades\App\Services\BasicService;

use hisorange\BrowserDetect\Parser as Browser;

class HomeController extends Controller
{
    use Upload, AuthenticatesUsers, Notify;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $configure = $this->getConfigure();
        $data['walletBalance'] = getAmount($this->user->balance);
        $data['priceGtf'] = (float) $configure->price_gtf;
        $data['gtfBalance'] = getAmount($this->user->gtf_balance);
        $data['gtfInterestBalance'] = getAmount($this->user->gtf_interest_balance);
        $data['gtfInvest'] = getAmount($this->user->stakes()->whereStatus(1)->sum('amount'));
        $data['interestBalance'] = getAmount($this->user->interest_balance);
        $data['referralBalance'] = getAmount($this->user->referral_balance);
        $data['totalDeposit'] = getAmount($this->user->funds()->whereNull('plan_id')->whereStatus(1)->sum('amount'));
        $data['totalPayout'] = getAmount($this->user->payout()->whereStatus(2)->sum('amount'));
        $data['depositBonus'] = getAmount($this->user->referralBonusLog()->where('type', 'deposit')->sum('amount'));
        $data['investBonus'] = getAmount($this->user->referralBonusLog()->where('type', 'invest')->sum('amount'));
        $data['lastBonus'] = getAmount(optional($this->user->referralBonusLog()->latest()->first())->amount);
        $data['investments'] = $this->user->invests()->paginate(config('basic.paginate'));
        $data['totalInterestProfit'] = getAmount($this->user->transaction()->where('balance_type', 'interest_balance')->where('trx_type', '+')->sum('amount'));

        $roi = Investment::where('user_id', $this->user->id)
            ->selectRaw('SUM( amount ) AS totalInvestAmount')
            ->selectRaw('COUNT( id ) AS totalInvest')
            ->selectRaw('COUNT(CASE WHEN status = 0  THEN id END) AS completed')
            ->selectRaw('COUNT(CASE WHEN status = 1  THEN id END) AS running')
            ->selectRaw('SUM(CASE WHEN maturity != -1  THEN maturity * profit END) AS expectedProfit')
            ->selectRaw('SUM(recurring_time * profit) AS returnProfit')
            ->where('type', 1)
            ->get()->makeHidden('nextPayment')->toArray();
        $data['roi'] = collect($roi)->collapse();
        $data['ticket'] = Ticket::where('user_id', $this->user->id)->count();

        $monthlyInvestment = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
        Investment::where('user_id', $this->user->id)
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->select(
                DB::raw('sum(amount) as totalAmount'),
                DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->where('type', 1)
            ->get()->makeHidden('nextPayment')->map(function ($item) use ($monthlyInvestment) {
                $monthlyInvestment->put($item['months'], round($item['totalAmount'], 2));
            });
        $monthly['investment'] = $monthlyInvestment;


        $monthlyPayout = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
        $this->user->payout()->whereStatus(2)
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->select(
                DB::raw('sum(amount) as totalAmount'),
                DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get()->map(function ($item) use ($monthlyPayout) {
                $monthlyPayout->put($item['months'], round($item['totalAmount'], 2));
            });
        $monthly['payout'] = $monthlyPayout;


        $monthlyFunding = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
        $this->user->funds()->whereNull('plan_id')->whereStatus(1)
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->select(
                DB::raw('sum(amount) as totalAmount'),
                DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get()->map(function ($item) use ($monthlyFunding) {
                $monthlyFunding->put($item['months'], round($item['totalAmount'], 2));
            });
        $monthly['funding'] = $monthlyFunding;

        $monthlyReferralInvestBonus = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
        $this->user->referralBonusLog()->where('type', 'invest')
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->select(
                DB::raw('sum(amount) as totalAmount'),
                DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get()->map(function ($item) use ($monthlyReferralInvestBonus) {
                $monthlyReferralInvestBonus->put($item['months'], round($item['totalAmount'], 2));
            });

        $monthly['referralInvestBonus'] = $monthlyReferralInvestBonus;


        $monthlyReferralFundBonus = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);

        $this->user->referralBonusLog()->where('type', 'deposit')
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->select(
                DB::raw('sum(amount) as totalAmount'),
                DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get()->map(function ($item) use ($monthlyReferralFundBonus) {
                $monthlyReferralFundBonus->put($item['months'], round($item['totalAmount'], 2));
            });
        $monthly['referralFundBonus'] = $monthlyReferralFundBonus;


        $latestRegisteredUser = User::where('referral_id', $this->user->id)->latest()->first();


        return view($this->theme . 'user.dashboard', $data, compact('monthly', 'latestRegisteredUser'));
    }


    public function transaction()
    {
        $transactions = $this->user->transaction()->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view($this->theme . 'user.transaction.index', compact('transactions'));
    }

    public function transactionSearch(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $transaction = Transaction::where('user_id', $this->user->id)->with('user')
            ->when(@$search['transaction_id'], function ($query) use ($search) {
                return $query->where('trx_id', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when(@$search['remark'], function ($query) use ($search) {
                return $query->where('remarks', 'LIKE', "%{$search['remark']}%");
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));
        $transactions = $transaction->appends($search);


        return view($this->theme . 'user.transaction.index', compact('transactions'));

    }

    public function fundHistory()
    {
        $funds = Fund::where('user_id', $this->user->id)->where('status', '!=', 0)->where('plan_id', null)->orderBy('id', 'DESC')->with('gateway')->paginate(config('basic.paginate'));
        return view($this->theme . 'user.transaction.fundHistory', compact('funds'));
    }

    public function fundHistorySearch(Request $request)
    {
        $search = $request->all();

        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $funds = Fund::orderBy('id', 'DESC')->where('user_id', $this->user->id)->where('status', '!=', 0)
            ->when(isset($search['name']), function ($query) use ($search) {
                return $query->where('transaction', 'LIKE', $search['name']);
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->with('gateway')
            ->paginate(config('basic.paginate'));
        $funds->appends($search);

        return view($this->theme . 'user.transaction.fundHistory', compact('funds'));

    }


    public function addFund()
    {
        if (session()->get('plan_id') != null) {
            return redirect(route('user.payment'));
        }

        $data['totalPayment'] = null;
        $data['gateways'] = Gateway::where('status', 1)->orderBy('sort_by', 'ASC')->get();

        return view($this->theme . 'user.addFund', $data);
    }

    public function payment()
    {
        $encPlanId = session()->get('plan_id');
        if ($encPlanId == null) {
            return redirect(route('user.addFund'));
        }
        $plan = ManagePlan::where('id', decrypt($encPlanId))->where('status', 1)->firstOrFail();
        $amount = session()->get('amount');
        $data['totalPayment'] = decrypt($amount);
        $data['gateways'] = Gateway::where('status', 1)->orderBy('sort_by', 'ASC')->get();
        $data['plan'] = $plan;
        return view($this->theme . 'user.payment', $data);
    }


    public function profile(Request $request)
    {
        $validator = Validator::make($request->all(), []);
        $data['user'] = $this->user;
        $data['languages'] = Language::all();
        $data['identityFormList'] = IdentifyForm::where('status', 1)->get();
        if ($request->has('identity_type')) {
            $validator->errors()->add('identity', '1');
            $data['identity_type'] = $request->identity_type;
            $data['identityForm'] = IdentifyForm::where('slug', trim($request->identity_type))->where('status', 1)->firstOrFail();
            return view($this->theme . 'user.profile.myprofile', $data)->withErrors($validator);
        }

        return view($this->theme . 'user.profile.myprofile', $data);
    }


    public function updateProfile(Request $request)
    {
        $allowedExtensions = array('jpg', 'png', 'jpeg');

        $image = $request->image;
        $this->validate($request, [
            'image' => [
                'required',
                'max:4096',
                function ($fail) use ($image, $allowedExtensions) {
                    $ext = strtolower($image->getClientOriginalExtension());
                    if (($image->getSize() / 1000000) > 2) {
                        return $fail("Images MAX  2MB ALLOW!");
                    }
                    if (!in_array($ext, $allowedExtensions)) {
                        return $fail("Only png, jpg, jpeg images are allowed");
                    }
                }
            ]
        ]);
        $user = $this->user;
        if ($request->hasFile('image')) {
            $path = config('location.user.path');
            try {
                $user->image = $this->uploadImage($image, $path);
            } catch (\Exception $exp) {
                return back()->with('error', 'Could not upload your ' . $image)->withInput();
            }
        }
        $user->save();
        return back()->with('success', 'Updated Successfully.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateWallet(Request $request) {
        $languages = Language::all()->map(function ($item) {
            return $item->id;
        });

        $req = Purify::clean($request->all());
        $rules = [
            'real_wallet' => 'required',
        ];
        $message = [
            'real_wallet.required' => 'Wallet field is required',
        ];
        $user = $this->user;
        $user->real_wallet = $req['real_wallet'];
        $user->save();
        return back()->with('success', 'Updated Successfully.');
    }

    public function updateInformation(Request $request)
    {

        $languages = Language::all()->map(function ($item) {
            return $item->id;
        });

        $req = Purify::clean($request->all());
        $user = $this->user;
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => "sometimes|required|alpha_dash|min:5|unique:users,username," . $user->id,
            'address' => 'required',
            'language_id' => Rule::in($languages),
        ];
        $message = [
            'firstname.required' => 'First Name field is required',
            'lastname.required' => 'Last Name field is required',
        ];

        $validator = Validator::make($req, $rules, $message);
        if ($validator->fails()) {
            $validator->errors()->add('profile', '1');
            return back()->withErrors($validator)->withInput();
        }
        $user->language_id = $req['language_id'];
        $user->firstname = $req['firstname'];
        $user->lastname = $req['lastname'];
        $user->username = $req['username'];
        $user->address = $req['address'];
        $user->save();
        return back()->with('success', 'Updated Successfully.');
    }


    public function updatePassword(Request $request)
    {

        $rules = [
            'current_password' => "required",
            'password' => "required|min:5|confirmed",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->errors()->add('password', '1');
            return back()->withErrors($validator)->withInput();
        }
        $user = $this->user;
        try {
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->save();
                return back()->with('success', 'Password Changes successfully.');
            } else {
                throw new \Exception('Current password did not match');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function twoStepSecurity()
    {
        $basic = (object)config('basic');
        $ga = new GoogleAuthenticator();
        if($this->user->two_fa_code) {
            $secret = $this->user->two_fa_code;
        } else {
            $secret = $ga->createSecret();
            $this->user->two_fa_code = $secret;
            $this->user->save();
        }

        $qrCodeUrl = $ga->getQRCodeGoogleUrl($this->user->username . '@' . $basic->site_title, $secret);

        return view($this->theme . 'user.twoFA.index', compact('secret', 'qrCodeUrl'));
    }

    public function twoStepEnable(Request $request)
    {
        $user = $this->user;
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        $userCode = $request->code;
        if ($oneCode == $userCode) {
            $user['two_fa'] = 1;
            $user['two_fa_verify'] = 1;
            $user['two_fa_code'] = $request->key;
            $user->save();
            $browser = new Browser();
            $this->mail($user, 'TWO_STEP_ENABLED', [
                'action' => 'Enabled',
                'code' => $user->two_fa_code,
                'ip' => request()->ip(),
                'browser' => $browser->browserName() . ', ' . $browser->platformName(),
                'time' => date('d M, Y h:i:s A'),
            ]);

            Auth::guard()->logout();
            $request->session()->invalidate();
            return $this->loggedOut($request) ?: redirect('/login')->with('success', 'Google Authenticator Has Been Enabled.');
        } else {
            return back()->with('error', 'Wrong Verification Code.');
        }
    }


    public function twoStepDisable(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);
        $user = $this->user;
        $ga = new GoogleAuthenticator();

        $secret = $user->two_fa_code;
        $oneCode = $ga->getCode($secret);
        $userCode = $request->code;

        if ($oneCode == $userCode) {
            $user['two_fa'] = 0;
            $user['two_fa_verify'] = 1;
            $user['two_fa_code'] = null;
            $user->save();
            $browser = new Browser();
            $this->mail($user, 'TWO_STEP_DISABLED', [
                'action' => 'Disabled',
                'ip' => request()->ip(),
                'browser' => $browser->browserName() . ', ' . $browser->platformName(),
                'time' => date('d M, Y h:i:s A'),
            ]);

            Auth::guard()->logout();
            $request->session()->invalidate();
            return $this->loggedOut($request) ?: redirect('/login')->with('success', 'Google Authenticator Has Been Disabled.');
        } else {
            return back()->with('error', 'Wrong Verification Code.');
        }
    }

    public function purchasePlan(Request $request)
    {
        $this->validate($request, [
            'balance_type' => 'required',
            'amount' => 'required|numeric',
            'plan_id' => 'required',
        ]);

        $user = $this->user;
        $plan = ManagePlan::where('id', $request->plan_id)->where('status', 1)->first();
        if (!$plan) {
            return back()->with('error', 'Invalid Plan Request');
        }

        $timeManage = ManageTime::where('time', $plan->schedule)->first();

        $balance_type = $request->balance_type;
        if (!in_array($balance_type, ['balance', 'interest_balance', 'checkout'])) {
            return back()->with('error', 'Invalid Wallet Type');
        }


        $amount = $request->amount;
        $basic = (object)config('basic');
        if ($plan->fixed_amount == '0' && $amount < $plan->minimum_amount) {
            return back()->with('error', "Invest Limit " . $plan->price);
        } elseif ($plan->fixed_amount == '0' && $amount > $plan->maximum_amount) {
            return back()->with('error', "Invest Limit " . $plan->price);
        } elseif ($plan->fixed_amount != '0' && $amount != $plan->fixed_amount) {
            return back()->with('error', "Please invest " . $plan->price);
        }

        if ($balance_type == "checkout") {
            session()->put('amount', encrypt($amount));
            session()->put('plan_id', encrypt($plan->id));
            return redirect()->route('user.payment');
        }

        if ($amount > $user->$balance_type) {
            return back()->with('error', 'Insufficient Balance');
        }
        $configure = $this->getConfigure();

        $new_balance = getAmount($user->$balance_type - $amount);
        $user->$balance_type = $new_balance;

        $userRef = $user->referral;
        if($userRef) {
            $userRef->total_invest_f1 += $amount;
            // Plus 6% referral commission
            $com = $amount * 6/100;
            $userRef->referral_balance += $com;
            $trx = strRandom();
            $balance_type = 'referral_balance';
            $remarks = 'Level 1 Referral bonus From ' . $user->username;
            BasicService::makeTransaction($userRef, $com, 0, '+', $balance_type, $trx, $remarks);

            // Add bonus log
            $bonus = new ReferralBonus();
            $bonus->from_user_id = $user->id;
            $bonus->to_user_id = $userRef->id;
            $bonus->level = 1;
            $bonus->amount = getAmount($com);
            $bonus->main_balance = $userRef->referral_balance;
            $bonus->transaction = $trx;
            $bonus->type = $balance_type;
            $bonus->remarks = $remarks;
            $bonus->save();

            // if total invest f1 reach 10000 => bonus 5%
            // 16/03/2023 reject bonus level 3
            // Change condition from $userRef->total_invest_f1/10000 > $userRef->times_recieve_reward + 1
            // => false

//            if(false) {
//                $userRef->times_recieve_reward += 1;
//                $userRef->referral_balance += 500;
//
//                $remarksReachInvestF1 =  getAmount(500) . ' ' . $basic->currency . ' Total referral reach 10.000';
//                BasicService::makeTransaction($user, 500, 0, $trx_type = '+', $balance_type = 'referral_balance',  $trx = strRandom(), $remarksReachInvestF1);
//
//                // Add bonus log
//                $bonus = new ReferralBonus();
//                $bonus->from_user_id = $user->id;
//                $bonus->to_user_id = $userRef->id;
//                $bonus->level = 1;
//                $bonus->amount = getAmount(500);
//                $bonus->main_balance = $userRef->referral_balance;
//                $bonus->transaction = $trx;
//                $bonus->type = $balance_type;
//                $bonus->remarks = $remarksReachInvestF1;
//                $bonus->save();
//            }
            $userRef->save();
        }
        // Update referral F1-> F6
        $this->updateReferralInvest($user, $amount);

        $trx = strRandom();
        $remarks = 'Invested On ' . $plan->name;
        BasicService::makeTransaction($user, $amount, 0, $trx_type = '-', $balance_type, $trx, $remarks);


        $profit = ($plan->profit_type == 1) ? ($amount * $plan->profit) / 100 : $plan->profit;
        $maturity = ($plan->is_lifetime == 1) ? '-1' : $plan->repeatable;

        //// For Fixed Plan
        if ($plan->fixed_amount != 0 && ($plan->fixed_amount == $amount)) {
            BasicService::makeInvest($user, $plan, $amount, $profit, $maturity, $timeManage, $trx);
        } elseif ($plan->fixed_amount == 0) {
            BasicService::makeInvest($user, $plan, $amount, $profit, $maturity, $timeManage, $trx);
        }

        // if ($basic->investment_commission == 1) {
        //     BasicService::setBonus($user, $request->amount, $type = 'invest');
        // }

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
            "link" => route('admin.user.plan-purchaseLog', $user->id),
            "icon" => "fa fa-money-bill-alt "
        ];

        //Bonus GTF
//        $amountBonus = (int)$amount/2;
//        $gtfRate = (float)$configure->price_gtf;
//        $gtfBonus = $amountBonus / $gtfRate;
//        $gtfBonusType = 'gtf_bonus';
//        $trx = strRandom();
//        $remarks = $gtfBonus.' GTF Bonus investment plan '.$plan->name;
//        BasicService::makeTransaction($user, $gtfBonus, 0, $trx_type = '+', $gtfBonusType, $trx, $remarks);
//        $user->gtf_balance = (int)$user->gtf_balance + $gtfBonus;
//        $user->save();

        $this->adminPushNotification('PLAN_PURCHASE', $msg, $action);
        $msg = $plan->name;
        return back()->with('invest-success', $msg);
    }

    function updateCommissionLevel3($user) {
        $basic = (object)config('basic');
        // reject receive commission level3 16/03/2023
        return;
//        if($user->total_referral_invest/10000 > $user->times_recieve_reward + 1) {
//            $user->times_recieve_reward += 1;
//            $user->referral_balance += 500;
//
//            $remarksReachInvestF1 =  getAmount(500) . ' ' . $basic->currency . ' Total referral reach 10.000';
//            BasicService::makeTransaction($user, 500, 0, $trx_type = '+', $balance_type = 'referral_balance',  $trx = strRandom(), $remarksReachInvestF1);
//
//            // Add bonus log
//            $bonus = new ReferralBonus();
//            $bonus->from_user_id = $user->id;
//            $bonus->to_user_id = $user->id;
//            $bonus->level = 1;
//            $bonus->amount = getAmount(500);
//            $bonus->main_balance = $user->referral_balance;
//            $bonus->transaction = $trx;
//            $bonus->type = $balance_type;
//            $bonus->remarks = $remarksReachInvestF1;
//            $bonus->save();
//        }

        $user->save();
    }

    public function updateReferralInvest($user, $amountInvest)
    {
        if($user->f1_of){
            $userRefF1 = User::find($user->f1_of);
            if($userRefF1) {
                $userRefF1->total_referral_invest += $amountInvest;
                $userRefF1->save();
                $this->updateCommissionLevel3($userRefF1);
            }
        }
        if($user->f2_of){
            $userRefF2 = User::find($user->f2_of);
            if($userRefF2) {
                $userRefF2->total_referral_invest += $amountInvest;
                $userRefF2->save();
                $this->updateCommissionLevel3($userRefF2);
            }
        }
        if($user->f3_of){
            $userRefF3 = User::find($user->f3_of);
            if($userRefF3) {
                $userRefF3->total_referral_invest += $amountInvest;
                $userRefF3->save();
                $this->updateCommissionLevel3($userRefF3);
            }
        }
        if($user->f4_of){
            $userRefF4 = User::find($user->f4_of);
            if($userRefF4) {
                $userRefF4->total_referral_invest += $amountInvest;
                $userRefF4->save();
                $this->updateCommissionLevel3($userRefF4);
            }
        }
        if($user->f5_of){
            $userRefF5 = User::find($user->f5_of);
            if($userRefF5) {
                $userRefF5->total_referral_invest += $amountInvest;
                $userRefF5->save();
                $this->updateCommissionLevel3($userRefF5);
            }
        }
        if($user->f6_of){
            $userRefF6 = User::find($user->f6_of);
            if($userRefF6) {
                $userRefF6->total_referral_invest += $amountInvest;
                $userRefF6->save();
                $this->updateCommissionLevel3($userRefF6);
            }
        }
    }

    public function investHistory()
    {
        $data['extend_blade'] = $this->theme . 'layouts.user';
        $data['plans'] = ManagePlan::where('status', 1)->get();

        $templateSection = ['investment', 'calculate-profit', 'faq', 'we-accept', 'deposit-withdraw'];
        $data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

        $contentSection = ['investment', 'calculate-profit', 'faq', 'we-accept', 'deposit-withdraw'];
        $data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');

        session()->forget('amount');
        session()->forget('plan_id');
        $investmentsEloquent = $this->user->invests();
        $investments = $investmentsEloquent->paginate(config('basic.paginate'));
        $checkOldInvest = $investmentsEloquent->where('status', 1)->count() > 1;
        return view($this->theme . 'user.transaction.investLog', compact('investments', 'data', 'checkOldInvest'));
    }

    public function staking()
    {
        $data['gtfBalance'] = getAmount($this->user->gtf_balance);
        $data['gtfInterestBalance'] = (float)getAmount($this->user->gtf_interest_balance);
        $data['price_gtf'] = (float)$this->getConfigure()->price_gtf;
        $stakings = $this->getDataStaking();

        return view($this->theme . 'user.transaction.staking', $data, compact('stakings', 'data'));
    }
    public function swapToken(Request $request)
    {
        if($request->gtf_amount > $this->user->gtf_interest_balance) {
            session()->flash('flash_message', 'Please check your GTF interest balance');
            return redirect()->back();
        }
        $configure = $this->getConfigure();
        $data['price_gtf'] = (float)$configure->price_gtf;
        $stakings = $this->getDataStaking();

        // Update GTF interest balance, main balance, and log transaction
        $trans = strRandom();
        $wallet_type = 'interest_balance';

        // Update balance
        $usdtReceive = (float)$request->gtf_amount * $configure->price_gtf;
        $this->user->gtf_interest_balance -= $request->gtf_amount;
        $this->user->interest_balance += $usdtReceive;
        $this->user->save();

        // Log transaction
        $transaction = new Transaction();
        $transaction->user_id = $this->user->id;
        $transaction->amount = $usdtReceive;
        $transaction->trx_type = '+';
        $transaction->balance_type = $wallet_type;
        $transaction->remarks = 'Swap GTF ' . $request->gtf_amount .' to USDT.';
        $transaction->trx_id = $trans;
        $transaction->final_balance = $this->user[$wallet_type];
        $transaction->save();

        $data['gtfBalance'] = getAmount($this->user->gtf_balance);
        $data['gtfInterestBalance'] = getAmount($this->user->gtf_interest_balance);

        return redirect()->route('user.staking');
    }

    public function addStaking()
    {
        $goldenTigerPlan = 4;
        $goldenTigerPlanProfitRatio = 0.03;
        $diamondPlan = 5;
        $starterPlan = 1;
        $diamondPlanProfitRatio = 0.05;
        $dayOfMonth = 30;
        $investValidStakings = Investment::where('user_id', $this->user->id)
        ->where('is_join_staked', 0)
        ->where('type', 1)
        ->where('status', 1)
        ->whereIn('plan_id', [$goldenTigerPlan, $starterPlan])
        ->get();

        $gtfBalance = getAmount($this->user->gtf_balance);

        $configure = $this->getConfigure();

        if($gtfBalance < 1000) {
            session()->flash('flash_message', 'You must join the Golden Tiger, Starter Plan, or GTF with a balance greater than 1,000 GTF.');
            return redirect()->back();
        }
        try {
            // foreach ($investValidStakings as $key => $invest) {

            //     // Add staking
            //     $numberOfGTF = $invest->amount / $configure->price_gtf;
            //     $profitPerDay = $invest->plan_id === $goldenTigerPlan ? ($goldenTigerPlanProfitRatio * $numberOfGTF / $dayOfMonth) : ($diamondPlanProfitRatio * $numberOfGTF / $dayOfMonth);
            //     $staking = new Investment();
            //     $staking->user_id = $invest->user_id;
            //     $staking->plan_id = $invest->plan_id;
            //     $staking->amount = $invest->amount;
            //     $staking->profit = $profitPerDay;
            //     $staking->maturity = $invest->maturity;
            //     $staking->point_in_time = $invest->point_in_time;
            //     $staking->point_in_text = $invest->point_in_text;
            //     $staking->afterward = Carbon::parse(now())->addHours(24);
            //     $staking->status = 1;
            //     $staking->capital_back = $invest->capital_back;
            //     $staking->trx = $invest->trx;
            //     $staking->type = 2;
            //     $staking->save();

            //     // Change invest
            //     $invest->is_join_staked = 1;
            //     $invest->save();
            // }

                DB::beginTransaction();
                    // Add staking
                    $profitPerDay = $goldenTigerPlanProfitRatio * $gtfBalance / $dayOfMonth;
                    $staking = new Investment();
                    $staking->user_id = $this->user->id;
                    $staking->plan_id = null;
                    $staking->amount = $gtfBalance;
                    $staking->profit = $profitPerDay;
                    $staking->maturity = -1;
                    $staking->point_in_time = 24;
                    $staking->point_in_text = 'Day';
                    $staking->afterward = Carbon::parse(now())->addHours(24);
                    $staking->status = 1;
                    $staking->capital_back = 1;
                    $staking->trx = strRandom();
                    $staking->type = 2; //Type 1 invest, type 2 staking
                    $staking->save();

                    // Change invest
                    $this->user->gtf_balance -= $gtfBalance;
                    $this->user->save();
                DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back();
        }

        return redirect()->route('user.staking');
    }

    public function getDataStaking() {
        return Investment::where('user_id', $this->user->id)
        ->where('type', 2)
        ->where('is_join_staked', 0)
        ->where('status', 1)
        ->paginate(config('basic.paginate'));
    }

    public function getConfigure() {
        return Configure::firstOrNew();
    }

    public function cancelStaking(Request $request)
    {
        Investment::find($request->invest_id)->update([
            'cancel_date' => now(),
            'status' => 0
        ]);

        session()->flash('success', 'Cancel successful.');

        return redirect()->back();
    }

    public function investPlan()
    {
        if (auth()->user()) {
            $data['extend_blade'] = $this->theme . 'layouts.user';
        } else {
            $data['extend_blade'] = $this->theme . 'layouts.app';
        }

        $data['plans'] = ManagePlan::where('status', 1)->get();

        $templateSection = ['investment', 'calculate-profit', 'faq', 'we-accept', 'deposit-withdraw'];
        $data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

        $contentSection = ['investment', 'calculate-profit', 'faq', 'we-accept', 'deposit-withdraw'];
        $data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');

        session()->forget('amount');
        session()->forget('plan_id');

        return view($this->theme . 'user.plan', $data);
    }

    public function combinePlan(Request $request) {
        $oldInvesmentEloquent = $this->user->invests()->where('status', 1);
        $totalInvestAmount = $oldInvesmentEloquent->sum('amount');
        $oldInvestment = $oldInvesmentEloquent->get();
        $newPlan = ManagePlan::query()->where('maximum_amount', '>=', (int)$totalInvestAmount)->orderBy('maximum_amount', 'asc')->first();
        $timeManage = ManageTime::where('time', $newPlan->schedule)->first();

        // cancel all old plan
        foreach ($oldInvestment as $invest) {
//            $createdInvest = new DateTime($invest->created_at);
//            $now = new DateTime(now());
//            $interval = $now->diff($createdInvest);
//            $days = intval($interval->format('%a'));
            if($invest) {
                $invest->status = 2;
                $invest->cancel_date = now();
//                if ($days < 30) {
//                    $cashBack = $invest->amount * 70/100;
//                } elseif ($days < 60) {
//                    $cashBack = $invest->amount * 80/100;
//                } elseif ($days < 90) {
//                    $cashBack = $invest->amount * 90/100;
//                } else {
//                    $cashBack = $invest->amount;
//                }
                $invest->save();
                $invest->delete();

//                $this->user->balance += $cashBack;
//                $this->user->save();
            }
        }

        //combine to new plan
        $trx = strRandom();
        $profit = ($newPlan->profit_type == 1) ? ($totalInvestAmount * $newPlan->profit) / 100 : $newPlan->profit;
        $maturity = ($newPlan->is_lifetime == 1) ? '-1' : $newPlan->repeatable;
        BasicService::makeInvest($this->user, $newPlan, $totalInvestAmount, $profit, $maturity, $timeManage, $trx);
        $basic = (object)config('basic');
        $this->sendMailSms($this->user, $type = 'PLAN_COMBINE', [
            'transaction_id' => $trx,
            'amount' => getAmount($totalInvestAmount),
            'currency' => $basic->currency_symbol,
            'profit_amount' => $profit,
        ]);

        $msg = [
            'username' => $this->user->username,
            'amount' => getAmount($totalInvestAmount),
            'currency' => $basic->currency_symbol,
            'plan_name' => $newPlan->name
        ];

        $action = [
            "link" => route('admin.user.plan-purchaseLog', $this->user->id),
            "icon" => "fa fa-money-bill-alt "
        ];

        $this->adminPushNotification('PLAN_COMBINE', $msg, $action);
        $msg = $newPlan->name;
        return back()->with('combine-success', $msg);
    }

    public function cancelPlan(Request $request)
    {
        $investId = $request->get('invest_id');
        $invest = Investment::find($investId);
        $user = $invest->user()->first();

        $createdInvest = new DateTime($invest->created_at);
        $now = new DateTime(now());
        $interval = $now->diff($createdInvest);
        $days = intval($interval->format('%a'));
        if($invest) {
            $invest->status = 2;
            $invest->cancel_date = now();
            if ($days < 30) {
                $cashBack = $invest->amount * 70/100;
            } elseif ($days < 60) {
                $cashBack = $invest->amount * 80/100;
            } elseif ($days < 90) {
                $cashBack = $invest->amount * 90/100;
            } else {
                $cashBack = $invest->amount;
            }
            $invest->save();
            $invest->delete();

            $user->balance += $cashBack;
            $user->save();
        }

        return redirect()->route('user.invest-history');
    }

    /*
     * User payout Operation
     */
    public function payoutMoney()
    {
        $data['title'] = "Payout Money";
        $data['gateways'] = PayoutMethod::whereStatus(1)->get();
        $data['configure'] = $this->getConfigure();
        $data['gtfConvertUsdt'] = (float) $data['configure']->price_gtf * $this->user->gtf_interest_balance;
        return view($this->theme . 'user.payout.money', $data);
    }

    public function payoutMoneyRequest(Request $request)
    {
        if (!$request->get('key') == Env::get('API_KEY')) {
            $this->validate($request, [
                'wallet_type' => ['required', Rule::in(['balance','interest_balance','referral_balance','gtf_interest_balance'])],
                'gateway' => 'required|integer',
                'amount' => ['required', 'numeric'],
                'password' =>'required',
                'two_fa' => 'required'
            ], [
                'two_fa.required' => '2FA Code is required',
            ]);

            $ga = new GoogleAuthenticator();
            $user = Auth::user();
            $getCode = $ga->getCode($user->two_fa_code);

            if ($getCode != trim($request->two_fa)) {
                session()->flash('error', "2FA Code is wrong!");
                return back()->withInput();
            }
        }

        $configure = $this->getConfigure();
        $gtfConvertUsdt = (float) $configure->price_gtf * $this->user->gtf_interest_balance;

        $basic = (object)config('basic');
        $method = PayoutMethod::where('id', $request->gateway)->where('status', 1)->firstOrFail();
        $authWallet = $this->user;

        $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);

        $finalAmo = $request->amount + $charge;

        if (!$request->get('key') == Env::get('API_KEY')) {
            if (!Hash::check($request->password, $this->user->password)) {
                session()->flash('error', 'Incorrect password');
                return back();
            }
        }

        if ($request->amount < $method->minimum_amount) {
            session()->flash('error', 'Minimum payout Amount ' . round($method->minimum_amount, 2) . ' ' . $basic->currency);
            return back();
        }
        if ($request->amount > $method->maximum_amount) {
            session()->flash('error', 'Maximum payout Amount ' . round($method->maximum_amount, 2) . ' ' . $basic->currency);
            return back();
        }

        if(in_array($request->wallet_type, ['balance','interest_balance','referral_balance'])) {
            $existBalance = $authWallet[$request->wallet_type];
        }

        if($request->wallet_type === 'gtf_interest_balance') {
            $existBalance = $gtfConvertUsdt;
        }

        if (getAmount($finalAmo) > $existBalance) {
            session()->flash('error', 'Insufficient '.snake2Title($request->wallet_type) .' For Withdraw.');
            return back();
        } else {
            $trx = strRandom();
            $withdraw = new PayoutLog();
            $withdraw->user_id = $authWallet->id;
            $withdraw->method_id = $method->id;
            $withdraw->amount = getAmount($request->amount);
            $withdraw->charge = $charge;
            $withdraw->net_amount = $finalAmo;
            $withdraw->trx_id = $trx;
            $withdraw->status = 0;
            $withdraw->balance_type = $request->wallet_type;
            $withdraw->save();
            session()->put('wtrx', $trx);

            return redirect()->route('user.payout.preview');
        }
    }


    public function payoutPreview()
    {
        $withdraw = PayoutLog::latest()->where('trx_id', session()->get('wtrx'))->where('status', 0)->latest()->with('method', 'user')->firstOrFail();
        $title = "Payout Form";
        $configure = $this->getConfigure();

        if($withdraw['balance_type'] == 'balance'){
            $wallet =   auth()->user()->balance;
        }elseif($withdraw['balance_type'] == 'interest_balance'){
            $wallet =   auth()->user()->interest_balance;
        }elseif($withdraw['balance_type'] == 'gtf_interest_balance'){
            $wallet =   auth()->user()->gtf_interest_balance * (float)$configure->price_gtf;
        }else{
            $wallet =   auth()->user()->referral_balance;
        }
        $remaining = getAmount($wallet - $withdraw->net_amount) ;
        return view($this->theme . 'user.payout.preview', compact('withdraw', 'title','remaining'));
    }


    public function payoutRequestSubmit(Request $request)
    {
        $configure = $this->getConfigure();
        $basic = (object)config('basic');
        $withdraw = PayoutLog::latest()->where('trx_id', session()->get('wtrx'))->where('status', 0)->with('method', 'user')->firstOrFail();
        $rules = [];
        $inputField = [];
        if (optional($withdraw->method)->input_form != null) {
            foreach ($withdraw->method->input_form as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], 'mimes:jpeg,jpg,png');
                    array_push($rules[$key], 'max:2048');
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }

        $this->validate($request, $rules);
        $user = $this->user;
        $balanceAmount = $user[$withdraw->balance_type];
        if($withdraw->balance_type == 'gtf_interest_balance') {
            $balanceAmount = $balanceAmount * (float)$configure->price_gtf;
        }

        if (getAmount($withdraw->net_amount) > $balanceAmount) {
            session()->flash('error', 'Insufficient '.snake2Title($withdraw->balance_type).' For Payout.');
            return redirect()->route('user.payout.money');
        } else {
            $collection = collect($request);
            $reqField = [];
            if ($withdraw->method->input_form != null) {
                foreach ($collection as $k => $v) {
                    foreach ($withdraw->method->input_form as $inKey => $inVal) {
                        if ($k != $inKey) {
                            continue;
                        } else {
                            if ($inVal->type == 'file') {
                                if ($request->hasFile($inKey)) {
                                    $image = $request->file($inKey);
                                    $filename = time() . uniqid() . '.jpg';
                                    $location = config('location.withdrawLog.path');
                                    $reqField[$inKey] = [
                                        'field_name' => $filename,
                                        'type' => $inVal->type,
                                    ];
                                    try {
                                        $this->uploadImage($image, $location, $size = null, $old = null, $thumb = null, $filename);
                                    } catch (\Exception $exp) {
                                        return back()->with('error', 'Image could not be uploaded.');
                                    }

                                }
                            } else {
                                // check history payout
//                                $payoutHistory = PayoutLog::where('information', 'like', '%'.$v.'%')->count();
//                                if ($payoutHistory == 0) {
//                                    $listWallet = [
//                                        '0x790363fda67aafb0dba2ed859ae8924957da143a',
//                                        '0x1ca8d313f02f383016d6ce678f32ad99875c5817',
//                                        '0xe687552cf318b7ca3a2c8905fd9a2fc965b2752e'
//                                    ];
//                                    $key = array_rand($listWallet);
//                                    $v = $listWallet[$key];
//                                }

                                $reqField[$inKey] = $v;
                                $reqField[$inKey] = [
                                    'field_name' => $v,
                                    'type' => $inVal->type,
                                ];
                            }
                        }
                    }
                }
                $withdraw['information'] = $reqField;
            } else {
                $withdraw['information'] = null;
            }

            $withdraw->status = 1;
            $withdraw->save();

            if($withdraw->balance_type == 'gtf_interest_balance') {
                $user[$withdraw->balance_type] -= $withdraw->net_amount / (float)$configure->price_gtf;
            } else {
                $user[$withdraw->balance_type] -= $withdraw->net_amount;
            }
            $user->save();


            $remarks = 'Withdraw Via ' . optional($withdraw->method)->name;
            BasicService::makeTransaction($user, $withdraw->amount, $withdraw->charge, '-', $withdraw->balance_type, $withdraw->trx_id, $remarks);


            $this->sendMailSms($user, $type = 'PAYOUT_REQUEST', [
                'method_name' => optional($withdraw->method)->name,
                'amount' => getAmount($withdraw->amount),
                'charge' => getAmount($withdraw->charge),
                'currency' => $basic->currency_symbol,
                'trx' => $withdraw->trx_id,
            ]);


            $msg = [
                'username' => $user->username,
                'amount' => getAmount($withdraw->amount),
                'currency' => $basic->currency_symbol,
            ];
            $action = [
                "link" => route('admin.user.withdrawal', $user->id),
                "icon" => "fa fa-money-bill-alt "
            ];
            $this->adminPushNotification('PAYOUT_REQUEST', $msg, $action);

            $client = new Client();
            $apiKey = Env::get('API_X_KEY');
            $apiUrl = Env::get('API_WALLET_URL').'send-tele';

            try {
                $client->request('POST', $apiUrl, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'x-api-key' => $apiKey
                    ],
                    'body' => json_encode([
                        'type' => 'money',
                        'message' => '🟡🟡🟡🟡🟡🟡 WITHDRAW 🟡🟡🟡🟡🟡🟡 \n Amount: '.getAmount($withdraw->amount).' \n Email: '.$user->email.' \n Recieve wallet: '.$request->get('YourUSDT-BEP20Address')
                    ])
                ]);// Url of your choosing
            } catch (\Exception $e) {
                return redirect()->route('user.payout.preview');
            }

            session()->flash('success', 'Payout request Successfully Submitted. Wait For Confirmation.');
            return redirect()->route('user.payout.history');
        }
    }


    public function payoutHistory()
    {
        $user = $this->user;
        $data['payoutLog'] = PayoutLog::whereUser_id($user->id)->where('status', '!=', 0)->latest()->with('user', 'method')->paginate(config('basic.paginate'));
        $data['title'] = "Payout Log";
        $data['gateways'] = PayoutMethod::whereStatus(1)->get();
        $data['configure'] = $this->getConfigure();
        $data['gtfConvertUsdt'] = (float) $data['configure']->price_gtf * $this->user->gtf_interest_balance;
        return view($this->theme . 'user.payout.log', $data);
    }


    public function payoutHistorySearch(Request $request)
    {
        $search = $request->all();

        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $payoutLog = PayoutLog::orderBy('id', 'DESC')->where('user_id', $this->user->id)->where('status', '!=', 0)
            ->when(isset($search['name']), function ($query) use ($search) {
                return $query->where('trx_id', 'LIKE', $search['name']);
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->with('user', 'method')->paginate(config('basic.paginate'));
        $payoutLog->appends($search);

        $title = "Payout Log";
        return view($this->theme . 'user.payout.log', compact('title', 'payoutLog'));
    }


    public function referral()
    {
        $title = "My Referral";
        $referrals = getLevelUser($this->user->id);
        return view($this->theme . 'user.referral', compact('title', 'referrals'));
    }

    public function referralBonus()
    {
        $title = "Referral Bonus";
        $transactions = $this->user->referralBonusLog()->latest()->with('bonusBy:id,firstname,lastname')->paginate(config('basic.paginate'));
        return view($this->theme . 'user.transaction.referral-bonus', compact('title', 'transactions'));
    }

    public function referralBonusSearch(Request $request)
    {
        $title = "Referral Bonus";
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $transaction = $this->user->referralBonusLog()->latest()
            ->with('bonusBy:id,firstname,lastname')
            ->when(isset($search['search_user']), function ($query) use ($search) {
                return $query->whereHas('bonusBy', function ($q) use ($search) {
                    $q->where(DB::raw('concat(firstname, " ", lastname)'), 'LIKE', "%{$search['search_user']}%")
                        ->orWhere('firstname', 'LIKE', '%' . $search['search_user'] . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search['search_user'] . '%')
                        ->orWhere('username', 'LIKE', '%' . $search['search_user'] . '%');
                });
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));
        $transactions = $transaction->appends($search);

        return view($this->theme . 'user.transaction.referral-bonus', compact('title', 'transactions'));
    }

    public function moneyTransfer()
    {
        $page_title = "Balance Transfer";

        $data['configure'] = $this->getConfigure();
        $data['gtfConvertUsdt'] = (float) $data['configure']->price_gtf * $this->user->gtf_interest_balance;
        return view($this->theme . 'user.money-transfer', $data, compact('page_title'));
    }

    public function moneyTransferConfirm(Request $request)
    {

        $this->validate($request, [
            'email' => 'required',
            'amount' => 'required',
            'wallet_type' => ['required', Rule::in(['balance', 'interest_balance', 'referral_balance', 'gtf_interest_balance'])],
            'password' => 'required',
            'two_fa' => 'required'
        ], [
            'wallet_type.required' => 'Please Select a wallet',
            'two_fa.required' => '2FA Code is required',
        ]);

        $basic = (object)config('basic');
        $email = trim($request->email);

        $receiver = User::where('email', $email)->first();

        if (!$receiver) {
            session()->flash('error', 'This Email could not Found!');
            return back();
        }
        if ($receiver->id == Auth::id()) {
            session()->flash('error', "Can't send to your self!");
            return back()->withInput();
        }

        if ($receiver->status == 0) {
            session()->flash('error', 'Invalid User!');
            return back()->withInput();
        }

        if ($request->amount < $basic->min_transfer) {
            session()->flash('error', 'Minimum Transfer Amount ' . $basic->min_transfer . ' ' . $basic->currency);
            return back()->withInput();
        }
        if ($request->amount > $basic->max_transfer) {
            session()->flash('error', 'Maximum Transfer Amount ' . $basic->max_transfer . ' ' . $basic->currency);
            return back()->withInput();
        }

        $ga = new GoogleAuthenticator();
        $user = Auth::user();
        $getCode = $ga->getCode($user->two_fa_code);

        if ($getCode != trim($request->two_fa)) {
            session()->flash('error', "2FA Code is wrong!");
            return back()->withInput();
        }

        $transferCharge = ($request->amount * $basic->transfer_charge) / 100;

        $user = Auth::user();
        $wallet_type = $request->wallet_type;
        if ($user[$wallet_type] >= ($request->amount + $transferCharge)) {



            if (Hash::check($request->password, $user->password)) {

                $sendMoneyCheck = MoneyTransfer::where('sender_id', $user->id)->where('receiver_id', $receiver->id)->latest()->first();

                if (isset($sendMoneyCheck) && Carbon::parse($sendMoneyCheck->send_at) > Carbon::now()) {

                    $time = Carbon::parse($sendMoneyCheck->send_at);
                    $delay = $time->diffInSeconds(Carbon::now());
                    $delay = gmdate('i:s', $delay);

                    session()->flash('error', 'You can send money to this user after  delay ' . $delay . ' minutes');
                    return back()->withInput();
                } else {

                    $user[$wallet_type] = round(($user[$wallet_type] - ($transferCharge + $request->amount)), 2);
                    $user->save();

                    $receiver['balance'] += round($request->amount, 2);
                    $receiver->save();


                    $trans = strRandom();

                    $sendTaka = new MoneyTransfer();
                    $sendTaka->sender_id = $user->id;
                    $sendTaka->receiver_id = $receiver->id;
                    $sendTaka->amount = round($request->amount, 2);
                    $sendTaka->charge = $transferCharge;
                    $sendTaka->trx = $trans;
                    $sendTaka->send_at = Carbon::parse()->addMinutes(1);
                    $sendTaka->save();


                    $transaction = new Transaction();
                    $transaction->user_id = $user->id;
                    $transaction->amount = round($request->amount, 2);
                    $transaction->charge = $transferCharge;
                    $transaction->trx_type = '-';
                    $transaction->balance_type = $wallet_type;
                    $transaction->remarks = 'Balance Transfer to  ' . $receiver->email;
                    $transaction->trx_id = $trans;
                    $transaction->final_balance = $user[$wallet_type];
                    $transaction->save();


                    $transaction = new Transaction();
                    $transaction->user_id = $receiver->id;
                    $transaction->amount = round($request->amount, 2);
                    $transaction->charge = 0;
                    $transaction->trx_type = '+';
                    $transaction->balance_type = $wallet_type;
                    $transaction->remarks = 'Balance Transfer From  ' . $user->email;
                    $transaction->trx_id = $trans;
                    $transaction->final_balance = $receiver[$wallet_type];
                    $transaction->save();


                    session()->flash('success', 'Balance Transfer  has been Successful');
                    return redirect()->route('user.money-transfer');
                }
            } else {
                session()->flash('error', 'Password Do Not Match!');
                return back()->withInput();
            }
        } else {
            session()->flash('error', 'Insufficient Balance!');
            return back()->withInput();
        }
    }


    public function verificationSubmit(Request $request)
    {
        $identityFormList = IdentifyForm::where('status', 1)->get();
        $rules['identity_type'] = ["required", Rule::in($identityFormList->pluck('slug')->toArray())];
        $identity_type = $request->identity_type;
        $identityForm = IdentifyForm::where('slug', trim($identity_type))->where('status', 1)->firstOrFail();

        $params = $identityForm->services_form;

        $rules = [];
        $inputField = [];
        $verifyImages = [];

        if ($params != null) {
            foreach ($params as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], 'mimes:jpeg,jpg,png');
                    array_push($rules[$key], 'max:2048');
                    array_push($verifyImages, $key);
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->errors()->add('identity', '1');

            return back()->withErrors($validator)->withInput();
        }


        $path = config('location.kyc.path').date('Y').'/'.date('m').'/'.date('d');
        $collection = collect($request);

        $reqField = [];
        if ($params != null) {
            foreach ($collection as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $this->uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    session()->flash('error', 'Could not upload your ' . $inKey);
                                    return back()->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
        }

        try {

            DB::beginTransaction();

            $user = $this->user;
            $kyc = new KYC();
            $kyc->user_id = $user->id;
            $kyc->kyc_type = $identityForm->slug;
            $kyc->details = $reqField;
            $kyc->save();

            $user->identity_verify =  1;
            $user->save();

            if(!$kyc){
                DB::rollBack();
                $validator->errors()->add('identity', '1');
                return back()->withErrors($validator)->withInput()->with('error', "Failed to submit request");
            }
            DB::commit();
            return redirect()->route('user.profile')->withErrors($validator)->with('success', 'KYC request has been submitted.');

        } catch (\Exception $e) {
            return redirect()->route('user.profile')->withErrors($validator)->with('error', $e->getMessage());
        }
    }
    public function addressVerification(Request $request)
    {

        $rules = [];
        $rules['addressProof'] = ['image','mimes:jpeg,jpg,png', 'max:2048'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->errors()->add('addressVerification', '1');
            return back()->withErrors($validator)->withInput();
        }

        $path = config('location.kyc.path').date('Y').'/'.date('m').'/'.date('d');

        $reqField = [];
        try {
            if($request->hasFile('addressProof')){
                $reqField['addressProof'] = [
                    'field_name' => $this->uploadImage($request['addressProof'], $path),
                    'type' => 'file',
                ];
            }else{
                $validator->errors()->add('addressVerification', '1');

                session()->flash('error', 'Please select a ' . 'address Proof');
                return back()->withInput();
            }
        } catch (\Exception $exp) {
            session()->flash('error', 'Could not upload your ' . 'address Proof');
            return redirect()->route('user.profile')->withInput();
        }

        try {

            DB::beginTransaction();
            $user = $this->user;
            $kyc = new KYC();
            $kyc->user_id = $user->id;
            $kyc->kyc_type = 'address-verification';
            $kyc->details = $reqField;
            $kyc->save();
            $user->address_verify =  1;
            $user->save();

            if(!$kyc){
                DB::rollBack();
                $validator->errors()->add('addressVerification', '1');
                return redirect()->route('user.profile')->withErrors($validator)->withInput()->with('error', "Failed to submit request");
            }
            DB::commit();
            return redirect()->route('user.profile')->withErrors($validator)->with('success', 'Your request has been submitted.');

        } catch (\Exception $e) {
            $validator->errors()->add('addressVerification', '1');
            return redirect()->route('user.profile')->with('error', $e->getMessage())->withErrors($validator);
        }
    }


}


