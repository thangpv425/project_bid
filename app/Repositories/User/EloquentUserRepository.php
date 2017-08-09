<?php
namespace App\Repositories\User;

use App\User;

class EloquentUserRepository implements UserRepositoryInterface {
    /**
     * @param $email
     * @return \App\User
     */
    public function getUserByEmail($email) {
        $users = User::where('email', '=', $email)
            ->limit(1)
            ->get();
        return $users->isEmpty() ? null : $users->first();
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

}