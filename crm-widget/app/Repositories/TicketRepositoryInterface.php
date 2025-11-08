<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Ticket;

interface TicketRepositoryInterface
{
    public function createForCustomer(Customer $customer, array $data): Ticket;
}
