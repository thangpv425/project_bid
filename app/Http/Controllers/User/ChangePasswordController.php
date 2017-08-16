<?php

namespace App\Http\Controllers\User;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ChangePasswordController extends Controller {

    /**
     * User repository
     * @var UserRepositoryInterface
     */
    protected $user;

    public function __construct(UserRepositoryInterface $user) {
        $this->middleware('auth');
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show() {
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
}
