<?php

namespace Modules\User\Repositories;

use App\Repository\Repository;
use Illuminate\Support\Facades\DB;
use Modules\User\Constants\UserType;
use Modules\User\Entities\User;

class UserRepository extends Repository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * Recharge all user credit
     */
    public function recharge()
    {
        return DB::update(
            DB::raw("
                UPDATE users SET credit = (CASE type WHEN ".UserType::REGULAR." THEN ".UserType::credits(UserType::REGULAR)
                ." WHEN ".UserType::PREMIUM." THEN ".UserType::credits(UserType::PREMIUM)." END) 
                WHERE type in (".UserType::REGULAR.",".UserType::PREMIUM.")"
            )
        );
        
    }
}