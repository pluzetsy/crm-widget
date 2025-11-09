<?php

namespace Tests\Feature\Admin;

use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TicketAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function createManagerUser(): User
    {
        $user = User::factory()->create([
            'email' => 'manager@example.com',
        ]);

        Role::create(['name' => 'manager']);
        $user->assignRole('manager');

        return $user;
    }

    public function test_guest_is_redirected_to_login_when_accessing_admin_tickets(): void
    {
        $response = $this->get('/admin/tickets');

        $response->assertRedirect('/login');
    }

    public function test_manager_can_see_tickets_list(): void
    {
        $user = $this->createManagerUser();

        $customer = Customer::factory()->create();
        Ticket::factory()->create([
            'customer_id' => $customer->id,
            'status' => TicketStatus::New,
        ]);

        $response = $this->actingAs($user)->get('/admin/tickets');

        $response->assertOk();
        $response->assertSee('Tickets');
        $response->assertSee((string)Ticket::first()->id);
    }

    public function test_manager_can_update_ticket_status(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = $this->createManagerUser();

        $customer = Customer::factory()->create();

        $ticket = Ticket::factory()->create([
            'customer_id' => $customer->id,
            'status' => TicketStatus::New,
        ]);

        $response = $this->actingAs($user)->patch("/admin/tickets/{$ticket->id}", [
            'status' => TicketStatus::Done->value,
        ]);

        $response->assertRedirect(route('admin.tickets.show', $ticket));

        $this->assertEquals(
            TicketStatus::Done,
            $ticket->fresh()->status
        );
    }
}
