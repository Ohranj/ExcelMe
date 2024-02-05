<?php

namespace App\Actions\Activity;

use App\Models\Activity;

class CreateActivity
{
    public function execute($assetable, $actionable, string $reference, array $meta): void
    {
        $activity = new Activity();
        $activity->assetable()->associate($assetable);
        $activity->actionable()->associate($actionable);
        $activity->activity = $reference;
        $activity->meta_data = $meta;
        $activity->save();
    }
}
