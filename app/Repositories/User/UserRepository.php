<?php
namespace App\Repositories\User;

use App\User;
use Illuminate\Support\Facades\Config;

class UserRepository implements UserRepositoryInterface {
    /**
     * @param $email
     * @return \App\User
     */
    public function getUserByEmail($email) {
        $user = User::where('email', '=', $email)
            ->where('status', '=', Config::get('constants.user_status.active'))
            ->first();
        return $user ? $user : null;
    }

    /**
     * @param $id
     * @return \App\User
     */
    public function getUserById($id) {
        return User::find($id);
    }

    /**
     * @param $id
     * @param array $attributes
     */
    public function update($id, array $attributes) {
        $user = User::findOrFail($id);
        $user->update($attributes);
    }

    /**
     * @param array $attributes
     * @return User
     */
    public function create(array $attributes) {
        return User::create($attributes);
    }

    /**
     * Check mail valid or not
     * @param $email
     * @return bool
     */
    public function checkMail($email) {

        $user = User::where('users.email', '=', $email)
            ->where(function($query) use ($email){
                $query->where('users.status', '=', Config::get('constants.user_status.active'))
                    ->orWhere('users.status', '=', Config::get('constants.user_status.block'));
            })
            ->first();

        return $user ? false : true;
    }

}