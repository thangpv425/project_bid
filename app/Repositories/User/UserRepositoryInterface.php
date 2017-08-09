<?php

namespace App\Repositories\User;

interface UserRepositoryInterface {
    /**
     * @param $email
     * @return \App\User
     */
    function getUserByEmail($email);

    /**
     * @param $id
     * @return \App\User
     */
    function getUserById($id);

    /**
     * @param $id
     * @param array $attributes
     */
    public function update($id, array $attributes);
}