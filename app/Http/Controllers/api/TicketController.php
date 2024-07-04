<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Lane;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index()
    {
        $lanes = Ticket::all();
        return response()->json(
            [
                'success' => true,
                'data' => $lanes,
                'message' => 'Ticket retrieved successfully'
            ],
            200
        );
    }

    public function show($id)
    {
        return Ticket::findOrFail($id);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'priority' => 'required|string|max:255',
                'description' => 'required|string',
                'link_issue' => 'required|string|url',
            ]);
            // fix lane_id
            $laneId = 1;
            $position_ticket = 0;
            $ticket = new Ticket();
            $ticket->lane_id = $laneId;
            $ticket->title = $validatedData['title'];
            // handle get author
            $ticket->author = $request->input('author') ? $request->input('author') : 'HienLV';
            $ticket->priority = $validatedData['priority'];
            $ticket->description = $validatedData['description'];
            $ticket->link_issue = $validatedData['link_issue'];
            $ticket->position =  $position_ticket;
            Ticket::where('lane_id', 1)->increment('position');
            $ticket->save();

            $lanes = Lane::with(['tickets' => function ($query) {
                $query->orderBy('position', 'asc');
            }])->get();

            return response()->json(['success' => true, 'data' => $lanes, 'message' => 'Ticket created successfully'], 200);
            //code...
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }
    public function delete(Request $request, int $laneId, int $ticketId)
    {
        try {
            $ticket = Ticket::where('id', $ticketId)
                ->where('lane_id', $laneId)
                ->firstOrFail();

            $ticket->delete();
            $lanes = Lane::with('tickets')->get();

            return response()->json(['success' => true, 'data' => $lanes, 'message' => 'Ticket deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete ticket'], 500);
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
            $ticket = Ticket::find($ticketId);

            if (empty($ticket)) {
                return response()->json(['success' => false, 'message' => 'Ticket not found'], 404);
            }

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
                } else {
                    Ticket::where('lane_id', $currentLaneId)
                        ->where('position', '>', $oldPosition)
                        ->where('position', '<=', $newPosition)
                        ->decrement('position');
                }
            }

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
            ->first();

        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket not found'], 404);
        }

        $ticket->title = $request->input('title', $ticket->title);
        $ticket->author = $request->input('author', $ticket->author);
        $ticket->priority = $request->input('priority', $ticket->priority);
        $ticket->description = $request->input('description', $ticket->description);
        $ticket->link_issue = $request->input('link_issue', $ticket->link_issue);

        try {
            $ticket->save();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update ticket'], 500);
        }

        $lanes = Lane::with(['tickets' => function ($query) {
            $query->orderBy('position', 'asc');
        }])->get();

        return response()->json(['success' => true, 'data' => $lanes, 'message' => 'Ticket updated successfully'], 200);
    }
}
