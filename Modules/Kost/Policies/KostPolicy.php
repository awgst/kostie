<?php

namespace Modules\Kost\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Kost\Entities\Kost;
use Modules\Kost\Exceptions\InvalidUserTypeException;
use Modules\User\Constants\UserType;
use Modules\User\Entities\User;

class KostPolicy
{
    use HandlesAuthorization;

    /**
     * Store kost
     * @param User $user
     */
    public function store(User $user)
    {
        if ($user->type != UserType::OWNER) {
            throw new InvalidUserTypeException("Anda tidak dapat menambah kos karena anda bukan seorang pemilik");
        }
        
        return true;
    }

    /**
     * Update kost
     * @param User $user
     */
    public function update(User $user)
    {
        if ($user->type != UserType::OWNER) {
            throw new InvalidUserTypeException("Anda tidak dapat mengubah kos karena anda bukan seorang pemilik");
        }
        
        return true;
    }

    /**
     * Delete kost
     * @param User $user
     */
    public function destroy(User $user, Kost $kost)
    {
        if ($user->type != UserType::OWNER) {
            throw new InvalidUserTypeException("Anda tidak dapat menghapus kos karena anda bukan seorang pemilik");
        }
        
        if ($user->id != $kost->owner_id) {
            throw new InvalidUserTypeException("Anda tidak dapat menghapus kos karena anda bukan seorang pemilik");
        }
        
        return true;
    }
}
