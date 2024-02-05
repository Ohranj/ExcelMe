<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\Captain\CreateCaptain;
use Illuminate\Auth\Events\Registered;
use App\Actions\Activity\CreateActivity;
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
    public function store(RegisterRequest $request, CreateOrganisation $createOrganisation, CreateCaptain $createCaptain, CreateActivity $createActivity): JsonResponse
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
        } catch (Exception $e) {
            Log::info(json_encode($e));
            DB::rollback();
            return $this->returnJson(false, [
                'message' => 'An error occured. If this persists, please contact our team.'
            ], 422);
        }

        DB::commit();

        event(new Registered($user));

        $createActivity->execute($user, $user, 'REGISTER', []);

        Auth::login($user);

        return $this->returnJson(true, ['message' => 'Account created'], 201);
    }
}
