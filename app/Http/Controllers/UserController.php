<?php

namespace App\Http\Controllers;

use App\Models\Muscle;
use App\Models\User;
use App\Models\VerificationToken;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function verify(Request $request)
    {
        $user = User::find($request->get('user_id'));
        $verificationToken = $user->verificationToken()->first();
        $token = $request->get('token');

        if ($verificationToken->isValid()) {
            if ($verificationToken->token === $token) {
                $user->email_verified_at = now();
                $user->save();
                $verificationToken->delete();
                return response()->json(['message' => 'Account verified successfully.']);
            } else {
                return response()->json(['message' => 'Invalid token.'], 401);
            }
        } else {
            $verificationToken->delete();
            $user->sendVerificationEmail();
            return response()->json(['message' => 'Token expired, new token sent to your email.'], 401);
        }

    }

    public function rememberTokenCheck(Request $request)
    {
        $rememberToken = $request->get('remember_token');
        if ($rememberToken) {
            $rememberToken = JWT::decode($rememberToken, new Key(env('JWT_SECRET'), 'HS256'));

            if ($rememberToken->exp > time()) {
                $user = User::where('remember_token', $rememberToken->token)->first();
                $user->generateRememberToken();

                if ($user->email_verified_at == null) {
                    $verificationToken = $user->verificationToken()->first();
                    if (!$verificationToken->isValid()) {
                        $verificationToken->delete();
                        $user->sendVerificationEmail();
                    }
                }

                return response()->json(['user' => $user, 'remember_token' => $user->rememberToken()]);
            }
        }
        return response()->json(['message' => 'token is invalid'], 401);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'weight' => 'integer',
            'will' => 'string|max:255',
        ]);

        $user = User::create(
            [
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
                'weight' => $request->get('weight'),
                'will' => $request->get('will'),
            ]
        );

        $user->sendVerificationEmail();

        $user->generateRememberToken();
        $token = $user->rememberToken();

        $user->save();

        return response()->json(['user' => $user, 'remember_token', $token], 201);
    }

    public function getAll()
    {
        $page = request('page') ?? 1;
        $limit = request('limit') ?? 10;

        $users = User::skip(($page - 1) * $limit)->take($limit)->get();
        return response()->json(['users' => $users]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials, true)) {
            $user = auth()->user();

            if ($user->email_verified_at == null) {
                $verificationToken = $user->verificationToken()->first();

                if (!$verificationToken->isValid()) {
                    $verificationToken->delete();
                    $user->sendVerificationEmail();
                }
            }

            $token = $user->rememberToken();

            return response()->json([
                'user' => $user,
                'remember_token' => $token,
            ]);

        } else {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Muscle  $muscle
     * @return \Illuminate\Http\Response
     */
    public function show(Muscle $muscle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Muscle  $muscle
     * @return \Illuminate\Http\Response
     */
    public function edit(Muscle $muscle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if ($user == null) {
            return response()->json(['message' => 'User not found.'], 404);
        } else {
            $request->validate([
                'name' => 'string|max:255',
                'email' => 'string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'sometimes|string|min:6|confirmed',
                'weight' => 'integer',
                'will' => 'string|max:255',
            ]);

            if ($request->get('name') != null) {
                $user->name = $request->get('name');
            }
            if ($request->get('email') != null) {
                $user->email = $request->get('email');
            }
            if ($request->get('password') != null) {
                $user->password = bcrypt($request->get('password'));
            }
            if ($request->get('weight') != null) {
                $user->weight = $request->get('weight');
            }
            if ($request->get('will') != null) {
                $user->will = $request->get('will');
            }

            $user->save();

            return response()->json($user, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user == null) {
            return response()->json(['message' => 'User not found.'], 404);
        } else {
            $user->delete();

            return response()->json(['message' => 'User deleted.'], 200);
        }
    }
}
