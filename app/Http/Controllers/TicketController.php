<?php

namespace App\Http\Controllers;

use App\Models\Lane;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    //
    public function index()
    {
        return Ticket::all();
    }

    public function show($id)
    {
        return Ticket::findOrFail($id);
    }

    public function store(Request $request)
    {
        Ticket::where('lane_id', 1)->increment('position');
        $ticket = new Ticket();
        $ticket->lane_id = 1;
        $ticket->title = $request->input('title');
        $ticket->author = $request->input('author');
        $ticket->priority = $request->input('priority');
        $ticket->description = $request->input('description');
        $ticket->link_issue = $request->input('link_issue');
        $ticket->position = 0;
        $ticket->save();

        $lanes = Lane::with(['tickets' => function ($query) {
            $query->orderBy('position', 'asc');
        }])->get();

        return response()->json(['success' => true, 'data' => $lanes]);
    }
    public function delete(Request $request, $laneId, $ticketId)
    {
        try {
            $ticket = Ticket::where('id', $ticketId)
                ->where('lane_id', $laneId)
                ->firstOrFail();

            $ticket->delete();
            $lanes = Lane::with('tickets')->get();
            $responseData = [
                'message' => 'Ticket deleted successfully',
                'data' => $lanes,
                'success' => true,
            ];

            return response()->json($responseData, 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Failed to delete ticket', 'error' => $exception->getMessage()], 500);
        }
    }
    public function moveTicket(Request $request)
    {
        $ticketId = $request->input('ticketId');
        $fromLaneId = $request->input('fromLaneId');
        $toLaneId = $request->input('toLaneId');
        $oldPosition = $request->input('oldIndex');
        $newPosition = $request->input('newIndex');

        DB::transaction(function () use ($ticketId, $toLaneId, $newPosition, $oldPosition) {
            $ticket = Ticket::findOrFail($ticketId);
            $currentLaneId = $ticket->lane_id;

            if ($currentLaneId == $toLaneId && $newPosition == $oldPosition) {
                return response()->json(['success' => true, 'message' => 'Ticket does not move']);
            }

            if ($currentLaneId != $toLaneId) {
                Ticket::where('lane_id', $currentLaneId)
                    ->where('position', '>', $ticket->position)
                    ->decrement('position');

                Ticket::where('lane_id', $toLaneId)
                    ->where('position', '>=', $newPosition)
                    ->increment('position');
            } else {
                if ($newPosition < $oldPosition) {
                    Ticket::where('lane_id', $currentLaneId)
                        ->where('position', '>=', $newPosition)
                        ->where('position', '<', $oldPosition)
                        ->increment('position');
                    echo "move up";
                } else {
                    Ticket::where('lane_id', $currentLaneId)
                        ->where('position', '>', $oldPosition)
                        ->where('position', '<=', $newPosition)
                        ->decrement('position');
                }
            }

            echo  'newPosition: ' . $newPosition . ' oldPosition: ' . $oldPosition . ' currentLaneId: ' . $currentLaneId . ' toLaneId: ' . $toLaneId . 'ticketId: ' . $ticketId;
            $ticket->lane_id = $toLaneId;
            $ticket->position = $newPosition;
            $ticket->save();
        });

        $lanes = Lane::with(['tickets' => function ($query) {
            $query->orderBy('position', 'asc');
        }])->get();

        return response()->json(['success' => true, 'data' => $lanes]);
    }
    public function update(Request $request, $laneId, $ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)
            ->where('lane_id', $laneId)
            ->firstOrFail();
        $ticket->title = $request->input('title');
        $ticket->author = $request->input('author');
        $ticket->priority = $request->input('priority');
        $ticket->description = $request->input('description');
        $ticket->link_issue = $request->input('link_issue');
        $ticket->save();
        $lanes = Lane::with(['tickets' => function ($query) {
            $query->orderBy('position', 'asc');
        }])->get();

        return response()->json(['success' => true, 'data' => $lanes]);
    }
}
