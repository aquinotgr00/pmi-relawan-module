<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use BajakLautMalaka\PmiRelawan\EventParticipant;
use BajakLautMalaka\PmiRelawan\EventReport;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreEventParticipantRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateParticipantRequest;
use BajakLautMalaka\PmiRelawan\Traits\RelawanTrait;
use BajakLautMalaka\PmiRelawan\Events\PendingParticipant;


class EventParticipantApiController extends Controller
{
    private const GENERAL_DISCUSSION = 1;

    use RelawanTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, EventParticipant $eventParticipants)
    {
        $eventParticipants = $eventParticipants->where('event_report_id',$request->input('e',1));
        
        $eventParticipants = $this->handleRequestJoinStatus($request, $eventParticipants);
        return response()->success($eventParticipants->with('volunteer')->paginate(10));
    }

    private function handleRequestJoinStatus(Request $request,$eventParticipants)
    {
        if ($request->has('j')) {
            if($request->j==='approved') {
                $eventParticipants = $eventParticipants->where('approved',1);
            } else {
                $eventParticipants = $eventParticipants->whereNull('approved');
            }
        }
        return $eventParticipants;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventParticipantRequest $request)
    {
        $rsvp = EventReport::find($request->event_report_id);
        
        $eventParticipation = $rsvp->participants()->create(['volunteer_id'=>Auth::user()->volunteer->id]);

        event(new PendingParticipant($eventParticipation));
        
        return response()->success($eventParticipation);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \BajakLautMalaka\PmiRelawan\EventParticipant  $participants
     * @return \Illuminate\Http\Response
     */
    public function show(EventParticipant $participants)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \BajakLautMalaka\PmiRelawan\EventParticipant  $participants
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateParticipantRequest $request, EventParticipant $participants)
    {
        $participants->request_join = $request->request_join;
        $participants->admin_id = auth()->user()->id;
        $participants->save();
        $participants->event;
        return response()->success($participants);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \BajakLautMalaka\PmiRelawan\EventParticipant  $participants
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventParticipant $participants)
    {
        //
    }
}
