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
     * @param  $token
     * @param  $tokenType
     * @return View
     */
    public function showResetForm($userId, $token, $tokenType) {

        return view('auth.passwords.reset')->with([
            'token' => $token,
            'type' => $tokenType,
            'user_id' => $userId,
        ]);
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
            if ($hash == null) {
                return $this->sendFailResponse('Access denied');
            }

            //validate time expire
            if (!$this->validateTimeExpire($hash)) {
                return $this->sendFailResponse('Access denied!');
            }

            //get user and change password
            $user = User::where('id','=',$request->input('user_id'))->firstOrFail();
            if ($user->email != $request->input('email')) {
                return $this->sendFailResponse('The email you entered does not match');
            }

            //else save user password to database
            $user->forceFill([
                'password' => bcrypt($request->input('password')),
                'remember_token' => Str::random(60),
            ])->save();

            return $this->sendSuccessResponse('Reset password success!');
        } catch (ModelNotFoundException $exception) {
            return $this->sendFailResponse('Access denied');
        }
    }

    /**
     * @param $userId
     * @param $token
     * @param $type
     * @return Hash if existed , null if not existed
     */

    private function getHash($userId, $token, $type) {
        try {
            $hash = Hash::where('user_id', '=', $userId)
                ->where('hash_key', '=', $token)
                ->where('type', '=',$type)
                ->firstOrFail();

            return $hash;
        } catch (ModelNotFoundException $exception) {
            return null;
        }
    }


    /**
     * send fail response when reset password
     * @param $message
     * @return \Illuminate\Http\RedirectResponse
     */
    private function sendFailResponse($message) {
        return redirect()->back()->with('error', $message);
    }

    /**
     * send success response when reset password
     * @param $message
     */
    private function sendSuccessResponse($message) {
        return redirect($this->redirectPath())->with('status', $message);
    }

    /**
     * validate reset password time expire
     * @param Hash $hash
     * @return bool
     */
    private function validateTimeExpire(Hash $hash) {
        $now = Carbon::now();
        $expire = new Carbon($hash->expire_at);
        if ($now > $expire) {
            return false;
        }
        return true;
    }

    /**
     * Return route after reset password success
     * @return string
     */
    protected function redirectPath() {
        return $this->redirectTo;
    }

}
