<?php

namespace App\Http\Controllers;

use App\Models\BodyZone;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'thumbnail' => 'required',
            'creator_id' => 'integer',
            'updater_id' => 'integer',
        ]);

        $imageName = MediaController::setMedia($request->get('thumbnail'), 'thumbnails/body_zone');

        $bodyZone = new BodyZone(
            [
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'upper_body' => $request->get('upper_body'),
                'region' => $request->get('region'),
                'thumbnail_path' => $imageName,
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
            $media = $bodyZone->media()->orderBy('order', 'asc')->get();
            foreach ($media as $mediaItem) {
                $mediaItem->media_path = asset('storage/' . $mediaItem->media_path);
            }
            return response()->json(['medias' => $media], 200);
        }
    }

    public function getMuscles($id) {
        $bodyZone = BodyZone::find($id);

        if ($bodyZone == null) {
            return response()->json(['message' => 'Body zone not found.'], 404);
        } else {
            $muscles = $bodyZone->muscles()->orderBy('updated_at', 'desc')->get();
            return response()->json(['muscles' => $muscles], 200);
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
            if ($request->get('thumbnail') != null) {
                $imageName = MediaController::setMedia($request->get('thumbnail'), 'thumbnails/body_zone');

                if ($imageName) {
                    unlink(public_path('storage/' . $bodyZone->thumbnail_path));
                    $bodyZone->thumbnail_path = $imageName;
                }
            }

            $bodyZone->save();

            if (isset($imageName) && !$imageName) {
                return response()->json(['message' => 'Body zone updated successfully but thumbnail update failed.'], 200);
            } else {
                return response()->json(['message' => 'Body zone updated successfully.'], 200);
            }
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
            unlink(public_path('storage/' . $bodyZone->thumbnail_path));
            foreach ($bodyZone->media as $media) {
                unlink(public_path('storage/' . $media->media_path));
            }

            $bodyZone->delete();

            return response()->json(['message' => 'Body zone deleted successfully.'], 200);
        }
    }
}
