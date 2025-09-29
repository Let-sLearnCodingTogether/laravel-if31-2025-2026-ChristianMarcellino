<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use Exception;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json([
                'Message' => 'Review Successfully Showed',
                'data' => Review::all(),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'Message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        try {
            $validated = $request->safe()->all();
            $validated['user_id'] = $request->user()->id;
            $response = Review::create($validated);
            if ($response) {
                return response()->json([
                    'Message' => 'Review Successfully Added',
                    'data' => $response,
                ], 201);
            } else {
                return response()->json([
                    'Message' => 'Failed to add review',
                    'data' => null,
                ], 500);
            }
        } catch (Exception $e) {
            return response()->json([
                'Message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        try {
            if ($review->delete()) {
                return response()->json([
                    'Message' => 'Review Successfully Deleted',
                    'data' => null,
                ], 200);
            } else {
                return response()->json([
                    'Message' => 'Failed to Delete Review',
                    'data' => null,
                ], 500);
            }
        } catch (Exception $e) {
            return response()->json([
                'Message' => $e->getMessage(),
                'data' => null,
            ], 403);
        }
    }
}
