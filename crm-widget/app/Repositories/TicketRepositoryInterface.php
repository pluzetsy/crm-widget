<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Pagination\LengthAwarePaginator;

interface TicketRepositoryInterface
{
    public function createForCustomer(Customer $customer, array $data): Ticket;

    public function getStatistics(): array;

    public function paginateWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function updateStatus(Ticket $ticket, string $status): Ticket;

    public function getWithRelations(Ticket $ticket): Ticket;
}
