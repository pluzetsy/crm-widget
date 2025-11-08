<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use App\TicketStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $managers = User::role('manager')->get();

        if ($managers->isEmpty()) {
            return;
        }

        Customer::factory(10)->create()->each(function (Customer $customer) use ($managers) {
            Ticket::factory(rand(3, 5))->create([
                'customer_id' => $customer->id,
                'manager_id' => $managers->random()->id,
            ]);
        });

        Ticket::query()
            ->inRandomOrder()
            ->limit(10)
            ->update([
                'status' => TicketStatus::Done->value,
                'handled_at' => now(),
            ]);
    }
}
