<?php
namespace App\Repositories\Hash;

use App\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Illuminate\Database\Eloquent\Model;

class EloquentHashRepository implements HashRepositoryInterface {

    /**
     * @var App
     */
    protected $app;

    /**
     * @var
     */
    protected $model;


    /**
     * EloquentHashRepository constructor.
     * @param User $model
     */
    public function __construct(Hash $model) {
        $this->model = $model;
    }

    /**
     * create new hash
     * @param array $attributes
     * @return Hash
     */
    public function create(array $attributes) {
        return $this->model->create($attributes);
    }

    /**
     * @param $hashKey
     * @return Hash
     */
    public function getHashByHashKey($hashKey) {
        try {
            $hash = $this->model
                ->where('hash_key', '=', $hashKey)
                ->firstOrFail();
            return $hash;
        } catch (ModelNotFoundException $exception) {
            return null;
        }
    }
}