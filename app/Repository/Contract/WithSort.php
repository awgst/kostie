<?php

namespace App\Repository\Contract;

interface WithSort
{
    // Function to sort model by spesified column
    public function sortBy(string $column, $order = 'asc');    
}