<?php

namespace Modules\Kost\Policies;

use Exception;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Kost\Entities\Kost;
use Modules\Kost\Exceptions\InvalidUserTypeException;
use Modules\Kost\Exceptions\OutOfCreditException;
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
    public function update(User $user, Kost $kost)
    {
        if ($user->type != UserType::OWNER) {
            throw new InvalidUserTypeException("Anda tidak dapat mengubah kos karena anda bukan seorang pemilik");
        }

        if ($user->id != $kost->owner_id) {
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

    /**
     * Ask availability kost
     * @param User $user
     */
    public function askAvailability(User $user, Kost $kost)
    {
        if ($user->credit == 0 || ($user->credit - 5) <= 0) {
            throw new OutOfCreditException('Kredit anda tidak cukup untuk menanyakan ketersediaan kos');
        }

        if ($kost->slot <= 0) {
            throw new Exception('Kost sudah penuh');
        }
        
        return true;
    }
}
