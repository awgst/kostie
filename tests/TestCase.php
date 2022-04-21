<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\User\Entities\User;
use Illuminate\Support\Str;
use Modules\User\Constants\UserType;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    protected function user(int $type = UserType::REGULAR)
    {
        $password = Str::random(8);
        $user = User::factory(1)->create([
            'password' => bcrypt($password),
            'type' => $type
        ])->first();
        
        $this->password = $password;
        return $user;
    }
}
