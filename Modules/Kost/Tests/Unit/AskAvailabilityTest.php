<?php

namespace Modules\Kost\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Laravel\Passport\Passport;
use Modules\Kost\Entities\Kost;
use Modules\User\Constants\UserType;

class AskAvailabilityTest extends TestCase
{
    /**
     * Test ask availability
     *
     * @return void
     */
    public function testUserAskKosAvailability()
    {
        $user = $this->user(UserType::PREMIUM);
        Passport::actingAs($user);

        $kost = $this->kost()[0];

        $response = $this->json('post', 'api/v1/kost/'.$kost['id'].'/ask-availability', [], ['Accept' => 'application/json']);

        $response->assertStatus(JsonResponse::HTTP_OK);

        $kost = Kost::find($kost['id']);
        $this->assertTrue($kost->checker->first()->id==$user->id);
    }
}
