<?php

namespace App\Http\Controllers\Auth;

use App\Hash;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMailable;
use App\Mail\RegisterMailable;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Send reset link to email
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function sendResetLinkEmail(Request $request) {
        $this->validateEmail($request);

        try {
            //check email user existed
            //make new token for reset mail and save to database
            //send to mail reset link
            $user = User::where('email',$request->input('email'))->firstOrFail();

            if ($user->status == Config::get('constants.user_status.de_active')) {
                return $this->sendFailResponse('Email is not registered');
            }

            $newHash = $this->createNewHash($user,$request);

            $mailData = array(
                'link' => $this->createResetLink($newHash, $user->id),
            );

            Mail::to($request->input('email'))
                ->send(new ForgotPasswordMailable($mailData));

            return $this->sendSuccessResponse('Reset link sent to yours email!');

        } catch (ModelNotFoundException $exception) {
            return $this->sendFailResponse('Email is not registered');
        }
    }

    /**
     * @param User $user
     * @param Request $request
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    private function createNewHash(User $user, Request $request) {
         return Hash::create([
            'hash_key' => md5(uniqid()),
            'type' => Config::get('constants.hash_type.forgot_password'),
            'user_id' =>$user->id,
            'expire_at' => Carbon::now()->addMinutes(Config::get('constants.time_during.forgot_password')),
        ]);
    }

    /**create reset link
     * @param Hash $hash
     * @param $userId
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    private function createResetLink(Hash $hash, $userId) {
        return url(config('app.url').route('password.reset',
                array(
                    'user_id' => $userId,
                    'hash_key' => $hash->hash_key,
                    'type' => $hash->type,
                )));
    }

    /**
     * Send fail response for a failed password reset link
     * @param $message
     * @return \Illuminate\Http\RedirectResponse
     */

    private function sendFailResponse($message) {
        return redirect()->back()->with('error', $message);
    }

    /**
     * Send success response for a failed password reset link
     * @param $message
     * @return \Illuminate\Http\RedirectResponse
     */
    private function sendSuccessResponse($message) {
        return redirect()->back()->with('status', $message);
    }
}
