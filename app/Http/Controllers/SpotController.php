<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpotRequest;
use App\Http\Requests\UpdateSpotRequest;
use App\Models\Category;
use App\Models\Spot;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

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
            if($spot){
                $categories =[];

                foreach($validated['categories'] as $category){
                    $categories[] = [
                        'spot_id' => $spot->id,
                        'category' => $category,
                    ];
                }
                Category::fillAndInsert($categories);
                return response()->json([
                'message' => 'Spot Successfully added',
                'data' => $validated
            ], 201);

            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to Add Data',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Spot $spot)
    {
        //
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
        $spot->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spot $spot)
    {
        //
    }
}
