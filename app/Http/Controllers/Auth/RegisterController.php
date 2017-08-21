<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\MailManager;
use App\Mail\RegisterMailable;
use App\Repositories\Hash\HashRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * hash repository
     * @var App\Repositories\Hash\HashRepositoryInterface
     */
    protected $hash;

    /**
     * hash repository
     * @var App\Repositories\User\UserRepositoryInterface
     */
    protected $user;

    /**
     * Mail manager
     * @var App\Mail\MailManager
     */
    protected $mailManager;


    public function __construct(HashRepositoryInterface $hash,
                                UserRepositoryInterface $user,
                                MailManager $mailManager) {
        $this->middleware('guest');
        $this->hash = $hash;
        $this->user = $user;
        $this->mailManager = $mailManager;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function show() {
        return view('auth.register');
    }


    public function register(Request $request) {
        //validate input
        $this->validate($request,[
            'nickname' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($this->user->checkMail($request->input('email')) == false) {
            return redirect()->back()->with('message', array(
                'type' => 'error',
                'data' => 'Địa chỉ email đã được sử dụng'
            ));
        }

        //create new user
        try{
            $hashKey = md5(uniqid());

            $userData = [
                'nickname' => $request->input('nickname'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'status' => Config::get('constants.user_status.inactive'),
                'grant' => Config::get('constants.user_grant.user')
            ];

            $mailData = array(
                'nickname' => $request->input('nickname'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'link' => url(config('app.url').route('register.active',
                        array(
                            'hash_key' => $hashKey,
                        ))),
            );

            DB::beginTransaction();

            $user = $this->user->create($userData);

            //create new hash
            $hashData = array(
                'hash_key' => $hashKey,
                'type' => Config::get('constants.hash_type.register'),
                'user_id' =>$user->id,
                'expire_at' => Carbon::now()->addMinutes(Config::get('constants.time_during.register')),
            );
            $this->hash->create($hashData);

            $this->mailManager->send($request->input('email'), new RegisterMailable($mailData));

            DB::commit();

            $message = array(
                'type' => 'success',
                'data' => 'Hãy kiểm tra email để kích hoạt tài khoản'
            );
        }catch(\Exception $e){
            DB::rollback();
            $message = array(
                'type' => 'success',
                'data' => 'Lỗi khi tạo hash mới'
            );
        }

        return redirect()->back()->with(compact('message'));
    }

    public function active($hashKey) {
        $hashType = Config::get('constants.hash_type.register');
        $userStatus = Config::get('constants.user_status.inactive');
        $hash = $this->hash->getHash($hashKey, $hashType, $userStatus);
        $userId = empty($hash) ? null : $hash->user_id;
        $user = $this->user->getUserById($userId);

        if (empty($hash) || (empty($user))) {
            return redirect()->back()->with('message', array(
                'type' => 'error',
                'data' => 'Tài khoản người dùng hoặc bảng hash đã bị xóa khỏi hệ thống'
            ));
        }

        try{
            DB::beginTransaction();
            $user->status = Config::get('constants.user_status.active');
            $user->save();
            $message = array(
                'type' => 'success',
                'data' => 'Tài khoản của bạn được kích hoạt thành công!'
            );
            DB::commit();
            $this->hash->cancelRegisterRequest($user->email);
        }catch(\Exception $e){
            DB::rollback();
            $message = array(
                'type' => 'error',
                'data' => 'Lỗi khi cập nhập thông tin người dùng hoặc bảng hash'
            );
        }
        
        return redirect()->back()->with(compact('message'));
    }

}
