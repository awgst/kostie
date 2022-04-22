<?php

namespace Modules\Kost\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Laravel\Passport\Passport;
use Modules\User\Constants\UserType;
use stdClass;

class RestfulKostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * User can see available kost | Kost Index
     */
    public function testUserCanSeeAvailableKost()
    {
        $kosts = $this->kost(5);
        $response = $this->json('get', 'api/v1/kost', [], ['Accept' => 'application/json']);

        $response->assertSuccessful();
        $this->assertTrue(count($kosts) == 5);
    }

    /**
     * Kost Detail
     */
    public function testUserCanSeeDetailKost()
    {
        $kost = $this->kost()[0];
        $response = $this->json('get', 'api/v1/kost/'.$kost['id'], [], ['Accept' => 'application/json']);

        $response->assertSuccessful();
        $response->assertJsonStructure(['message', 'data' => ['owner']]);
    }

    /**
     * Kost Index with filter by name, address/location and price
     */
    public function testUserCanFilterByNameAddressAndPrice()
    {
        $custom = new stdClass;
        $custom->name = 'Kos 1';
        $custom->address = 'Jogja';
        $custom->price = 350000;
        $custom->slot = 1;
        $kost = $this->kost(1, $custom);
        $response = $this->json('get', 'api/v1/kost', [
            'name' => 'Kos 1',
            'address' => 'Jogja',
            'price' => [100000, 500000]
        ], ['Accept' => 'application/json']);

        $response->assertSuccessful();
        $response->assertJson([
            'data' => [
                'data' => [
                    [
                        'name' => 'Kos 1',
                        'address' => 'Jogja',
                        'price' => 350000
                    ]
                ]
            ]
        ]);
    }

    /**
     * Sorted kost list by price
     */
    public function testKostListSortedByPrice()
    {
        $kost = $this->kost(10);
        $response = $this->json('get', 'api/v1/kost', [
            'sort' => 'price',
            'order' => 'asc'
        ], ['Accept' => 'application/json']);

        $response->assertSuccessful();
    }

    /**
     * Store kost
     */
    public function testOwnerCanAddAKost()
    {
        $user = $this->user(UserType::OWNER);
        $user = Passport::actingAs($user);

        $response = $this->json('post', 'api/v1/kost', [
            'name' => 'Kost Baru',
            'address' => 'Jl Magelang',
            'price' => 380000,
            'slot' => 1,
            'description' => 'Lorem ipsum'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(JsonResponse::HTTP_CREATED);
    }

    /**
     * User (not owner) cannot store
     */
    public function testRegularUserCannotAddAKost()
    {
        $user = $this->user(UserType::REGULAR);
        $user = Passport::actingAs($user);

        $response = $this->json('post', 'api/v1/kost', [
            'name' => 'Kost Baru',
            'address' => 'Jl Magelang',
            'price' => 380000,
            'slot' => 1,
            'description' => 'Lorem ipsum'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * User (not owner) cannot store
     */
    public function testPremiumUserCannotAddAKost()
    {
        $user = $this->user(UserType::PREMIUM);
        $user = Passport::actingAs($user);

        $response = $this->json('post', 'api/v1/kost', [
            'name' => 'Kost Baru',
            'address' => 'Jl Magelang',
            'price' => 380000,
            'slot' => 1,
            'description' => 'Lorem ipsum'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }
}
