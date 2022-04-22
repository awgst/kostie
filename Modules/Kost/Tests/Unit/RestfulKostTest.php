<?php

namespace Modules\Kost\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $custom = new stdClass;
        $custom->name = 'Kos 1';
        $custom->address = 'Jogja';
        $custom->price = 500000;
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
                    ],
                    [
                        'name' => 'Kos 1',
                        'address' => 'Jogja',
                        'price' => 500000
                    ]
                ]
            ]
        ]);
    }
}
