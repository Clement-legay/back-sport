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

    public function verify($token): \Illuminate\Http\RedirectResponse
    {
        $verificationToken = VerificationToken::where('token', base64_decode($token))->first();


        if ($verificationToken->isValid()) {
            $user = User::where('remember_token', )->first();

            if ($user) {
                if ($user->email_verified_at == null) {
                $user->email_verified_at = now();
                $user->save();
                dd('your account is verified');
                } else {
                    dd('your account is already verified');
                }
            }

            dd('user not found');
        } else {
            dd('token expired');
        }

    }

    public function rememberTokenCheck(Request $request)
    {
        $rememberToken = $request->get('remember_token');
        if ($rememberToken) {
            $rememberToken = JWT::decode($rememberToken, new Key(env('JWT_SECRET'), 'HS256'));

            if ($rememberToken->exp > time()) {
                $user = User::where('remember_token', $rememberToken->token)->first();
                // generate a new remember token
                $user->generateRememberToken();
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
        ]);

        return response()->json(['data' => $request->all()]);


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
                return response()->json(['message' => 'Please verify your email.'], 401);
            } else {
                $token = $user->rememberToken();

                return response()->json([
                    'user' => $user,
                    'remember_token' => $token,
                ]);
            }
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
