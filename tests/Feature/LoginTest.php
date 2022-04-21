<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
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

        $response->assertSuccessful();
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

    /**
     * Test user login with wrong credential
     */
    public function testUserCannotLoginWithWrongCredentials()
    {
        $user = $this->user();
        $response = $this->post('api/v1/login', [
            'email' => $user->email,
            'password' => 'WrongPassWordTemp123!'
        ]);

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
        $response->assertJson(['message' => 'Login Gagal']);
        $response->assertJsonStructure(['message']);
    }

    /**
     * Test user login with wrong credential
     */
    public function testUserSendAnInvalidData()
    {
        $response = $this->post('api/v1/login', [
            'email' => '',
            'password' => ''
        ]);

        $response->assertStatus(JsonResponse::HTTP_FOUND);
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors('password');
    }
}
