<?php

namespace Modules\Kost\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RestfulKostTest extends TestCase
{
    /**
     * User can see available kost
     */
    public function testUserCanSeeAvailableKost()
    {
        $response = $this->json('get', 'api/v1/kost', [], ['Accept' => 'application/json']);

        $response->assertSuccessful();
    }
}
