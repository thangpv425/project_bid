<?php

namespace App\Http\Controllers\User;

use App\Mail\MailManager;
use App\Repositories\Bid\BidRepositoryInterface;
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
     * Bid Repository
     * @var
     */
    protected $bid;

    /**
     * Mail Manager
     * @var
     */
    protected $mailManager;

    public function __construct(UserRepositoryInterface $user,
                                HashRepositoryInterface $hash,
                                MailManager $mailManager,
                                BidRepositoryInterface $bid) {
        $this->middleware('auth');
        $this->user = $user;
        $this->hash = $hash;
        $this->mailManager = $mailManager;
        $this->bid = $bid;
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
            return redirect()->back()->with(array(
                'type' => 'error',
                'data' => 'Your password not valid!'
            ));
        }

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
            'email' => 'required|email|confirmed'
        ]);
        $newEmail = $request->input('email');

        $user = Auth::user();

        if (!$this->user->checkChangeEmail($newEmail)) {
            return redirect()->back()->with('message', array(
                'type' => 'error',
                'data' => 'New email has been used!'
            ));
        }

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

            //send mail
            $mailData = array(
                'nickname' => $user->nickname,
                'new_email' => $newEmail,
                'link' => url(config('app.url').route('user.confirm.change-email',
                        array(
                            'hash_key' => $newHash->hash_key,
                        ))),
            );
            $this->mailManager->send($user->email, new ChangeEmailMailable($mailData));

            DB::commit();

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

        if (empty($hash) || empty($user)) {
            return redirect()->back()->with('message', array(
                'type' => 'error',
                'data' => 'User Account or hash has been deleted from system',
            ));
        }

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

        return redirect()->back()->with(compact('message'));
    }

    /**
     * show delete account form
     * @return View
     */
    public function showInactiveForm() {
        return view('user.delete_account')->with('email', Auth::user()->email);
    }

    public function inactive(Request $request) {
        $this->validate($request,[
            'password' => 'required|string|min:6'
        ]);

        $user = Auth::user();
        if (!Hash::check($request->input('password'), $user->password)) {
            return redirect()->back()->with('message', array(
                'type' => 'error',
                'data' => 'Your password not valid!'
            ));
        }

        if (!$this->bid->checkInactiveAccount($user->id)) {
            return redirect()->back()->with('message', array(
                'type' => 'error',
                'data' => 'You can not inactive yours account now'
            ));
        }

        //change user status: inactive
        $success =$this->user->update($user->id, array('status' => Config::get('constants.user_status.inactive')));

        if (!$success) {
            return redirect()->back()->with('message', array(
                'type' => 'error',
                'data' => 'Error while inactive yours account'
            ));
        }

        Auth::logout();
        return redirect('home');

    }


}
