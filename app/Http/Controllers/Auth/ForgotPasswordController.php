<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMailable;
use App\Mail\MailManager;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Hash\HashRepositoryInterface;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
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

    use SendsPasswordResetEmails;

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
     * Create a new controller instance.
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
        $this->validateEmail($request);

        $user = $this->userRepository->getUserByEmail($request->input('email'));

        if ($user == null || $user->status == Config::get('constants.user_status.de_active')) {
            return redirect()->back()->with('error','Email is not registered');
        }

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

        return redirect()->back()->with('status','Reset password link sent to mail');

    }
}
