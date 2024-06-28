<?php

namespace App\Http\Controllers;

use App\Models\Lane;
use App\Models\Ticket;
use Illuminate\Http\Request;

class LaneController extends Controller
{
    public function index()
    {

        return  Lane::with(['tickets' => function ($query) {
            $query->orderBy('position', 'asc'); // Sắp xếp tickets theo trường position tăng dần
        }])->get();
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
