<?php

namespace App\Traits;

trait ResponseTrait
{
    public function returnJson($state = false, $data = [], $status = 422)
    {
        return response()->json(['success' => $state, ...$data], $status);
    }
}
