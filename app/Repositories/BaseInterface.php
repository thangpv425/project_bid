<?php
namespace App\Repositories;
interface BaseInterface
{
    public function all();
    public function paginate($limit = null, $columns = ['*']);
    public function find($id, $columns = ['*']);
    public function create($input);
    public function update($id, $input);
    public function delete($id);
}
