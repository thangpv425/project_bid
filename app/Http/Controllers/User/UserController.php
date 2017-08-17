<?php

namespace App\Http\Controllers\User;

use App\Mail\MailManager;
use App\Repositories\Hash\HashRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\ChangeEmailMailable;
use Carbon\Carbon;

class UserController extends Controller {

    /**
     * User repository
     * @var UserRepositoryInterface
     */
    protected $user;

    /**
     * Hash Repository
     * @var HashRepositoryInterface
     */
    protected $hash;

    /**
     * Mail Manager
     * @var
     */
    protected $mailManager;

    public function __construct(UserRepositoryInterface $user, HashRepositoryInterface $hash, MailManager $mailManager) {
        $this->middleware('auth');
        $this->user = $user;
        $this->hash = $hash;
        $this->mailManager = $mailManager;
    }

    public function index() {
        return view('home');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showChangePassword() {
        return view('user.change_password');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request) {
        $this->validate($request, [
            'current_password' => 'required|string|min:6|',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $curPassword = $request->input('current_password');
        $newPassword = $request->input('password');

        $user = Auth::user();
        if (!Hash::check($curPassword, $user->password)) {
            $message = array(
                'type' => 'error',
                'data' => 'Your password not valid!'
            );
        } else {
            try {
                $newAttrs = array(
                    'password' => bcrypt($newPassword),
                    'remember_token' => Str::random(60),
                );

                DB::beginTransaction();
                $this->user->update($user->id, $newAttrs);
                DB::commit();

                $message = array(
                    'type' => 'success',
                    'data' => 'Yours password has been changed!'
                );

            } catch (\Exception $exception) {
                DB::rollback();
                $message = array(
                    'type' => 'error',
                    'data' => 'Error while update yours password'
                );
            }
        }
        return redirect()->back()->with(compact('message'));
    }

    /**
     * show change email form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showChangeEmail() {
        return view('user.change_email');
    }

    public function changeEmail(Request $request) {
        //validate input
        $this->validate($request, [
           'current_email' => 'required|email',
            'email' => 'required|email|confirmed'
        ]);
        $currentEmail = $request->input('current_email');
        $newEmail = $request->input('email');

        $user = Auth::user();

        if ($user->email != $currentEmail) {
            $message = array(
                'type'  => 'error',
                'data' => 'Yours email not match',
            );
        } else if (!$this->user->checkChangeEmail($newEmail)) {
            $message = array(
                'type' => 'error',
                'data' => 'New email has been used!'
            );
        } else {
            try {
                $hashData = array(
                    'hash_key' => md5(uniqid()),
                    'type' => Config::get('constants.hash_type.change_email'),
                    'user_id' =>$user->id,
                    'expire_at' => Carbon::now()->addMinutes(Config::get('constants.time_during.change_email')),
                );

                DB::beginTransaction();
                $newHash = $this->hash->create($hashData);

                //update new_email
                $this->user->update($user->id, array('new_email' => $newEmail));

                DB::commit();

                //send mail
                $mailData = array(
                    'nickname' => $user->nickname,
                    'new_email' => $newEmail,
                    'link' => url(config('app.url').route('user.confirm.change-email',
                            array(
                                'hash_key' => $newHash->hash_key,
                            ))),
                );
                $this->mailManager->send($currentEmail, new ChangeEmailMailable($mailData));

                //message
                $message = array(
                    'type' => 'success',
                    'data' => 'Change email link sent to mail',
                );

            } catch (\Exception $exception) {
                $message = array(
                    'type' => 'error',
                    'data' => 'Error while create hash key',
                );
                DB::rollback();
            }
        }

        return redirect()->back()->with(compact('message'));
    }

    /**
     * Change email after user confirm
     * @param $hashKey
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmChangeEmail($hashKey) {
        $hashType = Config::get('constants.hash_type.change_email');
        $userStatus = Config::get('constants.user_status.active');
        $hash = $this->hash->getHash($hashKey, $hashType, $userStatus);
        $userId = empty($hash) ? null : $hash->user_id;
        $user = $this->user->getUserById($userId);

        if (!(empty($hash)) && !(empty($user))) {
            try {
                DB::beginTransaction();
                //update user
                $user->email = $user->new_email;
                $user->new_email = null;
                $user->remember_token = Str::random(60);
                $user->save();

                //update hash
                $hash->expire_at = Carbon::createFromDate('1970', '1', '1');
                $hash->save();


                DB::commit();

                $message = array(
                    'type' => 'success',
                    'data' => 'Change email success!'
                );
            } catch (\Exception $exception) {
                DB::rollback();
                $message = array(
                    'type' => 'error',
                    'data' => 'Error while update user and hash data'
                );
            }
        } else {
            $message = array(
                'type' => 'error',
                'data' => 'User Account or hash has been deleted from system',
            );
        }

        return redirect()->back()->with(compact('message'));
    }
}
