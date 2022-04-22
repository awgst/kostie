<?php

namespace App\Repository\Traits;

trait WithSorting
{
    /**
     * Function to sort model by spesified column
     * @param string $column
     * @param mix $order
     */
    public function sortBy(string $column, $order = 'asc')
    {
        $model = $this->getModel();
        $model = $model->orderBy($column, $order);
        $this->setModel($model);

        return $this;
    }
}