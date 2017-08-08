<?php
namespace App\Repositories\User;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Container\Container as App;
use Mockery\Exception;
use Illuminate\Database\Eloquent\Model;

class EloquentUserRepository implements UserRepositoryInterface {

    /**
     * @var App
     */
    protected $app;

    /**
     * @var
     */
    protected $model;

    public function __construct(User $model) {
        $this->model = $model;
    }

    /**
     * @param $email
     * @return \App\User
     */
    public function getUserByEmail($email) {
        try {
            return User::where('email', '=', $email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return null;
        }
    }

    /**
     * @param $id
     * @return \App\User
     */
    public function getUserById($id) {
        return User::find($id);
    }

    public function makeModel() {
        $model = $this->app->make($this->model());
        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    public function model() {
        return User::class;
    }

}