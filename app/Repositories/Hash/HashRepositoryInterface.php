<?php

namespace App\Repositories\Hash;

interface HashRepositoryInterface {

    /**create new hash
     * @param array $attributes
     */
    function create(array $attributes);

    /**
     * get hash by hash key
     * @param $hashKey
     * @return App/Hash
     */
    function getHashByHashKey($hashKey);

}