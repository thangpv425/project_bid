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

}