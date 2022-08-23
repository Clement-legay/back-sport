<?php

namespace App\Http\Controllers;

use App\Models\Muscle;
use Illuminate\Http\Request;

class MuscleController extends Controller
{
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
            'description' => 'required|string|max:255',
            'body_zone_id' => 'required|integer',
            'creator_id' => 'integer',
            'updater_id' => 'integer',
        ]);

        $muscle = new Muscle(
            [
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'body_zone_id' => $request->get('body_zone_id'),
                'creator_id' => $request->get('creator_id'),
                'updater_id' => $request->get('updater_id'),
            ]
        );

        $muscle->save();

        return response()->json(['message' => 'Muscle created successfully.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $muscle = Muscle::find($id);
        if ($muscle == null) {
            return response()->json(['message' => 'Muscle not found.'], 404);
        } else {
            return response()->json(['muscle' => $muscle], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $muscle = Muscle::find($id);

        if ($muscle == null) {
            return response()->json(['message' => 'Muscle not found.'], 404);
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'body_zone_id' => 'required|integer',
                'creator_id' => 'integer',
                'updater_id' => 'integer',
            ]);

            $muscle->update(
                [
                    'name' => $request->get('name'),
                    'description' => $request->get('description'),
                    'body_zone_id' => $request->get('body_zone_id'),
                    'creator_id' => $request->get('creator_id'),
                    'updater_id' => $request->get('updater_id'),
                ]
            );

            $muscle->save();

            return response()->json(['message' => 'Muscle updated successfully.'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $muscle = Muscle::find($id);

        if ($muscle == null) {
            return response()->json(['message' => 'Muscle not found.'], 404);
        } else {
            $muscle->delete();

            return response()->json(['message' => 'Muscle deleted successfully.'], 200);
        }
    }
}
