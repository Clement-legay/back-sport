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
            'type' => 'required|string|max:255',
            'muscles' => 'required',
        ]);

        $exercice = new Exercice(
            [
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'fat_burn' => $request->get('fat_burn'),
                'level' => $request->get('level'),
                'type' => $request->get('type'),
            ]
        );

        $exercice->save();

        foreach ($request->get('muscles') as $muscle) {
            $muscle = Muscle::find($muscle);
            $exercice->muscles()->attach($muscle);
        }

        return response()->json(['message' => 'Body zone created successfully.'], 201);
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
            'fat_burn' => 'required|integer',
            'level' => 'required|integer',
            'type' => 'required|string|max:255',
            'muscles' => 'required|array',
        ]);
        $exercice = Exercice::find($id);
        if ($exercice == null) {
            return response()->json(['message' => 'Exercice not found.'], 404);
        } else {
            $exercice->name = $request->get('name');
            $exercice->description = $request->get('description');
            $exercice->fat_burn = $request->get('fat_burn');
            $exercice->level = $request->get('level');
            $exercice->type = $request->get('type');
            $exercice->save();
            $exercice->muscles()->detach();
            foreach ($request->get('muscles') as $muscle) {
                $exercice->muscles()->attach($muscle);
            }
            return response()->json(['message' => 'Exercice updated successfully.'], 200);
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
            $exercice->delete();
            return response()->json(['message' => 'Exercice deleted successfully.'], 200);
        }
    }
}
