<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Actions\Activity\CreateActivity;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    use ResponseTrait;

    public function index(): mixed
    {
        $hasRememberMe = Auth::hasValidRememberMeCookie();

        if ($hasRememberMe['state']) {
            $user = $hasRememberMe['user'];
            Auth::loginUsingId($user->id);
            return redirect()->route('dashboard');
        }

        return view('welcome');
    }

    /**
     *
     */
    public function store(LoginRequest $request, CreateActivity $createActivity): JsonResponse
    {
        $isThrottled = $request->checkIsThrottled();
        if ($isThrottled['state']) {
            return response()->json(['success' => false, 'message' => $isThrottled['message']], 429);
        }

        if (!Auth::attempt($request->validated(), $request->remember_me)) {
            $request->hitThrottleKey();
            return response()->json(['success' => false, 'message' => 'Invalid credentials. Please check and try again.'], 422);
        }

        $request->clearThrottleKey();

        /** @var User $user */
        $user = Auth::user();
        $createActivity->execute($user, $user, 'LOGIN', []);

        return $this->returnJson(true, ['message' => 'Account authenticated'], 202);
    }

    /**
     *
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        return redirect('/');
    }
}
