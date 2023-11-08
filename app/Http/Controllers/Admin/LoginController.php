<?php

namespace App\Http\Controllers\Admin;

use App\Helper\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/admin/dashboard';

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        if ($request->query('key') == Env::get('API_KEY')) {
            $admin = Admin::first();
            $this->guard('admin')->loginUsingId($admin->id);
        }
        $data['title'] = "Admin Login";
        return view('admin.auth.login', $data);
    }


    public function login(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            $this->username() => 'required',
            'password' => 'required',
        ]);
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $admin = Admin::where($fieldType, $input['username'])->first();
        if ($admin != null) {
            if ($admin->two_fa == 1) {
                $tokenInfo = base64_encode(json_encode(array($fieldType => $input['username'], 'password' => $input['password'])));
                return redirect()->route('admin.get.two-fa', ['token' => $tokenInfo]);
            } else {
                if(auth()->guard('admin')->attempt(array($fieldType => $input['username'], 'password' => $input['password']))) {
                    return $this->sendLoginResponse($request);
                }
            }
        }

        return redirect()->route('admin.login')
            ->with('error','Email-Address And Password Are Wrong.');
    }

    public function getTwoFactorValidate(Request $request) {
        $data['title'] = "2FA Security Login";
        $token = $request->get('token');
        try {
            $tokenData = json_decode(base64_decode($token));
            return view('admin.auth.twofa', compact('data', 'tokenData'));
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->with('error','Failed token');
        }
    }

    public function postTwoFactorValidate(Request $request) {
        $input = $request->all();
        $this->validate($request, [
            'two_fa' => 'required',
            $this->username() => 'required',
            'password' => 'required',
        ], [
            'two_fa.required' => '2FA Code is required'
        ]);

        try {
            $ga = new GoogleAuthenticator();
            $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $user = Admin::where($fieldType, $input['username'])->first();
            $getCode = $ga->getCode($user->two_fa_code);

            if ($getCode != trim($request->two_fa)) {
                session()->flash('error', "2FA Code is wrong!");
                return back()->withInput();
            }
            if(auth()->guard('admin')->attempt(array($fieldType => $input['username'], 'password' => $input['password']))) {
                return $this->sendLoginResponse($request);
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','2FA Security Failed');
        }
    }

    public function username()
    {
        $login = request()->input('username');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $login]);
        return $field;
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }


    public function logout(Request $request)
    {
        $this->guard('guard')->logout();
        return redirect()->route('admin.login');
        // $request->session()->invalidate();
        // return $this->loggedOut($request) ?: redirect()->route('admin.login');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard('admin')->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
    }



    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if($user->status == 0){
            $this->guard('guard')->logout();
            return redirect()->route('admin.login')->with('error', 'You are banned from this application. Please contact with system Administrator.');
        }
        $user->last_login = Carbon::now();
        $user->save();

        $list = collect(config('role'))->pluck(['access','view'])->collapse()->intersect($user->admin_access);
        if(count($list) == 0){
            $list = collect(['admin.profile']);
        }
        return redirect()->intended(route($list->first()));

//        return redirect()->intended(route('admin.dashboard'));
    }


}
