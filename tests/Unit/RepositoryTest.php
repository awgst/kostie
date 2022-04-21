<?php

namespace Tests\Unit;

use App\Repository\Test\TestRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Constants\UserType;
use Tests\TestCase;

class RepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test filtering
     */
    public function testRepositoryFilterIsWorkingProperly()
    {
        $this->user(UserType::OWNER);
        $this->user(UserType::OWNER);
        $repository = app(TestRepository::class);
        $owner = $repository->filter(['type' => UserType::OWNER])->fetch();
        $this->assertTrue($owner->count() == 2);
    }
}
