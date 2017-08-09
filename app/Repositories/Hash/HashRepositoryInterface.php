<?php

namespace App\Repositories\Hash;

interface HashRepositoryInterface {
    /**
     * @param $hashKey
     * @return mixed
     */
    function getHashByKey($hashKey);

    /**
     * @param $hashKey
     * @param $hashType
     * @param $now
     * @param $userStatus
     * @return Hash
     */
    function getHash($hashKey, $hashType, $now, $userStatus);

    /**
     * @param $userId
     * @param $hashType
     * @param $now
     * @param $userStatus
     * @return Collection
     */
    function getHashByUserId($userId, $hashType, $now, $userStatus);

    /**
     * @param array $attributes
     * @return Hash
     */
    function create(array $attributes);

}