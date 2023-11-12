<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Contents;
use App\Models\Activities;
use Illuminate\Support\Facades\Validator;

class ActivitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activities::with(['user', 'content'])->get();

        if (count($activities) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $activities
            ], 200);
        } else {
            return response([
                'message' => 'Empty',
                'data' => null
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_user' => 'required',
            'id_content' => 'required',
            'accessed_at' => 'required'
        ]);
        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $user = User::find($storeData['id_user']);

        if ($user == null) {
            return response([
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        $content = Contents::find($storeData['id_content']);
        if ($content == null) {
            return response([
                'message' => 'Content not found',
                'data' => null
            ], 404);
        }

        $activities = Activities::create($storeData);
        return response([
            'message' => $user->name . ' has accessed ' . $content->title . ' at ' . $activities->accessed_at,
            'data' => $activities,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $activity = Activities::find($id);

        if (!is_null($activity)) {
            return response([
                'message' => 'Retrieve Activity Success',
                'data' => $activity
            ], 200);
        } else {
            return response([
                'message' => 'Activity Not Found',
                'data' => null
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $updateData = $request->all();
        $activities = Activities::find($id);

        if (is_null($activities)) {
            return response([
                'message' => 'Activity Not Found',
                'data' => null
            ], 404);
        }

        $validate = Validator::make($updateData, [
            'id_user' => 'required',
            'id_content' => 'required',
        ]);
        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $user = User::find($updateData['id_user']);
        if (is_null($user)) {
            return response([
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        $content = Contents::find($updateData['id_content']);
        if (!$content) {
            return response([
                'message' => 'Content not found',
                'data' => null
            ], 404);
        }

        $activities->id_user = $updateData['id_user'];
        $activities->id_content = $updateData['id_content'];
        $activities->accessed_at = $updateData['accessed_at'];

        if ($activities->save()) {
            return response([
                'message' => 'Update Activity Success',
                'data' => $activities,
            ], 200);
        }

        return response([
            'message' => 'Update Activity Failed',
            'data' => null,
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activities = Activities::find($id);
        if (is_null($activities)) {
            return response([
                'message' => 'Activity Not Found',
                'data' => null
            ], 404);
        }

        if ($activities->delete()) {
            return response([
                'message' => 'Delete Activity Success',
                'data' => $activities,
            ], 200);
        }

        return response([
            'message' => 'Delete Activity Failed',
            'data' => null,
        ], 400);
    }
}
