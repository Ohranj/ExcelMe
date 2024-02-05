<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    use ResponseTrait;

    /**
     *
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $isThrottled = $request->checkIsThrottled();
        if ($isThrottled['state']) {
            return response()->json(['success' => false, 'message' => $isThrottled['message']], 429);
        }

        if (!Auth::attempt($request->validated())) {
            $request->hitThrottleKey();
            return response()->json(['success' => false, 'message' => 'Invalid credentials. Please check and try again.'], 422);
        }

        $request->clearThrottleKey();

        // $activity = $this->createActivityArray(Auth::user());
        // $storeActivity->execute($activity);

        return $this->returnJson(true, ['message' => 'Account authenticated'], 202);
    }

    /**
     *
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * 
     */
    private function createActivity(User $user)
    {
        return [
            'activity' => 'LOGIN',
            'actionerable' => $user,
            'assetable' => $user
        ];
    }
}
