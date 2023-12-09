<?php

namespace Tests\Feature;

use App\Http\Resources\API\V1\MealResource;
use App\Models\Customer;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MenuControllerTest extends TestCase
{
    use RefreshDatabase;


    private User $user;

    public function setup(): void
    {
        parent::setup();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testMenuIndex()
    {
        $meals = Meal::factory()->count(5)->create();
        $response = $this->getJson('/api/v1/menus');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'price',
                        'description',
                        'available_quantity',
                        'discount',
                        'created_at',
                    ],
                ],
            ])
            ->assertJsonCount(5, 'data');

        $response->assertJsonFragment([
            'data' => MealResource::collection($meals)->toArray(request()),
        ]);
    }
}
