<?php

namespace App\Services;

use App\Models\Ticket;
use App\Repositories\CustomerRepositoryInterface;
use App\Repositories\TicketRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

readonly class TicketService
{
    public function __construct(
        private TicketRepositoryInterface   $tickets,
        private CustomerRepositoryInterface $customers,
    )
    {
    }

    public function create(array $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            $customer = $this->customers->findOrCreateForTicket($data);
            $ticket = $this->tickets->createForCustomer($customer, $data);

            if (!empty($data['attachments'])) {
                foreach ($data['attachments'] as $file) {
                    $ticket->addMedia($file)->toMediaCollection('attachments');
                }
            }

            return $ticket->load('customer', 'manager', 'media');
        });
    }

    public function getStatistics(): array
    {
        return $this->tickets->getStatistics();
    }

    public function paginateWithFilters(array $filters = []): LengthAwarePaginator
    {
        return $this->tickets->paginateWithFilters($filters);
    }

    public function updateStatus(Ticket $ticket, string $status): Ticket
    {
        return $this->tickets->updateStatus($ticket, $status)->load('customer', 'manager', 'media');
    }

    public function getWithRelations(Ticket $ticket): Ticket
    {
        return $this->tickets->getWithRelations($ticket);
    }
}
