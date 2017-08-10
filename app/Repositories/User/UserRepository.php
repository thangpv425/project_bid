<?php
namespace App\Repositories\User;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class UserRepository implements UserRepositoryInterface {
    /**
     * @param $email
     * @return \App\User
     */
    public function getActiveUserByEmail($email) {
        $user = User::where('email', '=', $email)
            ->where('status', '=', Config::get('constants.user_status.active'))
            ->first();
        return $user ? $user : null;
    }

    /**
     * @param $email
     * @return Collection
     */
    function getUsersByEmail($email) {
        $users = User::where('email', '=', $email)
            ->get();
        return $users;
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

        $user = User::leftJoin('hashs', 'users.id', '=', 'hashs.user_id')
            ->where('users.status', '=', Config::get('constants.user_status.block'))
            ->orWhere(function($query) use ($email){
              $query->where('hashs.expire_at', '>=', Carbon::now())
                  ->where('users.status', '=', Config::get('constants.user_status.inactive'))
                  ->where('users.email', '=', $email)
                  ->where('hashs.type', '=', Config::get('constants.hash_type.register'));
            })
            ->orWhere('users.status', '=', Config::get('constants.user_status.active'))
            ->first();

        return $user ? false : true;
    }

}