<?php

namespace App\Http\Controllers;

use App\Models\Muscle;
use App\Models\User;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'weight' => 'integer',
            'will' => 'required|string|max:255',
        ]);

        $user = new User(
            [
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
                'weight' => $request->get('weight'),
                'will' => $request->get('will'),
            ]
        );

        $user->save();

        return response()->json($user, 201);
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
