<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user login
     */
    public function testUserCanLoginWithCorrectCredentials()
    {
        $user = $this->user();
        $password = $this->password;
        $response = $this->post('api/v1/login', [
            'email' => $user->email,
            'password' => $password
        ]);

        $response->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'credit',
                'type',
                'created_at',
                'updated_at'
            ],
            'access_token'
        ]);
    }
}
