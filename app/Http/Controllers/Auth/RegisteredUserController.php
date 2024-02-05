<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use App\Models\Activity;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\Captain\CreateCaptain;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\RegisterRequest;
use App\Actions\Organisation\CreateOrganisation;

class RegisteredUserController extends Controller
{
    use ResponseTrait;

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request, CreateOrganisation $createOrganisation, CreateCaptain $createCaptain): JsonResponse
    {
        $params = [...$request->all()];

        $setOrganisation = $request->shouldSetOrganisation();


        DB::beginTransaction();

        try {
            if ($setOrganisation) {
                $name = $request->validated()['organisation'];
                $organisation = $createOrganisation->execute($name);
                $params['organisation_id'] = $organisation->id;
            }

            $user = User::create($params);

            if ($setOrganisation) {
                $createCaptain->execute($user);
            }

            event(new Registered($user));
        } catch (Exception $e) {
            DB::rollback();
            return $this->returnJson(false, [
                'message' => 'An occured on. If this persists, please contact our team.'
            ], 422);
        }

        DB::commit();

        $activity = new Activity();
        $activity->assetable()->associate($user);
        $activity->actionable()->associate($user);
        $activity->activity = 'REGISTER';
        $activity->meta_data = json_encode('[]');
        $activity->save();

        // Auth::login($user);

        return $this->returnJson(true, ['message' => 'Account created'], 201);
    }
}
