<?php

namespace App\Actions\Captain;

use App\Models\User;
use App\Models\Captain;

class CreateCaptain
{
    public function execute(User $user): void
    {
        $captain = new Captain();
        $captain->user_id = $user->id;
        $captain->save();
    }
}
