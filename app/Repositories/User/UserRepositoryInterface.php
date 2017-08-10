<?php

namespace App\Repositories\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface {
    /**
     * @param $email
     * @return \App\User
     */
    function getActiveUserByEmail($email);

    /**
     * @param $email
     * @return Collection
     */
    function getUsersByEmail($email);

    /**
     * @param $id
     * @return \App\User
     */
    function getUserById($id);

    /**
     * @param $id
     * @param array $attributes
     */
    function update($id, array $attributes);

    /**
     * @param array $attributes
     * @return User
     */
    function create(array $attributes);

    /**
     * @param $email
     * @return bool
     */
    function checkMail($email);
}