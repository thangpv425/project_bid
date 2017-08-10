<?php
namespace App\Repositories\Hash;

use App\Hash;
use Illuminate\Container\Container as App;

class HashRepository implements HashRepositoryInterface {

    /**
     * @param $hashKey
     * @return Hash
     */
    public function getHashByKey($hashKey) {
        $hash = Hash::where('hash_key', '=', $hashKey)
            ->first();
        return $hash ? $hash : null;
    }

    /**
     * Get hash still valid with :
     * @param $hashKey
     * @param $hashType
     * @param $now
     * @param $userStatus
     * @return Hash
     */
    public function getHash($hashKey, $hashType, $now, $userStatus) {
        $hash =  Hash::leftJoin('users', 'hashs.user_id', '=', 'users.id')
            ->select('hashs.*')
            ->where('expire_at', '>=', $now)
            ->where('type', '=', $hashType)
            ->where('hash_key', '=', $hashKey)
            ->where('users.status', '=', $userStatus)
            ->first();
        return $hash ? $hash :  null ;
    }

    /**
     * @param array $attributes
     * @return Hash
     */
    public function create(array $attributes) {
        return Hash::create($attributes);
    }
}