<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SalonController extends Controller
{
    /**
     * Display a listing of salons.
     *
     * @return JsonResponse
     */
    public function index()
    {
        // Retrieve all active salons
        $salons = Salon::where('active', true)->get();

        return response()->json($salons);
    }

    /**
     * Store a newly created salon in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $data = $request->validate([
            'owner_id'        => 'required|integer|exists:users,id',
            'slug'            => 'required|string|unique:salons,slug',
            'name'            => 'required|string|max:255',
            'translations'    => 'nullable|array',
            'address'         => 'nullable|string|max:255',
            'city'            => 'nullable|string|max:100',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'website'         => 'nullable|url|max:255',
            'social_links'    => 'nullable|array',
            'working_hours'   => 'nullable|array',
            'location'        => 'nullable|array',
            'active'          => 'boolean',
            'verified'        => 'boolean',
        ]);

        // Create salon
        $salon = Salon::create($data);

        return response()->json($salon, Response::HTTP_CREATED);
    }

    /**
     * Display the specified salon.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        // Find salon or fail
        $salon = Salon::findOrFail($id);

        return response()->json($salon);
    }

    /**
     * Update the specified salon in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Find salon or fail
        $salon = Salon::findOrFail($id);

        // Validate incoming data
        $data = $request->validate([
            'owner_id'        => 'sometimes|integer|exists:users,id',
            'slug'            => "sometimes|string|unique:salons,slug,{$id}",
            'name'            => 'sometimes|string|max:255',
            'translations'    => 'nullable|array',
            'address'         => 'nullable|string|max:255',
            'city'            => 'nullable|string|max:100',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'website'         => 'nullable|url|max:255',
            'social_links'    => 'nullable|array',
            'working_hours'   => 'nullable|array',
            'location'        => 'nullable|array',
            'active'          => 'boolean',
            'verified'        => 'boolean',
        ]);

        // Update salon
        $salon->update($data);

        return response()->json($salon);
    }

    /**
     * Remove the specified salon from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        // Find salon or fail
        $salon = Salon::findOrFail($id);

        // Soft delete salon
        $salon->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
