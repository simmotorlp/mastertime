<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments for the authenticated user or all if admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            $appointments = Appointment::all();
        } else {
            $appointments = Appointment::where('user_id', $user->id)->get();
        }

        return response()->json($appointments);
    }

    /**
     * Store a newly created appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'salon_id'       => 'required|integer|exists:salons,id',
            'specialist_id'  => 'required|integer|exists:specialists,id',
            'service_id'     => 'required|integer|exists:services,id',
            'start_time'     => 'required|date|after:now',
            'end_time'       => 'required|date|after:start_time',
            'price'          => 'required|numeric|min:0',
            'status'         => 'required|string|in:pending,confirmed,completed,cancelled',
            'notes'          => 'nullable|string',
            'client_name'    => 'nullable|string|max:255',
            'client_phone'   => 'nullable|string|max:20',
            'client_email'   => 'nullable|email|max:255',
        ]);

        // Assign to authenticated user
        $data['user_id'] = $request->user()->id;

        $appointment = Appointment::create($data);

        return response()->json($appointment, Response::HTTP_CREATED);
    }

    /**
     * Display the specified appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $appointment = Appointment::findOrFail($id);

        return response()->json($appointment);
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $data = $request->validate([
            'salon_id'       => 'sometimes|integer|exists:salons,id',
            'specialist_id'  => 'sometimes|integer|exists:specialists,id',
            'service_id'     => 'sometimes|integer|exists:services,id',
            'start_time'     => 'sometimes|date|after:now',
            'end_time'       => 'sometimes|date|after:start_time',
            'price'          => 'sometimes|numeric|min:0',
            'status'         => 'sometimes|string|in:pending,confirmed,completed,cancelled',
            'notes'          => 'nullable|string',
            'client_name'    => 'nullable|string|max:255',
            'client_phone'   => 'nullable|string|max:20',
            'client_email'   => 'nullable|email|max:255',
        ]);

        $appointment->update($data);

        return response()->json($appointment);
    }

    /**
     * Remove the specified appointment from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Get available slots for a given salon.
     *
     * @param  int  $salon_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableSlots($salon_id)
    {
        $salon = Salon::findOrFail($salon_id);

        // TODO: Implement logic to calculate available slots based on salon working_hours and existing appointments
        $slots = [];

        return response()->json($slots);
    }
}
