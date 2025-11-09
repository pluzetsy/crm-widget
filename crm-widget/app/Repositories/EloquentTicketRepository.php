<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Ticket;
use App\TicketStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function paginateWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return Ticket::query()
            ->with(['customer', 'manager', 'media'])
            ->when(!empty($filters['status']), fn(Builder $q) => $q->where('status', $filters['status']))
            ->when(!empty($filters['email']) || !empty($filters['phone']), function (Builder $q) use ($filters) {
                $q->whereHas('customer', function (Builder $query) use ($filters) {
                    if (!empty($filters['email'])) {
                        $query->where('email', 'ILIKE', '%' . $filters['email'] . '%');
                    }

                    if (!empty($filters['phone'])) {
                        $query->where('phone', 'ILIKE', '%' . $filters['phone'] . '%');
                    }
                });
            })
            ->when(!empty($filters['date_from']), fn(Builder $q) => $q->whereDate('created_at', '>=', $filters['date_from']))
            ->when(!empty($filters['date_to']), fn(Builder $q) => $q->whereDate('created_at', '<=', $filters['date_to']))
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function updateStatus(Ticket $ticket, string $status): Ticket
    {
        $ticket->status = $status;

        if ($status === TicketStatus::Done->value && is_null($ticket->handled_at)) {
            $ticket->handled_at = now();
        }

        $ticket->save();

        return $ticket;
    }

    public function getWithRelations(Ticket $ticket): Ticket
    {
        return $ticket->load(['customer', 'manager', 'media']);
    }
}
