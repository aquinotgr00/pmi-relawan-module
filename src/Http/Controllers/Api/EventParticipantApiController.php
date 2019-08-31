<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use BajakLautMalaka\PmiRelawan\EventPartisipant;
use BajakLautMalaka\PmiRelawan\EventReport;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreParticipantRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateParticipantRequest;
use Illuminate\Http\Request;
use BajakLautMalaka\PmiRelawan\Traits\RelawanTrait;

class EventParticipantApiController extends Controller
{
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
    public function index(Request $request, EventPartisipant $participants)
    {
        $user               = auth()->user();
        $participants       = $participants->where('volunteer_id',$user->id);
        $participants       = $this->handleRequestJoinStatus($request,$participants);
        
        $events_id          = [];    
        if ($participants->count() > 0) {
            foreach ($participants->get() as $key => $value) {
                $events_id[] = $value->event_report_id;
            }
        }
        $events             = EventReport::whereIn('id',$events_id)->where('approved',1);
        $events             = $this->handleArchivedStatus($request,$events); 
        $events             = $this->handleSearch($request,$events);
        $events             = $this->handleOrder($request,$events);
        $events             = $events->paginate();
        return response()->success($events);
    }

    private function handleRequestJoinStatus(Request $request,$participants)
    {
        if ($request->has('j')) {

            $participants = $participants->where('request_join',intval($request->j));
        }else{
            $participants = $participants->whereNull('request_join');    
        }
        return $participants;
    }

    private function handleArchivedStatus(Request $request,$events)
    {
        if ($request->has('ar')) {
            $events = $events->where('archived',$request->ar);
        }else{
            $events = $events->where('archived',0);
        }
        return $events;
    }

    private function handleSearch(Request $request,$events)
    {
        if ($request->has('s')) {
            $events = $events->where('title','like','%'.$request->s.'%')
            ->orWhere('description','like','%'.$request->s.'%')
            ->orWhere('type','like','%'.$request->s.'%')
            ->orWhere('location','like','%'.$request->s.'%');
        }
        return $events;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreParticipantRequest $request)
    {
        $user   = auth()->user();
        $event  = EventReport::find($request->event_report_id);
        if (!is_null($event)) {
            if ($event->approved === 1) {
                $participants = EventPartisipant::firstOrCreate(
                    [
                    'event_report_id' => $request->event_report_id 
                    ],
                    [
                    'event_report_id' => $request->event_report_id,
                    'volunteer_id' => $user->id
                    ]
                    );

                $participants->event;
                return response()->success($participants);
            }
        }
        return response()->fail($event);
    }

    /**
     * Display the specified resource.
     *
     * @param  \BajakLautMalaka\PmiRelawan\EventPartisipant  $participants
     * @return \Illuminate\Http\Response
     */
    public function show(EventPartisipant $participants)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \BajakLautMalaka\PmiRelawan\EventPartisipant  $participants
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateParticipantRequest $request, EventPartisipant $participants)
    {
        $participants->request_join = $request->request_join;
        $participants->admin_id     = auth()->user()->id;
        $participants->save();
        $participants->event;
        return response()->success($participants);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \BajakLautMalaka\PmiRelawan\EventPartisipant  $participants
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventPartisipant $participants)
    {
        //
    }
}
