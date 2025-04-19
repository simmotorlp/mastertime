<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the active services.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $services = Service::where('active', true)->get();
        return ServiceResource::collection($services);
    }

    /**
     * Display the specified service.
     *
     * @param int $id
     * @return ServiceResource
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return new ServiceResource($service);
    }

    /**
     * Store a newly created service in storage.
     *
     * @param Request $request
     * @return ServiceResource
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $salon_id = $user->salon_id; // Assuming user has a salon_id

        $validated = $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'active' => 'sometimes|boolean',
        ]);

        $service = new Service($validated);
        $service->salon_id = $salon_id;
        $service->save();

        return new ServiceResource($service);
    }

    /**
     * Update the specified service in storage.
     *
     * @param Request $request
     * @param int $id
     * @return ServiceResource
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $user = $request->user();

        if ($service->salon_id !== $user->salon_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:service_categories,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'duration' => 'sometimes|integer|min:1',
            'active' => 'sometimes|boolean',
        ]);

        $service->update($validated);

        return new ServiceResource($service);
    }

    /**
     * Remove the specified service from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $user = request()->user();

        if ($service->salon_id !== $user->salon_id) {
            abort(403, 'Unauthorized');
        }

        $service->delete();

        return response()->json(['message' => 'Service deleted']);
    }
}
