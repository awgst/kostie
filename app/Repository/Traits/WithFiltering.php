<?php

namespace App\Repository\Traits;

trait WithFiltering
{
    /**
     * Function to filter the model by request filter | data
     * @param array $request
     */
    public function filter(array $request)
    {
        $model = $this->getModel();

        $model = $this->_filters($request, $model);
        $this->setModel($model);
        return $this;
    }
}