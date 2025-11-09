<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'manager_id'  => null,
            'subject'     => $this->faker->sentence(4),
            'text'        => $this->faker->paragraph(),
            'status'      => $this->faker->randomElement(TicketStatus::values()),
            'handled_at'  => null,
        ];
    }
}
