<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketCreateRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$tickets = Ticket::all();
        $tickets = Ticket::latest("id")->paginate(10);
        return TicketResource::collection($tickets);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketCreateRequest $request)
    {
        $data = $request->validated();
        $ticket = Ticket::create([
            "subject" => $data["subject"],
            "description" => $data["description"],
            "status_id" => $data["status_id"],
            "customer_id" => $data["customer_id"],
            "user_id" => Auth::id()
        ]);
        //return response()->json($ticket->user_id);
        return response()->json([
            "message" => "Ticket Created",
            "success" => true,
            "ticket" => new TicketResource($ticket)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::find($id);
        if(is_null($ticket)){
            return response()->json(["message" => "Ticket is not found"],404);
        }
        //return response()->json($ticket);
        return new TicketResource($ticket);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "subject" => "sometimes",
            "description" => "sometimes",
            "status_id" => "sometimes|exists:statuses,id",
            "customer_id" => "sometimes|exists:customers,id"
        ]);
        $ticket = Ticket::find($id);
        if(is_null($ticket)){
            return response()->json(["message" => "Ticket is not found"],404);
        }

        $ticket->fill($request->only(['subject', 'description', 'status_id', 'customer_id']));
       
        $ticket->save();
        //return response()->json($ticket);
        return response()->json([
            "message" => "Ticket Updated",
            "success" => true,
            "ticket" => new TicketResource($ticket)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket = Ticket::find($id);
        if(is_null($ticket)){
            return response()->json(["message" => "Ticket is not found"],404);
        }
        $ticket->delete();
        return response()->json(["message" => "Ticket is Deleted"],204);
    }
}
