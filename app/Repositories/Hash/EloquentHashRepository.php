<?php
namespace App\Repositories\Hash;

use App\Hash;
use Illuminate\Container\Container as App;

class EloquentHashRepository implements HashRepositoryInterface {

    /**
     * @param $hashKey
     * @return Hash
     */
    public function getHashByKey($hashKey) {
        $hashs = Hash::where('hash_key', '=', $hashKey)
            ->limit(1)
            ->get();
        ;
        return $hashs.isEmpty() ? null : $hashs[0];
    }

    /**
     * @param $hashKey
     * @param $hashType
     * @param $now
     * @param $userStatus
     * @return Hash
     */
    public function getHash($hashKey, $hashType, $now, $userStatus) {
        $hashs =  Hash::leftJoin('users', 'hashs.user_id', '=', 'users.id')
            ->where('expire_at', '>=', $now)
            ->where('type', '=', $hashType)
            ->where('hash_key', '=', $hashKey)
            ->where('users.status', '=', $userStatus)
            ->limit(1)
            ->get();
        return $hashs->isEmpty() ? null : $hashs[0];
    }

    /**
     * @param array $attributes
     * @return Hash
     */
    public function create(array $attributes) {
        return Hash::create($attributes);
    }
}