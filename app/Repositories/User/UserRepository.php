<?php
namespace App\Repositories\User;

use App\User;

class UserRepository implements UserRepositoryInterface {
    /**
     * @param $email
     * @return \App\User
     */
    public function getUserByEmail($email) {
        $user = User::where('email', '=', $email)
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

}