<?php
namespace App\Repositories\Hash;

use App\Hash;
use Carbon\Carbon;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

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
     * @param $userStatus
     * @return Hash
     */
    public function getHash($hashKey, $hashType, $userStatus) {
        $now = Carbon::now();
        $hash =  Hash::leftJoin('users', 'hashs.user_id', '=', 'users.id')
            ->select('hashs.*')
            ->where('expire_at', '>', $now)
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

    public function cancelRegisterRequest($email) {
        $now = Carbon::now();
        $hashType = Config::get('constants.hash_type.register');

        $hashs = Hash::leftJoin('users', 'hashs.user_id', '=', 'users.id')
            ->select('hashs.*')
            ->where('expire_at', '>=', $now)
            ->where('type', '=', $hashType)
            ->get();

        try {
            DB::beginTransaction();
            foreach ($hashs as $hash) {
                $hash->expire_at = Carbon::createFromDate('1970', '1', '1');
                $hash->save();
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
        }
    }
}