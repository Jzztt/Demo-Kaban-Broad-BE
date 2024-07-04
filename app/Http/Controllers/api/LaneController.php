<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Lane;
use Illuminate\Http\Request;

class LaneController extends Controller
{
    public function index()
    {

        $lanes = Lane::with(['tickets' => function ($query) {
            $query->orderBy('position', 'asc');
        }])->get();

        return response()->json(['success' => True, 'data' => $lanes, 'message' => 'Lanes retrieved successfully'], 200);
    }

    public function show($id)
    {
        return Lane::with('tickets')->findOrFail($id);
    }

    public function update(Request $request, $laneId)
    {
        $laneData = $request->all();
        if (empty($laneData['tickets'])) {
        }
    }
}
