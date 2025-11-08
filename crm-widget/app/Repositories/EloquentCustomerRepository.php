<?php

namespace App\Repositories;

use App\Models\Customer;

class EloquentCustomerRepository implements CustomerRepositoryInterface
{
    public function findOrCreateForTicket(array $data): Customer
    {
        return Customer::firstOrCreate(
            ['email' => $data['email'] ?? null],
            [
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
            ]
        );
    }
}
