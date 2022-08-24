<?php

namespace App\Http\Controllers;

use App\Models\BodyZone;
use App\Models\BodyZoneMedia;
use App\Models\Exercice;
use App\Models\ExerciceMedia;
use App\Models\Muscle;
use App\Models\MuscleMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function store(Request $request, $reference_type)
    {
        $request->validate([
            'reference_id' => 'required|integer',
            'media_type' => 'required|string|max:255',
            'media_path' => 'required|string|max:255',
            'creator_id' => 'integer',
            'updater_id' => 'integer',
        ]);

        if ($request->get('media_file')) {
            if ($request->get('media_type') == 'image') {
                $image_64 = $request->input('media_file');
                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
                $replace = substr($image_64, 0, strpos($image_64, ',')+1);
                $image = str_replace($replace, '', $image_64);
                $image = str_replace(' ', '+', $image);
                $imageName = $reference_type . '/' . Str::random(35) . '.' . $extension;

                $media_file = Storage::disk('public')->putFile($imageName, base64_decode($image));
            } else {
                return response()->json(['error' => 'Only images are allowed for now'], 400);
            }
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        if ($reference_type == 'body_zone') {
            $body_zone = BodyZone::find($request->get('reference_id'));
            $result = BodyZoneMedia::create(
                [
                    'body_zone_id' => $request->get('reference_id'),
                    'media_type' => $request->get('media_type'),
                    'media_path' => $media_file,
                    'creator_id' => $request->get('creator_id') ?? null,
                    'updater_id' => $request->get('updater_id') ?? null,
                    'order' => $body_zone->orderMedia(),
                ]
            );
        } else if ($reference_type == 'muscle') {
            $muscle = Muscle::find($request->get('reference_id'));
            $result = MuscleMedia::create(
                [
                    'muscle_id' => $request->get('reference_id'),
                    'media_type' => $request->get('media_type'),
                    'media_path' => $media_file,
                    'creator_id' => $request->get('creator_id') ?? null,
                    'updater_id' => $request->get('updater_id') ?? null,
                    'order' => $muscle->orderMedia(),
                ]
            );
        } else if ($reference_type == 'exercice') {
            $exercice = Exercice::find($request->get('reference_id'));
            $result = ExerciceMedia::create(
                [
                    'exercice_id' => $request->get('reference_id'),
                    'media_type' => $request->get('media_type'),
                    'media_path' => $media_file,
                    'creator_id' => $request->get('creator_id') ?? null,
                    'updater_id' => $request->get('updater_id') ?? null,
                    'order' => $exercice->orderMedia(),
                ]
            );
        } else {
            return response()->json(['error' => 'Invalid reference type'], 400);
        }

        $result->save();

        return response()->json(['success' => 'Media uploaded'], 200);
    }

    private function getResult($reference_type, $id)
    {
        if ($reference_type == 'body_zone') {
            $result = BodyZoneMedia::find($id);
        } else if ($reference_type == 'muscle') {
            $result = MuscleMedia::find($id);
        } else if ($reference_type == 'exercice') {
            $result = ExerciceMedia::find($id);
        } else {
            return false;
        }

        return $result;
    }

    public function changeOrder(Request $request, $id, $reference_type) {
        $result = $this->getResult($reference_type, $id);
        if ($result) {
            $result->order = $request->get('order');
            $result->save();
            return response()->json(['success' => 'Order changed'], 200);
        } else {
            return response()->json(['error' => 'Media not found'], 400);
        }
    }

    public function delete($id, $reference_type) {
        $result = $this->getResult($reference_type, $id);
        if ($result) {
            Storage::disk('public')->delete($result->media_path);
            $result->delete();
            return response()->json(['success' => 'Media deleted'], 200);
        } else {
            return response()->json(['error' => 'Media not found'], 404);
        }
    }
}
