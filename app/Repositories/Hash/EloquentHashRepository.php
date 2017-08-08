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
     * @param Hash $model
     */
    public function __construct() {
        $this->app = new App();
        $this->makeModel();
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

    public function makeModel() {
        $model = $this->app->make($this->model());
        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    public function model() {
        return Hash::class;
    }

}