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
            'upper_body' => 'required|boolean',
            'region' => 'required|string|max:255',
            'creator_id' => 'integer',
            'updater_id' => 'integer',
        ]);

        $bodyZone = new BodyZone(
            [
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'upper_body' => $request->get('upper_body'),
                'region' => $request->get('region'),
                'creator_id' => $request->get('creator_id'),
                'updater_id' => $request->get('updater_id'),
            ]
        );
        $bodyZone->save();

        return response()->json(['message' => 'Body zone created successfully.'], 201);
    }

    public function getAll()
    {
        $page = request('page') ?? 1;
        $limit = request('limit') ?? 10;

        $bodyZones = BodyZone::skip(($page - 1) * $limit)->take($limit)->get();
        return response()->json(['bodyZones' => $bodyZones]);
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

    public function getMedia($id) {
        $bodyZone = BodyZone::find($id);

        if ($bodyZone == null) {
            return response()->json(['message' => 'Body zone not found.'], 404);
        } else {
            $bodyZoneMedia = $bodyZone->media()->orderBy('order', 'asc')->get();

            return response()->json(['medias' => $bodyZoneMedia], 200);
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
            'name' => 'string|max:255',
            'description' => 'string|max:255',
            'upper_body' => 'boolean',
            'region' => 'string|max:255',
            'creator_id' => 'integer',
            'updater_id' => 'integer',
        ]);

        $bodyZone = BodyZone::find($id);

        if ($bodyZone == null) {
            return response()->json(['message' => 'Body zone not found.'], 404);
        } else {
            if ($request->get('name') != null) {
                $bodyZone->name = $request->get('name');
            }
            if ($request->get('description') != null) {
                $bodyZone->description = $request->get('description');
            }
            if ($request->get('upper_body') != null) {
                $bodyZone->upper_body = $request->get('upper_body');
            }
            if ($request->get('region') != null) {
                $bodyZone->region = $request->get('region');
            }
            if ($request->get('creator_id') != null) {
                $bodyZone->creator_id = $request->get('creator_id');
            }
            if ($request->get('updater_id') != null) {
                $bodyZone->updater_id = $request->get('updater_id');
            }


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
