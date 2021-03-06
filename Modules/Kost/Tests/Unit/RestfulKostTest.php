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
        Passport::actingAs($user);

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
        Passport::actingAs($user);

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
        Passport::actingAs($user);

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
     * update kost
     */
    public function testOwnerCanEditKost()
    {
        $user = $this->user(UserType::OWNER);
        Passport::actingAs($user);

        $kost = $this->kost(1, null, $user)[0];

        $response = $this->json('put', 'api/v1/kost/'.$kost['id'], [
            'name' => 'Kost Baru',
            'address' => 'Jl Magelang',
            'price' => 380000,
            'slot' => 1,
            'description' => 'Lorem ipsum'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * User (not owner) cannot update
     */
    public function testRegularUserCannotEditKost()
    {
        $user = $this->user(UserType::REGULAR);
        Passport::actingAs($user);

        $kost = $this->kost()[0];

        $response = $this->json('put', 'api/v1/kost/'.$kost['id'], [
            'name' => 'Kost Baru',
            'address' => 'Jl Magelang',
            'price' => 380000,
            'slot' => 1,
            'description' => 'Lorem ipsum'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * User (not owner) cannot update
     */
    public function testPremiumUserCannotEditKost()
    {
        $user = $this->user(UserType::PREMIUM);
        Passport::actingAs($user);

        $kost = $this->kost()[0];

        $response = $this->json('put', 'api/v1/kost/'.$kost['id'], [
            'name' => 'Kost Baru',
            'address' => 'Jl Magelang',
            'price' => 380000,
            'slot' => 1,
            'description' => 'Lorem ipsum'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * delete kost
     */
    public function testOwnerCanDeleteKost()
    {
        $user = $this->user(UserType::OWNER);
        Passport::actingAs($user);

        $kost = $this->kost(1, null, $user)[0];

        $response = $this->json('delete', 'api/v1/kost/'.$kost['id'], [], ['Accept' => 'application/json']);

        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * User (not owner) cannot delete
     */
    public function testRegularUserCannotDeleteKost()
    {
        $user = $this->user(UserType::REGULAR);
        Passport::actingAs($user);

        $kost = $this->kost()[0];

        $response = $this->json('delete', 'api/v1/kost/'.$kost['id'], [], ['Accept' => 'application/json']);

        $response->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * User (not owner) cannot delete
     */
    public function testPremiumUserCannotDeleteKost()
    {
        $user = $this->user(UserType::PREMIUM);
        Passport::actingAs($user);

        $kost = $this->kost()[0];

        $response = $this->json('delete', 'api/v1/kost/'.$kost['id'], [], ['Accept' => 'application/json']);

        $response->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }
}
