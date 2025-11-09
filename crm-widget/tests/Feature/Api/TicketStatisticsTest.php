<?php

namespace Tests\Feature\Api;

use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TicketStatisticsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_it_returns_statistics_grouped_by_periods(): void
    {
        $customer = Customer::factory()->create();

        Ticket::factory()->create([
            'customer_id' => $customer->id,
            'status' => TicketStatus::New,
            'created_at' => Carbon::now(),
        ]);

        Ticket::factory()->count(2)->create([
            'customer_id' => $customer->id,
            'status' => TicketStatus::InProgress,
            'created_at' => Carbon::now()->subDays(3),
        ]);

        Ticket::factory()->create([
            'customer_id' => $customer->id,
            'status' => TicketStatus::Done,
            'created_at' => Carbon::now()->subDays(10),
        ]);

        $response = $this->getJson('/api/tickets/statistics');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'periods' => [
                        'day' => ['new', 'in_progress', 'done', 'total'],
                        'week' => ['new', 'in_progress', 'done', 'total'],
                        'month' => ['new', 'in_progress', 'done', 'total'],
                    ],
                    'summary' => [
                        'total_today',
                        'total_week',
                        'total_month',
                    ],
                ],
                'message',
            ]);
    }
}
