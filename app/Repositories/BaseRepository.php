<?php
namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;
use DB;
use Exception;
abstract class BaseRepository implements BaseInterface
{
    protected $model;
    public function __construct($model)
    {
        $this->model = $model;
    }
    
    /**
     * @return all record
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param $limit $column
     * @return list record paginate
     */
    public function paginate($limit = null, $columns = ['*'])
    {
        $limit = is_null($limit) ? 10 : $limit;
        return $this->model->paginate($limit, $columns);
    }

    /**
     * @param $id $column
     * @return record with property
     */
    public function find($id, $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * @param $input
     * @return record created with $input
     */
    public function create($input)
    {
        return $this->model->create($input);
    }

    public function multiCreate($input)
    {
        return $this->model->insert($input);
    }

    /**
     * @param $input $id
     * @return record updated with $input
     */
    public function update($id, $input)
    {
        $model = $this->model->findOrFail($id);
        $model->fill($input);
        $model->save();
        return $this;
    }

    /**
     * @param $ids
     * @return delete record
     */
    public function delete($ids)
    {
        if (empty($ids)) {
            return true;
        }
        $ids = is_array($ids) ? $ids : [$ids];
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function latestRow() {
        return $this->model->latest()->first();
    }
}
