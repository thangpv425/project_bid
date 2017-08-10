<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\MailManager;
use App\Mail\RegisterMailable;
use App\Repositories\Hash\HashRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $message = array(
                'type' => 'error',
                'data' => 'Email already taken'
            );
        } else {
            //create new user
            try{
                DB::beginTransaction();
                $userData = [
                    'nickname' => $request->input('nickname'),
                    'email' => $request->input('email'),
                    'password' => bcrypt($request->input('password')),
                    'status' => Config::get('constants.user_status.inactive'),
                    'grant' => Config::get('constants.user_grant.user')
                ];
                $user = $this->user->create($userData);

                //create new hash
                $hashData = array(
                    'hash_key' => md5(uniqid()),
                    'type' => Config::get('constants.hash_type.register'),
                    'user_id' =>$user->id,
                    'expire_at' => Carbon::now()->addMinutes(Config::get('constants.time_during.register')),
                );
                $hash = $this->hash->create($hashData);

                //send mail
                $mailData = array(
                    'nickname' => $request->input('nickname'),
                    'email' => $request->input('email'),
                    'password' => $request->input('password'),
                    'link' => url(config('app.url').route('register.active',
                            array(
                                'hash_key' => $hash->hash_key,
                            ))),
                );

                $this->mailManager->send($request->input('email'), new RegisterMailable($mailData));

                //redirect
                $message = array(
                    'type' => 'success',
                    'data' => 'Please check yours email to active account'
                );
                DB::commit();
            }catch(\Exception $e){
                DB::rollback();
                $message = array(
                    'type' => 'success',
                    'data' => 'Error while create new hash'
                );
            }
        }
        return redirect()->back()->with(compact('message'));
    }

    public function active($hashKey) {
        $hashType = Config::get('constants.hash_type.register');
        $now = Carbon::now();
        $userStatus = Config::get('constants.user_status.inactive');
        $hash = $this->hash->getHash($hashKey, $hashType, $now, $userStatus);

        if ($hash != null &&
            ($user = $this->user->getUserById($hash->user_id)) != null) {
            try{
                DB::beginTransaction();
                $user->status = Config::get('constants.user_status.active');
                $user->save();
                //$hash->expire_at = Carbon::createFromDate(1970,1,1);
                $hash->save();
                $message = array(
                    'type' => 'success',
                    'data' => 'Yours account active success!'
                );
                DB::commit();
            }catch(\Exception $e){
                DB::rollback();
                $message = array(
                    'type' => 'error',
                    'data' => 'Error while update user and hash'
                );
            }
        } else if ($hash == null) {
            $message = array(
                'type' => 'error',
                'data' => 'Request not valid or timeout'
            );
        } else {
            $message = array(
                'type' => 'error',
                'data' => 'User Account has been deleted from system'
            );
        }
        return redirect()->back()->with(compact('message'));
    }

}
