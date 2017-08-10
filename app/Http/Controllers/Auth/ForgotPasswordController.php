<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMailable;
use App\Mail\MailManager;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Hash\HashRepositoryInterface;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    /**
     * @var
     */
    private $hash;


    /**
     * @var
     */
    private $mailManager;

    /**\
     * @var
     */
    private $user;

    /**
     * ForgotPasswordController constructor.
     * @param HashRepositoryInterface $hash
     * @param UserRepositoryInterface $user
     * @param MailManager $mailManager
     */
    public function __construct(HashRepositoryInterface $hash,
                                UserRepositoryInterface $user,
                                MailManager $mailManager) {
        $this->middleware('guest');
        $this->hash= $hash;
        $this->user = $user;
        $this->mailManager = $mailManager;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show() {
        return view('auth.passwords.email');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendMail(Request $request) {
        $this->validate($request, ['email' => 'required|email']);

        $user = $this->user->getActiveUserByEmail($request->input('email'));

        if ($user == null ||
            $user->status == Config::get('constants.user_status.inactive') ||
            $user->status == Config::get('constants.user_status.block')) {

            $message = array(
                'type' => 'error',
                'data' => 'Email not register'
            );

        } else {
            try {
                DB::beginTransaction();
                $hashData = array(
                    'hash_key' => md5(uniqid()),
                    'type' => Config::get('constants.hash_type.forgot_password'),
                    'user_id' =>$user->id,
                    'expire_at' => Carbon::now()->addMinutes(Config::get('constants.time_during.forgot_password')),
                );
                $newHash = $this->hash->create($hashData);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollback();
                $message = array(
                    'type' => 'success',
                    'data' => 'Error while create new hash'
                );
                return redirect()->back()->with(compact('message'));
            }


            $mailData = array(
                'link' => url(config('app.url').route('password.reset',
                        array(
                            'hash_key' => $newHash->hash_key,
                        ))),
            );

            $this->mailManager->send($request->input('email'),
                new ForgotPasswordMailable($mailData));
            $message = array(
                'type' => 'success',
                'data' => 'Reset password link sent to mail'
            );
        }
        return redirect()->back()->with(compact('message'));
    }

    /**
     * show form reset
     * @param $hashKey
     * @return View
     */
    public function showResetForm($hashKey) {

        $hashType = Config::get('constants.hash_type.forgot_password');
        $now = Carbon::now();
        $userStatus = Config::get('constants.user_status.active');

        $hash = $this->hash->getHash($hashKey,$hashType,$now,$userStatus);
        if ($hash != null) {
            return view('auth.passwords.reset')->with('hashKey',$hashKey);
        }

        //TODO: make view for not valid token!
        return 'Hash key not valid or timeout';
    }

    /**
     * Reset password
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forgot(Request $request) {

        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
            'hash_key' => 'required'
        ]);

        $hashKey = $request->input('hash_key');
        $hashType = Config::get('constants.hash_type.forgot_password');
        $now = Carbon::now();
        $userStatus = Config::get('constants.user_status.active');

        $hash = $this->hash->getHash($hashKey,$hashType,$now,$userStatus);
        if ($hash == null) {
            $message = array(
                'type' => 'error',
                'data' => 'Request not valid or timeout'
            );
        } else {
            try {
                DB::beginTransaction();
                $this->user->update($hash->user_id,[
                    'password' => bcrypt($request->input('password')),
                    'remember_token' => Str::random(60),
                ]);
                $hash->expire_at = Carbon::createFromDate(1970,1,1);
                $hash->save();
                $message = array(
                    'type' => 'success',
                    'data' => 'Password reset success'
                );
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollback();
                $message = array(
                    'type' => 'error',
                    'data' => 'Error while update user'
                );
            }
        }
        return redirect()->back()->with($message);
    }

}
