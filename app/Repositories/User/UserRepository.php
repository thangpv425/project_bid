<?php
namespace App\Repositories\User;

use App\User;
use App\Models\Bid;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface {
    /**
     * @param $email
     * @return \App\User
     */
    public function getUserByEmail($email) {
        $user = User::where('email', '=', $email)
            ->where('status', '=', Config::get('constants.user_status.active'))
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
     * @return bool
     */
    public function update($id, array $attributes) {
        $user = User::findOrFail($id);
        try {
            DB::beginTransaction();
            $user->update($attributes);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            return false;
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return User
     */
    public function create(array $attributes) {
        return User::create($attributes);
    }

    /**
     * Check mail valid or not
     * @param $email
     * @return bool
     */
    //TODO: sua lai
    public function checkMail($email) {

        $user = User::where('users.email', '=', $email)
            ->where(function($query) use ($email){
                $query->where('users.status', '=', Config::get('constants.user_status.active'))
                    ->orWhere('users.status', '=', Config::get('constants.user_status.block'));
            })
            ->first();

        return $user ? false : true;
    }

    /**
     * check new email valid
     * @param $email
     * @return bool
     */
    public function checkChangeEmail($email) {
        $user = User::leftJoin('hashs', 'users.id', '=', 'hashs.user_id')
            ->select('users.*')
            ->where(function($query) use ($email) {
                $query->where('email', '=', $email)
                    ->where(function($query) {
                        $query->where('users.status', '=', Config::get('constants.user_status.active'))
                            ->orWhere('users.status', '=', Config::get('constants.user_status.block'));
                    });

            })->orWhere(function ($query) use ($email) {
                $query->where('hashs.expire_at', '>' , Carbon::now())
                    ->where(function ($query) use ($email) {
                       $query->where(function ($query) use ($email){
                           $query->where('users.new_email', '=', $email)
                               ->where('hashs.type', '=', Config::get('constants.hash_type.change_email'));
                       })
                           ->orWhere(function ($query) use ($email){
                               $query->where('users.email', '=', $email)
                                   ->where('hashs.type', '=', Config::get('constants.hash_type.register'));
                           });
                    });

            })->first();

        return empty($user) ? true : false;
    }

    /**
     * @param $userId
     * @return bool true if ok
     */
    public function checkDeleteAccount($userId) {
        $row = Bid::where('current_highest_bidder_id', '=', $userId)
            ->where(function ($query) {
                $query->where('time_end', '>', Carbon::now())
                    ->orWhere(function ($query) {
                        $query->where('status', '!=', Config::get('constants.bid_status.payment_confirm_success'))
                            ->where('status', '!=', Config::get('constants.bid_status.shipping'));
                    });
            })->first();

        return empty($row) ? true : false;
    }

}