<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Facades\App\Services\BasicService;

class Cron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron for investment Status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $basic = (object) config('basic');
        $investments = Investment::whereStatus(1)->where('afterward', '<=', $now)->with(['user:id,firstname,lastname,username,email,phone_code,phone,balance,interest_balance,gtf_interest_balance,f1_of,f2_of,f3_of,f4_of,f5_of,f6_of','plan'])->get();
        
        foreach ($investments as $data) {
            if($data){
                $next_time = Carbon::parse($now)->addHours($data->point_in_time);

                $invest= $data;
                $invest->recurring_time += 1;
                $invest->afterward = $next_time; // next Profit will get
                $invest->formerly = $now; // Last Time Get Profit

                // Return Amo   unt to user's Interest Balance

                $isUserStakingType = $data->type == 2 ? true : false; 
                $user = $data->user;
                $balance_type = 'interest_balance';

                if($isUserStakingType) {
                    $new_balance = getAmount($user->gtf_interest_balance + $data->profit);
                    $user->gtf_interest_balance = $new_balance;
                    $remarks =  getAmount($data->profit) . ' GTF Interest From Staking';
                    $balance_type = 'gtf_interest_balance';
                } else {
                    $new_balance = getAmount($user->interest_balance + $data->profit);
                    $user->interest_balance = $new_balance;
                    $remarks =  getAmount($data->profit) . ' ' . $basic->currency . ' Interest From '.optional($invest->plan)->name;
                    $balance_type = 'interest_balance';
                }
                $user->save();

                BasicService::makeTransaction($user, $data->profit, 0, $trx_type = '+', $balance_type,  $trx = strRandom(), $remarks);

                // Payment for commission for referral
                // Check user is invest
                if (BasicService::isUserInvested($user) && !$isUserStakingType) {
                    BasicService::calculateComission($user, $data->profit);
                }

                // Complete the investment if user get full amount as plan
                if ($invest->recurring_time >= $data->maturity && $data->maturity != '-1') {
                    $invest->status = 0; // stop return Back
                    // Give the capital back if plan says the same
                    if ($data->capital_back == 1) {
                        $capital =  $data->amount;
                        $new_balance = getAmount($user->interest_balance + $capital);
                        $user->interest_balance = $new_balance;
                        $user->save();
                        $remarks = getAmount($capital) . ' ' . $basic->currency . ' Capital Back From '.optional($invest->plan)->name;
                        BasicService::makeTransaction($user, getAmount($capital), 0, $trx_type = '+', $balance_type = 'interest_balance',  $trx = strRandom(), $remarks);
                    }
                }
                $invest->status = ($data->period == '-1') ? 1 : $invest->status; // Plan will run Lifetime
                $invest->save();
            }

        }

        $this->info('status');
    }

}


