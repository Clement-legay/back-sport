<?php

namespace App\Http\Controllers;

use App\Models\Exercice;
use App\Models\Muscle;
use Illuminate\Http\Request;

class ExerciceController extends Controller
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'fat_burn' => 'integer',
            'level' => 'integer',
            'thumbnail' => 'required',
            'type' => 'required|string|max:255',
            'muscles' => 'required',
        ]);

        $imageName = MediaController::setMedia($request->get('thumbnail'), 'thumbnails/exercice');

        $exercice = new Exercice(
            [
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'fat_burn' => $request->get('fat_burn'),
                'thumbnail_path' => $imageName,
                'level' => $request->get('level'),
                'type' => $request->get('type'),
            ]
        );

        $exercice->save();

        foreach (json_decode($request->get('muscles'), true) as $muscle) {
            $muscle = Muscle::find($muscle);
            $exercice->assignToMuscle($muscle);
        }

        return response()->json(['message' => 'Body zone created successfully.'], 201);
    }

    public function getAll()
    {
        $page = request('page') ?? 1;
        $limit = request('limit') ?? 10;

        $exercices = Exercice::skip(($page - 1) * $limit)->take($limit)->get();
        return response()->json(['workouts' => $exercices]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $exercice = Exercice::find($id);
        if ($exercice == null) {
            return response()->json(['message' => 'Exercice not found.'], 404);
        } else {
            return response()->json(['exercice' => $exercice], 200);
        }
    }

    public function getMedia($id)
    {
        $exercice = Exercice::find($id);
        if ($exercice == null) {
            return response()->json(['message' => 'Exercice not found.'], 404);
        } else {
            $media = $exercice->media()->orderBy('order', 'asc')->get();

            foreach ($media as $mediaItem) {
                $mediaItem->media_path = asset('storage/' . $mediaItem->media_path);
            }

            return response()->json(['medias' => $media], 200);
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
            'fat_burn' => 'integer',
            'level' => 'integer',
            'type' => 'string|max:255',
        ]);

        $exercice = Exercice::find($id);

        if ($exercice == null) {
            return response()->json(['message' => 'Exercice not found.'], 404);
        } else {
            if ($request->get('name') != null) {
                $exercice->name = $request->get('name');
            }
            if ($request->get('description') != null) {
                $exercice->description = $request->get('description');
            }
            if ($request->get('fat_burn') != null) {
                $exercice->fat_burn = $request->get('fat_burn');
            }
            if ($request->get('level') != null) {
                $exercice->level = $request->get('level');
            }
            if ($request->get('type') != null) {
                $exercice->type = $request->get('type');
            }
            if ($request->get('thumbnail') != null) {
                $imageName = MediaController::setMedia($request->get('thumbnail'), 'thumbnails/exercice');

                if ($imageName) {
                    unlink(public_path('storage/' . $exercice->thumbnail_path));
                    $exercice->thumbnail_path = $imageName;
                }
            }

            $exercice->discardMuscles();
            foreach (json_decode($request->get('muscles')) as $muscle) {
                $muscle = Muscle::find($muscle);
                $exercice->assignToMuscle($muscle);
            }

            $exercice->save();

            if (isset($imageName) && !$imageName) {
                return response()->json(['message' => 'Exercice updated successfully but thumbnail update failed.'], 200);
            } else {
                return response()->json(['message' => 'Exercice updated successfully.'], 200);
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
        $exercice = Exercice::find($id);
        if ($exercice == null) {
            return response()->json(['message' => 'Exercice not found.'], 404);
        } else {
            unlink(public_path('storage/' . $exercice->thumbnail_path));
            foreach ($exercice->media as $media) {
                unlink(public_path('storage/' . $media->media_path));
            }

            $exercice->delete();
            return response()->json(['message' => 'Exercice deleted successfully.'], 200);
        }
    }

    public function muscles($id)
    {
        $exercice = Exercice::find($id);

        return response()->json(['muscles' => $exercice->muscles()->get()], 200);
    }
}
