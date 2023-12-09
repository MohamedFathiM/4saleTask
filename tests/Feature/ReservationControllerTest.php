<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setup(): void
    {
        parent::setup();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testTableAvailability()
    {
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
        $customer_id = Customer::factory()->create()->id;
        $table = Table::factory()->create();
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

    //**Reserve a table */
    public function testTableReservationSuccess()
    {
        $table = Table::factory()->create();
        $customer_id = Customer::factory()->create()->id;

        // Create a ReserveRequest instance with the necessary properties
        $request = [
            'date' => '2023-12-08',
            'from_time' => '09:00',
            'to_time' => '10:00',
            'table_id' => $table->id,
            'customer_id' => $customer_id,
            'guests_count' => ($table->capacity - 1)
        ];

        // Call the reserveTable API endpoint
        $response = $this->postJson(
            'api/v1/reserve-table',
            $request
        );

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Table reserved successfully',
            ]);
    }

    public function testTableReservationTableNotAvailable()
    {
        $this->withoutExceptionHandling();
        $customer_id = Customer::factory()->create()->id;

        $table = Table::factory()->create();
        // Create a ReserveRequest instance with the necessary properties
        $request = Reservation::factory()->create([
            'date' => '2023-12-08',
            'from_time' => '09:00',
            'to_time' => '10:00',
            'table_id' => $table->id,
        ]);

        // Call the reserveTable API endpoint and expect a ValidationException
        $this->expectException(ValidationException::class);

        $this->postJson('api/v1/reserve-table', $request->toArray() + [
            'customer_id' => $customer_id,
            'guests_count' => ($table->capacity - 1)
        ]);
    }

    public function testTableReservationTableNotSuitableForGuests()
    {
        $this->withoutExceptionHandling();
        $table = Table::factory()->create();
        $customer_id = Customer::factory()->create()->id;

        $request = [
            'date' => '2023-12-08',
            'from_time' => '09:00',
            'to_time' => '10:00',
            'table_id' => $table->id,
            'customer_id' => $customer_id,
            'guests_count' => ($table->capacity + 1)
        ];

        // Call the reserveTable API endpoint and expect a ValidationException
        $this->expectException(ValidationException::class);

        $this->postJson('api/v1/reserve-table', $request);
    }
}
