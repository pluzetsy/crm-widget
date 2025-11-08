<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Ticket;
use App\TicketStatus;
use Illuminate\Database\Eloquent\Builder;

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

    public function getStatistics(): array
    {
        return [
            'day' => $this->countsByStatus(fn(Builder $q) => $q->createdToday()),
            'week' => $this->countsByStatus(fn(Builder $q) => $q->createdThisWeek()),
            'month' => $this->countsByStatus(fn(Builder $q) => $q->createdThisMonth()),
        ];
    }

    private function countsByStatus(callable $scope): array
    {
        $query = Ticket::query();
        $scope($query);

        $raw = $query
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status')
            ->all();

        $result = [];
        foreach (TicketStatus::values() as $status) {
            $result[$status] = $raw[$status] ?? 0;
        }

        $result['total'] = array_sum($result);

        return $result;
    }
}
