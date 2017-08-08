<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class ResetPasswordController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';


    public function __construct() {
        $this->middleware('guest');

    }

    /**
     * show form reset
     * @param $hashKey
     * @return View
     */
    public function showResetForm($hashKey) {

        if ($this->getUserValidHash($hashKey) != null) {
            return view('auth.passwords.reset')->with('hashKey',$hashKey);
        }

        //TODO: make view for valid token!
        return 'Hash key not valid or timeout';
    }

    /**
     * Reset password
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function reset(Request $request) {

        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
            'hash_key' => 'required'
        ]);


        $user = $this->getUserValidHash($request->input('hash_key'));
        if ($user == null) {
            return 'hash key not valid or timeout';
        }

        $user->forceFill([
            'password' => bcrypt($request->input('password')),
            'remember_token' => Str::random(60),
        ])->save();
        return redirect('login')->with('status','Reset password success!');

    }

    /**
     * Get user with valid hash key
     * @param $hashKey
     * @return User
     */
    private function getUserValidHash($hashKey) {
        $users = User::join('hashs', 'hashs.user_id', '=', 'users.id')
            ->where('expire_at', '>=', Carbon::now())
            ->where('type', '=', Config::get('constants.hash_type.forgot_password'))
            ->where('users.status', '=', Config::get('constants.user_status.active'))
            ->where('hash_key', '=', $hashKey)
            ->limit(1)
            ->get();
            return $users->isEmpty()? null : $users[0];
    }
}
