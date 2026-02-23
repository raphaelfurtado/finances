<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Algo deu errado com o login via Google.');
        }

        $user = User::updateOrCreate([
            'email' => $googleUser->getEmail(),
        ], [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'password' => null, // Opcional: senhas são nulas para login social se não definidas
        ]);

        if ($user->wasRecentlyCreated) {
            \App\Services\CategoryTemplateService::applyPersonalFinancesTemplate($user);
        }

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }

    /**
     * Handle Google JS Login (Credential Request)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function googleJsLogin(Request $request)
    {
        $token = $request->input('credential');

        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token não fornecido.'], 400);
        }

        $client = new \Google\Client(['client_id' => config('services.google.client_id')]);
        $payload = $client->verifyIdToken($token);

        if ($payload) {
            $email = $payload['email'];
            $name = $payload['name'];
            $googleId = $payload['sub'];
            $avatar = $payload['picture'] ?? null;

            $user = User::updateOrCreate([
                'email' => $email,
            ], [
                'name' => $name,
                'google_id' => $googleId,
                'avatar' => $avatar,
                'password' => null,
            ]);

            if ($user->wasRecentlyCreated) {
                \App\Services\CategoryTemplateService::applyPersonalFinancesTemplate($user);
            }

            Auth::login($user);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Falha na validação do token.'], 401);
    }
}
