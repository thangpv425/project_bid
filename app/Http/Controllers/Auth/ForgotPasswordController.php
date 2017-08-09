<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMailable;
use App\Mail\MailManager;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Hash\HashRepositoryInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Carbon\Carbon;
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
    private $hashRepository;


    /**
     * @var
     */
    private $mailManager;

    /**\
     * @var
     */
    private $userRepository;

    /**
     * ForgotPasswordController constructor.
     * @param HashRepositoryInterface $hashRepository
     * @param UserRepositoryInterface $userRepository
     * @param MailManager $mailManager
     */
    public function __construct(HashRepositoryInterface $hashRepository,
                                UserRepositoryInterface $userRepository,
                                MailManager $mailManager) {
        $this->middleware('guest');
        $this->hashRepository = $hashRepository;
        $this->userRepository = $userRepository;
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

        $user = $this->userRepository->getUserByEmail($request->input('email'));

        if ($user == null || $user->status == Config::get('constants.user_status.inactive')) {
            $message = array(
                'type' => 'error',
                'data' => 'Email not register'
            );
        } else {
            $hashData = array(
                'hash_key' => md5(uniqid()),
                'type' => Config::get('constants.hash_type.forgot_password'),
                'user_id' =>$user->id,
                'expire_at' => Carbon::now()->addMinutes(Config::get('constants.time_during.forgot_password')),
            );

            $newHash = $this->hashRepository->create($hashData);

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

        $hash = $this->hashRepository->getHash($hashKey,$hashType,$now,$userStatus);
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
    public function reset(Request $request) {

        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
            'hash_key' => 'required'
        ]);

        $hashKey = $request->input('hash_key');
        $hashType = Config::get('constants.hash_type.forgot_password');
        $now = Carbon::now();
        $userStatus = Config::get('constants.user_status.active');

        $hash = $this->hashRepository->getHash($hashKey,$hashType,$now,$userStatus);
        if ($hash == null) {
            //TODO: make view for not valid token
            return redirect()->back()->with('error', 'Hash key not valid or timeout');
        }

        $this->userRepository->update($hash->user_id,[
            'password' => bcrypt($request->input('password')),
            'remember_token' => Str::random(60),
        ]);
        return redirect('login');
    }

}
