<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpecialistResource;
use App\Models\Specialist;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SpecialistController extends Controller
{
    /**
     * Display a listing of the active specialists.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $specialists = Specialist::where('active', true)->get();
        return SpecialistResource::collection($specialists);
    }

    /**
     * Display the specified specialist.
     *
     * @param int $id
     * @return SpecialistResource
     */
    public function show($id)
    {
        $specialist = Specialist::findOrFail($id);
        return new SpecialistResource($specialist);
    }

    /**
     * Store a newly created specialist in storage.
     *
     * @param Request $request
     * @return SpecialistResource
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $salon_id = $user->salon_id; // Assuming user has a salon_id

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|string',
            'working_hours' => 'nullable|json',
            'active' => 'sometimes|boolean',
        ]);

        $specialist = new Specialist($validated);
        $specialist->salon_id = $salon_id;
        $specialist->save();

        return new SpecialistResource($specialist);
    }

    /**
     * Update the specified specialist in storage.
     *
     * @param Request $request
     * @param int $id
     * @return SpecialistResource
     */
    public function update(Request $request, $id)
    {
        $specialist = Specialist::findOrFail($id);
        $user = $request->user();

        if ($specialist->salon_id !== $user->salon_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'position' => 'sometimes|string',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|string',
            'working_hours' => 'nullable|json',
            'active' => 'sometimes|boolean',
        ]);

        $specialist->update($validated);

        return new SpecialistResource($specialist);
    }

    /**
     * Remove the specified specialist from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $specialist = Specialist::findOrFail($id);
        $user = request()->user();

        if ($specialist->salon_id !== $user->salon_id) {
            abort(403, 'Unauthorized');
        }

        $specialist->delete();

        return response()->json(['message' => 'Specialist deleted']);
    }
}
