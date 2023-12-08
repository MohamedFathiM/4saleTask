<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setup(): void
    {
        parent::setup();
        $this->user = Customer::factory()->create()->user;
    }

    public function testTableAvailability()
    {
        $this->actingAs($this->user);
        $table = Table::factory()->create();

        // Make a request to the checkTableAvailability endpoint
        $response = $this->getJson('/api/v1/check-table-availability?datetime=2023-12-01 10:00&guests_count=4&table_id=' . $table->id);

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'is_available' => true,
                ],
                'message' => 'Table is available',
            ]);
    }

    public function testTableIsNotAvailable()
    {
        $this->actingAs($this->user);
        $table = Table::factory()->create();
        $customer_id = $this->user->id;
        $date = '2023-12-01';
        $from_time = '12:00';
        $to_time = '14:00';

        $reservation = Reservation::create([
            'table_id' => $table->id,
            'customer_id' => $customer_id,
            'date' => $date,
            'from_time' => $from_time,
            'to_time' => $to_time,
        ]);


        // Make a request to the checkTableAvailability endpoint
        $response = $this->getJson("/api/v1/check-table-availability?datetime=$date 13:00&guests_count={$table->capacity}&table_id={$table->id}");

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'is_available' => false,
                ],
                'message' => 'Table is not available',
            ]);
    }
}
