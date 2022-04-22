<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\User\Entities\User;
use Illuminate\Support\Str;
use Modules\Kost\Entities\Kost;
use Modules\User\Constants\UserType;
use stdClass;

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

    protected function kost(int $count=1, $custom=null)
    {
        $user = $this->user();
        if (is_null($custom)) {
            $kost = Kost::factory($count)->create(['owner_id'=>$user->id])->all();
        } else {
            $kost = Kost::factory($count)->create([
                'owner_id' => $user->id,
                'name' => $custom->name,
                'address' => $custom->address,
                'price' => $custom->price,
                'slot' => $custom->slot
            ])->all();
        }

        return $kost;
    }
}
