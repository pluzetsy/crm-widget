<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketStatisticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'periods' => [
                'day' => $this->formatPeriod($this->resource['day'] ?? []),
                'week' => $this->formatPeriod($this->resource['week'] ?? []),
                'month' => $this->formatPeriod($this->resource['month'] ?? []),
            ],
            'summary' => [
                'total_today' => $this->resource['day']['total'] ?? 0,
                'total_week' => $this->resource['week']['total'] ?? 0,
                'total_month' => $this->resource['month']['total'] ?? 0,
            ],
        ];
    }

    private function formatPeriod(array $data): array
    {
        return [
            'new' => $data['new'] ?? 0,
            'in_progress' => $data['in_progress'] ?? 0,
            'done' => $data['done'] ?? 0,
            'total' => $data['total'] ?? 0,
        ];
    }
}
