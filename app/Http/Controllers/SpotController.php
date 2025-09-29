<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpotRequest;
use App\Http\Requests\UpdateSpotRequest;
use App\Models\Category;
use App\Models\Spot;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $spots = Spot::with([
                'user:id,name',
                'categories:category,spot_id',
            ])
                ->withCount(['reviews'])
                ->withSum('reviews', 'rating')
                ->orderBy('created_at', 'desc')
                ->paginate(request('size', 10));

            return response()->json([
                'message' => 'List of Data',
                'data' => $spots,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to Show Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpotRequest $request)
    {
        try {
            $validated = $request->safe()->all();

            $picture_path = Storage::disk('public')->putFile('spots', $request->file('picture'));

            $validated['user_id'] = Auth::user()->id;
            $validated['picture'] = $picture_path;

            $spot = Spot::create($validated);
            if ($spot) {
                $categories = [];

                foreach ($validated['category'] as $category) {
                    $categories[] = [
                        'spot_id' => $spot->id,
                        'category' => $category,
                    ];
                }
                Category::fillAndInsert($categories);

                return response()->json([
                    'message' => 'Spot Successfully added',
                    'data' => $validated,
                ], 201);

            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to Add Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Spot $spot)
    {
        try {
            return response()->json([
                'message' => 'Data Successfully Showed!',
                'data' => $spot->load(['categories:category,spot_id', 'user:id,name'])->loadCount(['reviews'])->loadSum('reviews', 'rating'),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to Show Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Spot $spot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpotRequest $request, Spot $spot)
    {
        $validated = $request->safe()->all();
        if (isset($validated['picture'])) {
            $picture_path = Storage::disk('public')->putFile('spots', $request->file('picture'));
        }

        if (isset($validated['category'])) {
            $categories = [];

            foreach ($validated['category'] as $category) {
                $categories[] = [
                    'spot_id' => $spot->id,
                    'category' => $category,
                ];
            }
            Category::fillAndInsert($categories);
        }

        $spot->update([
            'name' => $validated['name'],
            'picture' => $picture_path ?? $spot->picture,
            'address' => $validated['address'],
        ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spot $spot)
    {
        try {
            $user = Auth::user();
            if ($spot->user_id == $spot->id || $user->role == 'admin') {
                $spot->delete();

                return response()->json([
                    'message' => 'Data Successfully Deleted!',
                ], 200);
            } else {

                return response()->json([
                    'message' => 'Failed to Delete Data!',
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function review(Spot $spot)
    {
        try {
            return response()->json([
                'message' => 'List of Reviews',
                'data' => $spot->reviews()->with(['user:id,name'])->get(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to Show Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
