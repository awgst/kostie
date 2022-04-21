<?php

namespace App\Repository\Contract;

interface RepositoryInterface
{
    // Destroy spesified model
    public function destroy($id);
    // Fetch list data
    public function fetch(array $with=null);
    // Find spesified model by id
    public function find($id, array $with=null);
    // Find spesified model by column
    public function findBy(string $column, $search, array $with=null);
    // Find spesified model by id, throw exception if model not exists
    public function findOrFail($id, array $with=null);
    // Get the assigned model
    public function getModel();
    // Paginate list data
    public function paginate(int $number, array $with=null);
    // Store data
    public function store(array $data);
    // Get the setted table name
    public function table() : string;
    // Update data
    public function update($id, array $data);
}