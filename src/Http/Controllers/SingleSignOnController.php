<?php

namespace FennecTech\LaravelSingleSignOn\Http\Controllers;

use App\Models\User;
use FennecTech\LaravelSingleSignOn\Http\Responses\LogoutResponse;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SingleSignOnController extends Controller
{
    public function index()
    {
        return view('single-sign-on::index');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): LogoutResponse
    {
        $request->session()->invalidate();
        $this->logout($request);

        return app(LogoutResponse::class);
    }

    public static function logout($request)
    {
        $url = env('ACCOUNTS_URL').'/api/user/logout';
        $headerValue = $request->headers->get('Cookie');

        try {
            $response = Http::accept('application/json')
                ->withHeaders(['cookie' => $headerValue])
                ->timeout(3)
                ->post($url);
        } catch (ConnectionException $e) {
            return (object) ['status' => 'error', 'message' => __('API.connectionFailed')];
        }
        if (! $response->successful()) {
            return (object) ['status' => 'error', 'message' => $response->json()];
        }

        return true;
    }

    /**
     * Check if the user has already been logged in in ACCOUNTS APP
     * If he is login
     */
    public static function check_accounts_login($request): bool
    {
        $apiResponse = self::me($request);
        if (isset($apiResponse->status) && $apiResponse->status === 'error') {
            return false;
        }

        $userData = $apiResponse->user;
        // Check if the user exists, or create new user
        $user = self::get_or_create_user($userData);
        if (! $user) {
            return false;
        }

        // Login the user in Academy app

        Auth::login($user);

        return true;
    }

    /**
     * Get the user using data provided by Account APP API
     *
     * @param [User] $userData
     * @return void
     */
    private static function get_or_create_user($userData)
    {
        // return the user if exists
        if ($user = User::where('email', $userData->email)->first()) {

            // if user updated in accounts, update user data
            if (
                $user->first_name != $userData->first_name ||
                $user->last_name != $userData->last_name ||
                $user->personal_picture != url(env('ACCOUNT_URL').$userData->profile_image) ||
                $user->email_verified_at != $userData->email_verified_at
            ) {
                return self::update_user($userData);
            }

            // if there is no update, return the user's data
            return $user;
        }

        // create the user and assign student role
        return self::create_user($userData);
    }

    /**
     * Syncronize user data between Accounts and Academy
     *
     * @param [User] $userData
     */
    private static function update_user($userData): User
    {
        $user = User::where('email', $userData->email)->first();

        $user->first_name = $userData->first_name;
        $user->last_name = $userData->last_name;
        $user->email_verified_at = $userData->email_verified_at;
        $user->personal_picture = ! empty($userData->profile_image) ? url(env('ACCOUNT_URL').$userData->profile_image) : '';

        $user->save();

        return $user;
    }

    /**
     * Create new user using Accounts user data
     *
     * @param [User] $userData
     */
    private static function create_user($userData): User
    {
        $newUser = User::Create(
            [
                'first_name' => $userData->first_name,
                'last_name' => $userData->last_name,
                'email' => $userData->email,
                'password' => Hash::make(Str::random(12)),
                'email_verified_at' => $userData->email_verified_at,
                'personal_picture' => ! empty($userData->profile_image) ? env('ACCOUNTS_URL').'/'.$userData->profile_image : '',
            ]
        );
        $newUser->assignRole('Guest');

        return $newUser;
    }

    /**
     * The me function return a call to the accounts app API when the user is logged in
     *
     * @return object
     */
    public static function me($request)
    {
        $url = env('ACCOUNTS_URL').'/api/user/me';
        $headerValue = $request->headers->get('Cookie');

        try {
            $response = Http::accept('application/json')
                ->withHeaders(['cookie' => $headerValue])
                ->timeout(3)
                ->get($url);
        } catch (ConnectionException $e) {
            return (object) ['status' => 'error', 'message' => __('API.connectionFailed')];
        }
        if (! $response->successful()) {
            return (object) ['status' => 'error', 'message' => $response->json()];
        }

        return json_decode($response->body());
    }

    public static function user($request)
    {
        $url = env('ACCOUNTS_URL').'/api/user/data';
        $headerValue = $request->headers->get('Cookie');
        try {
            $response = Http::accept('application/json')
                ->withHeaders(['cookie' => $headerValue])
                ->withBody(json_encode(['email' => $request->email]), 'application/json')
                ->timeout(3)
                ->get($url);
        } catch (ConnectionException $e) {
            return (object) ['status' => 'error', 'message' => __('API.connectionFailed')];
        }
        if (! $response->successful()) {
            return (object) ['status' => 'error', 'message' => $response->json()];
        }

        return json_decode($response->body());
    }

    public static function redirect_to_accounts($intended_url)
    {
        return redirect()->to(url(env('ACCOUNT_APP').'/login?redirect='.urlencode($intended_url)));
    }
}
