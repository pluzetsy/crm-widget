<?php

namespace Tests\Feature\Api;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_create_ticket_with_attachments(): void
    {
        Storage::fake('public');

        $response = $this->postJson('/api/tickets', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+48123123123',
            'subject' => 'Example subject',
            'text' => 'This is test ticket text.',
            'attachments' => [
                UploadedFile::fake()->create('test1.txt', 10),
                UploadedFile::fake()->create('test2.txt', 20),
            ],
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'subject',
                    'text',
                    'status',
                    'handled_at',
                    'created_at',
                    'updated_at',
                    'customer' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                    ],
                    'manager',
                    'attachments' => [
                        ['id', 'name', 'url'],
                    ],
                ],
                'message',
            ]);

        $this->assertDatabaseCount('tickets', 1);
        $this->assertDatabaseCount('customers', 1);

        $ticket = Ticket::first();
        $this->assertEquals('Example subject', $ticket->subject);
        $this->assertEquals('new', $ticket->status->value);
        $this->assertEquals('John Doe', $ticket->customer->name);

        $this->assertCount(2, $ticket->media);
    }

    public function test_validation_errors_are_returned_as_json(): void
    {
        $response = $this->postJson('/api/tickets', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'subject', 'text']);
    }

    public function test_cannot_create_more_than_one_ticket_per_day_for_same_email_or_phone(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'john@example.com',
            'phone' => '+48123123123',
        ]);

        Ticket::factory()->create([
            'customer_id' => $customer->id,
            'created_at' => Carbon::now()->subHours(1),
        ]);

        $response = $this->postJson('/api/tickets', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+48123123123',
            'subject' => 'Example subject',
            'text' => 'This is test ticket text.',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
