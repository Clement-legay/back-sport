<?php

namespace App\Http\Controllers;

use App\Models\BodyZone;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;

class BodyZoneController extends Controller
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
            'description' => 'required|string|max:255',
        ]);

        $bodyZone = new BodyZone(
            [
                'name' => $request->get('name'),
                'description' => $request->get('description'),
            ]
        );
        $bodyZone->save();

        return response()->json(['message' => 'Body zone created successfully.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $bodyZone = BodyZone::find($id);
        if ($bodyZone == null) {
            return response()->json(['message' => 'Body zone not found.'], 404);
        } else {
            return response()->json(['bodyZone' => $bodyZone], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $bodyZone = BodyZone::find($id);

        if ($bodyZone == null) {
            return response()->json(['message' => 'Body zone not found.'], 404);
        } else {
            $bodyZone->update(
                [
                    'name' => $request->get('name'),
                    'description' => $request->get('description'),
                ]
            );

            $bodyZone->save();

            return response()->json(['message' => 'Body zone updated successfully.'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $bodyZone = BodyZone::find($id);

        if ($bodyZone == null) {
            return response()->json(['message' => 'Body zone not found.'], 404);
        } else {
            $bodyZone->delete();

            return response()->json(['message' => 'Body zone deleted successfully.'], 200);
        }
    }
}
