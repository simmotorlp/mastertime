<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewController extends Controller
{
    /**
     * Display a listing of approved reviews.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $reviews = Review::approved()->get();

        return response()->json($reviews);
    }

    /**
     * Display approved reviews for a specific salon.
     *
     * @param  int  $salon_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function salonReviews($salon_id)
    {
        $reviews = Review::where('salon_id', $salon_id)
            ->approved()
            ->get();

        return response()->json($reviews);
    }

    /**
     * Display approved reviews for a specific specialist.
     *
     * @param  int  $specialist_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function specialistReviews($specialist_id)
    {
        $reviews = Review::where('specialist_id', $specialist_id)
            ->approved()
            ->get();

        return response()->json($reviews);
    }

    /**
     * Store a newly created review in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'salon_id'        => 'nullable|integer|exists:salons,id',
            'specialist_id'   => 'nullable|integer|exists:specialists,id',
            'service_id'      => 'nullable|integer|exists:services,id',
            'appointment_id'  => 'nullable|integer|exists:appointments,id',
            'content'         => 'required|string',
            'rating'          => 'required|integer|min:1|max:5',
        ]);

        $data['user_id'] = $request->user()->id;
        $data['approved'] = false;

        $review = Review::create($data);

        return response()->json($review, Response::HTTP_CREATED);
    }

    /**
     * Update the specified review.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $data = $request->validate([
            'content' => 'sometimes|string',
            'rating'  => 'sometimes|integer|min:1|max:5',
        ]);

        $review->update($data);

        return response()->json($review);
    }

    /**
     * Remove the specified review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Approve a review (admin only).
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->approved = true;
        $review->save();

        return response()->json($review);
    }

    /**
     * Hide (unapprove) a review (admin only).
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function hide($id)
    {
        $review = Review::findOrFail($id);
        $review->approved = false;
        $review->save();

        return response()->json($review);
    }
}
