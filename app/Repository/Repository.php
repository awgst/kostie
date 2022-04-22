<?php

namespace App\Repository;

use App\Repository\Contract\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class Repository implements RepositoryInterface
{
    private $model;
    private $table;

    public function __construct(Model $model)
    {   
        $this->model = $model;
        $this->table = $this->setTable();
    }

    /**
     * Destroy spesified model
     * @param mix $id
     */
    public function destroy($id)
    {
        return $this->find($id)->delete();
    }

    /**
     * Fetch list data
     * @param array $with
     */
    public function fetch(array $with=null)
    {
        $model = $this->model;
        if ($with) {
            $model = $model->with($with);
        }
        
        return $model->get();
    }

    /**
     * Find spesified model by id
     * @param mix $id
     * @param array $with
     */
    public function find($id, array $with=null)
    {
        $model = $this->model;
        if ($with) {
            $model = $model->with($with);
        }

        return $model->find($id);
    }

    /**
     * Function Find By specified column
     * @param string $column
     * @param mix $search
     * @param array $with - for eager loading
     */
    public function findBy(string $column, $search, array $with=null)
    {
        $model = $this->model;
        if ($with) {
            $model = $model->with($with);
        }

        $result = $model->where($column, $search)->get();

        return $result->isEmpty() ? null : $result;
    }

    /**
     * Find spesified model by id, throw exception if model not exists
     * @param mix $id
     * @param array $with
     */
    public function findOrFail($id, array $with=null)
    {
        $model = $this->model;
        if ($with) {
            $model = $model->with($with);
        }

        return $model->findOrFail($id);
    }

    /**
     * Get the assigned model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the model
     */
    protected function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Paginate list data
     * @param int $number
     * @param array $with
     */
    public function paginate(int $number, array $with=null)
    {
        $model = $this->model;
        if ($with) {
            $model = $model->with($with);
        }

        return $model->paginate($number);
    }

    /**
     * Set table name of assigned model
     */
    private function setTable() : string
    {
        $model = $this->getModel();
        $connection = $model->getConnectionName();
        // Get Database name of table
        $dbName = config('database.connections.'.$connection.'.database');
        return $dbName.'.'.$model->getTable();
    }

    /**
     * Store data
     * @param array $data
     */
    public function store(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Get the setted table name
     */
    public function table() : string
    {
        return $this->table;
    }

    /**
     * Update data
     * @param mix $id
     * @param array $data
     */
    public function update($id, array $data)
    {
        $updated = $this->findOrFail($id);
        $updated->update($data);
        return $updated;
    }
}