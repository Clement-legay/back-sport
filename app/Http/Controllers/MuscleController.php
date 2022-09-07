<?php

namespace App\Http\Controllers;

use App\Models\Muscle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'thumbnail' => 'required',
            'creator_id' => 'integer',
            'updater_id' => 'integer',
        ]);

        $imageName = MediaController::setMedia($request->get('thumbnail'), 'thumbnails/muscle');

        $muscle = new Muscle(
            [
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'body_zone_id' => $request->get('body_zone_id'),
                'thumbnail_path' => $imageName,
                'creator_id' => $request->get('creator_id'),
                'updater_id' => $request->get('updater_id'),
            ]
        );

        $muscle->save();

        return response()->json(['message' => 'Muscle created successfully.'], 201);
    }

    public function getAll()
    {
        $page = request('page') ?? 1;
        $limit = request('limit') ?? 10;

        $muscles = Muscle::skip(($page - 1) * $limit)->take($limit)->get();
        return response()->json(['muscles' => $muscles]);
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

    public function getMedia($id)
    {
        $muscle = Muscle::find($id);

        if ($muscle == null) {
            return response()->json(['message' => 'Muscle not found.'], 404);
        } else {
            $media = $muscle->media()->orderBy('created_at', 'desc')->get();
            foreach ($media as $mediaItem) {
                $mediaItem->media_path = asset('storage/' . $mediaItem->media_path);
            }
            return response()->json(['medias' => $media], 200);
        }
    }

    public function getWorkouts($id)
    {
        $muscle = Muscle::find($id);

        if ($muscle == null) {
            return response()->json(['message' => 'Muscle not found.'], 404);
        } else {
            $workouts = $muscle->exercices()->get();
            return response()->json(['workouts' => $workouts], 200);
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

            if ($request->get('name') != null) {
                $muscle->name = $request->get('name');
            }
            if ($request->get('description') != null) {
                $muscle->description = $request->get('description');
            }
            if ($request->get('body_zone_id') != null) {
                $muscle->body_zone_id = $request->get('body_zone_id');
            }
            if ($request->get('creator_id') != null) {
                $muscle->creator_id = $request->get('creator_id');
            }
            if ($request->get('updater_id') != null) {
                $muscle->updater_id = $request->get('updater_id');
            }
            if ($request->get('thumbnail') != null) {
                $imageName = MediaController::setMedia($request->get('thumbnail'), 'thumbnails/muscle');

                if ($imageName) {
                    unlink(public_path('storage/' . $muscle->thumbnail_path));
                    $muscle->thumbnail_path = $imageName;
                }
            }

            $muscle->save();

            if (isset($imageName) && !$imageName) {
                return response()->json(['message' => 'Muscle updated successfully but thumbnail update failed.'], 200);
            } else {
                return response()->json(['message' => 'Muscle updated successfully.'], 200);
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
        $muscle = Muscle::find($id);

        if ($muscle == null) {
            return response()->json(['message' => 'Muscle not found.'], 404);
        } else {
            unlink(public_path('storage/' . $muscle->thumbnail_path));
            foreach ($muscle->media as $media) {
                unlink(public_path('storage/' . $media->media_path));
            }

            $muscle->delete();

            return response()->json(['message' => 'Muscle deleted successfully.'], 200);
        }
    }
}
