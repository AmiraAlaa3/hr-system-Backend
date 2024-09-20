<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use Illuminate\Http\Request;
use App\Models\GenralSetting;
class genral_settingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $generalSetting = GenralSetting::all();
        return SettingResource::collection($generalSetting);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'weekend1' => 'required|string|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'weekend2' => 'required|string|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'bonusHours' => 'required|integer|min:1',
            'deductionsHours' => 'required|integer|min:1',
        ]);

        // Check if the same day is selected for both weekends
        if ($validated['weekend1'] === $validated['weekend2']) {
            return response()->json([
                'error' => 'Weekend1 and Weekend2 cannot be the same day.',
            ], 422);
        }
        if (!$validated['weekend1'] || !$validated['weekend2']) {
            return response()->json([
                'error' => 'you should enter a day.',
            ], 404);
         }
   
        $generalSetting = GenralSetting::first();

        if (!$generalSetting) {
            return response()->json([
                'error' => 'General settings not found.',
            ], 404);
        }

        $generalSetting->weekend1 = $validated['weekend1'];
        $generalSetting->weekend2 = $validated['weekend2'];
        $generalSetting->bonusHours = $validated['bonusHours'];
        $generalSetting->deductionsHours = $validated['deductionsHours'];
        $generalSetting->save();
        return response()->json([
            'message' => 'Weekend days updated successfully.',
            'data' => $generalSetting,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
