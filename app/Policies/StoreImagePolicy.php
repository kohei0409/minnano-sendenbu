<?php

namespace App\Policies;

use App\Models\StoreImage;
use App\Models\User;

class StoreImagePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StoreImage $storeImage): bool
    {
        return $user->store_id === $storeImage->store_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StoreImage $storeImage): bool
    {
        return $user->store_id === $storeImage->store_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StoreImage $storeImage): bool
    {
        return $user->store_id === $storeImage->store_id;
    }
}
