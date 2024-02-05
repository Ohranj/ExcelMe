<?php

namespace App\Actions\Organisation;

use App\Models\Organisation;

class CreateOrganisation
{
    public function execute(string $name): Organisation
    {
        $organisation = new Organisation();
        $organisation->name = $name;
        $organisation->save();
        return $organisation;
    }
}
