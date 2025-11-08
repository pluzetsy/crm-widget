<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Ticket;
use App\TicketStatus;

class EloquentTicketRepository implements TicketRepositoryInterface
{
    public function createForCustomer(Customer $customer, array $data): Ticket
    {
        return Ticket::create([
            'customer_id' => $customer->id,
            'subject' => $data['subject'],
            'text' => $data['text'],
            'status' => TicketStatus::New->value,
        ]);
    }
}
