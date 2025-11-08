<?php

namespace App\Repositories;

use App\Models\Customer;

interface CustomerRepositoryInterface
{
    public function findOrCreateForTicket(array $data): Customer;
}
