<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Captain\CreateCaptain;
use App\Actions\Organisation\CreateOrganisation;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\RegisterRequest;

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

        //Wrap in db transaction if one of the 3 queries fails then dont store to db

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

        // Auth::login($user);

        return $this->returnJson(true, ['message' => 'Account created'], 201);
    }
}
