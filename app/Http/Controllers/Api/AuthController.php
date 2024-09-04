<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AddUserRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Company;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * @group Auth management
 *
 * APIs for managing auth
 */
class AuthController extends Controller
{
    /**
     * Register
     *
     * Registra un Usuario. Si todo esta bien, retorna una respuesta 200 OK
     *
     * Si no retorna un error 422 que indica que hay errores en el body.
     * @bodyParam password_confirmation string The confirmed password. Example: password123
     * @response 422 scenario="El body tiene errores." {"message":"The password field confirmation does not match. (and 3 more errors)","errors":{"password":["The password field confirmation does not match."],"password_confirmation":["The password confirmation field is required."],"provider_id":["The selected provider id is invalid."],"company_id":["The selected company id is invalid."]}}
     * @responseField message Mensaje de error
     * @responseField errors Informacion detallada sobre los errores
     */
    // public function register(AddUserRequest $request) {
    //     $data = $request->validated();
    //     $data['email'] = strtolower($data['email']);
    //     $data['password'] = Hash::make($data['password']);
    //     unset($data['password_confirmation']);

    //     $user = User::create($data);

    //     $token = $user->createToken('authToken')->plainTextToken;

    //     if(isset($data['provider_id'])) {
    //         $provider = Provider::find($data['provider_id']);
    //         $provider->users()->attach([$user->id]);
    //     }

    //     if(isset($data['company_id'])) {
    //         $company = Company::find($data['company_id']);
    //         $company->users()->attach([$user->id]);
    //     }

    //     return response()->json([
    //         'message' => 'Cuenta creada.',
    //         'token' => $token,
    //     ]);
    // }

    /**
     * Login
     *
     * Check that the service is up. If everything is okay, you'll get a 200 OK response.
     *
     * Otherwise, the request will fail with a 400 error, and a response listing the failed services.
     *
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField status The status of this API (`up` or `down`).
     * @responseField services Map of each downstream service and their status (`up` or `down`).
     */
    public function login(LoginRequest $request) {
        $data = $request->validated();

        if (Auth::attempt(['email' => strtolower($data['email']), 'password' => $data['password']])) {
            $user = User::where('email', strtolower($data['email']))->firstOrFail();

            $token = $user->createToken('authToken')->plainTextToken;
            $user->token = $token;

            return response()->json([
                'message' => 'Sesion iniciada.',
                'data' => [
                    'token' => $token,
                    'user' => $user,
                ]
            ]);
        }

        return response()->json([
            'message' => 'Datos invalidos.'
        ], 401);
    }

}
