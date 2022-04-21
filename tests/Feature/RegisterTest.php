<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Modules\User\Constants\UserType;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Register as a regular member
     */
    public function testUserCanRegisterAsRegularMember()
    {
        $response = $this->json('post', 'api/v1/register', [
            'name' => 'Regular User',
            'email' => 'regular_user@test.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(JsonResponse::HTTP_CREATED);
        $response->assertSessionHasNoErrors();
        $response->assertJson([
            'user' => [
                'type' => UserType::REGULAR,
                'credit' => UserType::credits(UserType::REGULAR)
            ]
        ]);
    }

    /**
     * Register as a premium member
     */
    public function testUserCanRegisterAsPremiumMember()
    {
        $response = $this->json('post', 'api/v1/register', [
            'name' => 'Premium User',
            'email' => 'premium_user@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'type' => UserType::PREMIUM
        ], ['Accept' => 'application/json']);

        $response->assertStatus(JsonResponse::HTTP_CREATED);
        $response->assertSessionHasNoErrors();
        $response->assertJson([
            'user' => [
                'type' => UserType::PREMIUM,
                'credit' => UserType::credits(UserType::PREMIUM)
            ]
        ]);
    }

    /**
     * Register as a owner member
     */
    public function testUserCanRegisterAsOwner()
    {
        $response = $this->json('post', 'api/v1/owner/register', [
            'name' => 'Owner',
            'email' => 'owner@test.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(JsonResponse::HTTP_CREATED);
        $response->assertSessionHasNoErrors();
        $response->assertJson([
            'user' => [
                'type' => UserType::OWNER,
                'credit' => UserType::credits(UserType::OWNER)
            ]
        ]);
    }

    /**
     * Register with a invalid data
     */
    public function testUserRegisterWithInvalidData()
    {
        $response = $this->json('post', 'api/v1/owner/register', [
            'name' => '',
            'email' => '',
            'password' => '123456',
            'password_confirmation' => ''
        ], ['Accept' => 'application/json']);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'email',
                'password'
            ]
        ]);
    }
}
