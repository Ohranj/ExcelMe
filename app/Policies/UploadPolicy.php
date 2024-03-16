<?php

namespace App\Policies;

use App\Models\Upload;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UploadPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Upload $upload): Response
    {
        $isUploader = Upload::where('id', $upload->id)->whereBelongsTo($user, 'user')->exists();
        return $isUploader
            ? Response::allow()
            : Response::deny('We are unable to validate this action');
    }

    // /**
    //  * Determine whether the user can update the model.
    //  */
    // public function update(User $user, Upload $upload): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Upload $upload): bool
    {
        $isUploader = Upload::where('id', $upload->id)->whereBelongsTo($user, 'user')->exists();
        return $isUploader;
    }

    // /**
    //  * Determine whether the user can restore the model.
    //  */
    // public function restore(User $user, Upload $upload): bool
    // {
    //     //
    // }
}
