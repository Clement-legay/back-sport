<?php

namespace App\Http\Controllers;

use App\Models\Muscle;
use App\Models\Programme;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
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
        $request->validate(
            [
                // between 1 and 7
                'days_in_week' => 'required|integer|between:1,7',
                'focus' => 'required|string|max:255',
                'exercices' => 'required|array',
                'duration_goal' => 'required|integer',
                'user_id' => 'required|integer',
            ]
        );

        $programme = new Programme(
            [
                'days_in_week' => $request->get('days_in_week'),
                'focus' => $request->get('focus'),
                'exercices' => $request->get('exercices'),
                'duration_goal' => $request->get('duration_goal'),
                'user_id' => $request->get('user_id'),
            ]
        );

        $programme->save();

        return response()->json(['message' => 'Programme created successfully.'], 201);
    }

    public function getAll()
    {
        $page = request('page') ?? 1;
        $limit = request('limit') ?? 10;

        $programmes = Programme::skip(($page - 1) * $limit)->take($limit)->get();
        return response()->json(['programmes' => $programmes]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $programme = Programme::find($id);

        if ($programme == null) {
            return response()->json(['message' => 'Programme not found.'], 404);
        } else {
            return response()->json(['programme' => $programme], 200);
        }
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
        $programme = Programme::find($id);

        if ($programme == null) {
            return response()->json(['message' => 'Programme not found.'], 404);
        } else {
            $request->validate(
                [
                    'days_in_week' => 'integer|between:1,7',
                    'focus' => 'string|max:255',
                    'exercices' => 'array',
                    'duration_goal' => 'integer',
                ]
            );

            if ($request->get('days_in_week') != null) {
                $programme->days_in_week = $request->get('days_in_week');
            }
            if ($request->get('focus') != null) {
                $programme->focus = $request->get('focus');
            }
            if ($request->get('exercices') != null) {
                $programme->exercices = $request->get('exercices');
            }
            if ($request->get('duration_goal') != null) {
                $programme->duration_goal = $request->get('duration_goal');
            }

            $programme->save();

            return response()->json(['message' => 'Programme updated successfully.'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $programme = Programme::find($id);

        if ($programme == null) {
            return response()->json(['message' => 'Programme not found.'], 404);
        } else {
            $programme->delete();

            return response()->json(['message' => 'Programme deleted successfully.'], 200);
        }
    }
}
