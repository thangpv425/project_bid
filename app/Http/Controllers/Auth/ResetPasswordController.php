<?php

namespace App\Http\Controllers\Auth;

use App\Hash;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * show reset form
     * @param $userId
     * @param null $token
     * @param null $tokenType
     * @return View
     */
    public function showResetForm($userId, $token = null, $tokenType = null) {

        $hash = $this->getHash($userId,$token, $tokenType);

        if ($hash == null) {
            return '404 not found';
        }

        return view('auth.passwords.reset')->with(
            ['token' => $token,
                'type' => $tokenType,
                'user_id' => $userId,
                ]
        );

    }

    /**
     * Reset password
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function reset(Request $request) {

        $this->validate($request, $this->rules(), $this->validationErrorMessages());


        try {
            //get hash from table
            $hash = $this->getHash(
                $request->input('user_id'),
                $request->input('token'),
                $request->input('type')
            );

            //calculate time expire
            $now = Carbon::now();

            $expire = new Carbon($hash->expire_at);
            if ($now > $expire) {
                return redirect()->back()->with('error', 'Timeout!');
            }

            //get user and change password
            $user = User::where('id','=',$request->input('user_id'))->firstOrFail();
            if ($user->email != $request->input('email')) {
                return redirect()->back()->with('error', 'The email you entered does not match');
            }

            //save user password to database
            $user->forceFill([
                'password' => bcrypt($request->input('password')),
                'remember_token' => Str::random(60),
            ])->save();

            return redirect($this->redirectTo)
                ->with('status', 'Reset password success!');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Access denied');
        }

    }


    /**
     * Get hash from hashs table
     * @param $userId
     * @param $token
     * @param $type
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */

    private function getHash($userId, $token, $type) {

        return Hash::where('user_id', '=', $userId)
            ->where('hash_key', '=', $token)
            ->where('type', '=',$type)
            ->firstOrFail();
    }


}
