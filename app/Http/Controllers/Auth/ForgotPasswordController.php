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

        $user = $this->user->getUserByEmail($request->input('email'));

        if (empty($user)) {
            return redirect()->back()->with('message', array(
                'type' => 'error',
                'data' => 'Địa chỉ Email không được đăng ký hoặc bị block!'
            ));
        }

        try {
            $hashData = array(
                'hash_key' => md5(uniqid()),
                'type' => Config::get('constants.hash_type.forgot_password'),
                'user_id' =>$user->id,
                'expire_at' => Carbon::now()->addMinutes(Config::get('constants.time_during.forgot_password')),
            );
            DB::beginTransaction();

            $newHash = $this->hash->create($hashData);
            $mailData = array(
                'link' => url(config('app.url').route('password.reset',
                        array(
                            'hash_key' => $newHash->hash_key,
                        ))),
            );

            DB::commit();

            $this->mailManager->send($request->input('email'), new ForgotPasswordMailable($mailData));
            $message = array(
                'type' => 'success',
                'data' => 'Link reset mật khẩu đã được gửi đến địa chỉ email'
            );
        } catch (\Exception $exception) {
            DB::rollback();
            $message = array(
                'type' => 'success',
                'data' => 'Lỗi khi tạo hash mới'
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
        $userStatus = Config::get('constants.user_status.active');

        $hash = $this->hash->getHash($hashKey,$hashType,$userStatus);

        if (!empty($hash)) {
            return view('auth.passwords.reset')->with('hashKey',$hashKey);
        }

        return view('auth.result')->with('message',array(
            'title' => 'Reset password',
            'type' => 'error',
            'data' => 'Yêu cầu không hợp lệ hoặc hết thời hạn'
        ));
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
        $userStatus = Config::get('constants.user_status.active');

        $hash = $this->hash->getHash($hashKey,$hashType,$userStatus);
        if (empty($hash)) {
            return view('auth.result')->with('message', array(
                'title' => 'Đặt lại mật khẩu',
                'type' => 'error',
                'data' => 'Yêu cầu không hợp lệ hoặc hết thời hạn'
            ));
        }

        try {
            $newAttrs = array(
                'password' => bcrypt($request->input('password')),
                'remember_token' => Str::random(60),
            );
            DB::beginTransaction();
            $this->user->update($hash->user_id,$newAttrs);
            $hash->expire_at = Carbon::createFromDate(1970,1,1);
            $hash->save();

            DB::commit();

            $message = array(
                'title' => 'Đặt lại mật khẩu',
                'type' => 'success',
                'data' => 'Đặt lại mật khẩu thành công'
            );

        } catch (\Exception $exception) {
            DB::rollback();
            $message = array(
                'title' => 'Đặt lại mật khẩu',
                'type' => 'error',
                'data' => 'Lỗi khi cập nhật mật khẩu mới'
            );
        }

        return view('auth.result')->with('message', $message);
    }
}
