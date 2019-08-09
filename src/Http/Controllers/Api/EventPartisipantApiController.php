<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use BajakLautMalaka\PmiRelawan\EventPartisipant;
use BajakLautMalaka\PmiRelawan\EventReport;
use BajakLautMalaka\PmiRelawan\Http\Requests\StorePartisipantRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdatePartisipantRequest;
use Illuminate\Http\Request;

class EventPartisipantApiController extends Controller
{
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
    public function index(Request $request, EventPartisipant $partisipants)
    {
        $user               = auth()->user();
        $partisipants       = $partisipants->where('volunteer_id',$user->id);
        $partisipants       = $this->handleRequestJoinStatus($request,$partisipants);
        
        $events_id          = [];    
        if ($partisipants->count() > 0) {
            foreach ($partisipants->get() as $key => $value) {
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

    public function handleRequestJoinStatus(Request $request,$partisipants)
    {
        if ($request->has('j')) {

            $partisipants = $partisipants->where('request_join',intval($request->j));
        }else{
            $partisipants = $partisipants->whereNull('request_join');    
        }
        return $partisipants;
    }

    public function handleArchivedStatus(Request $request,$events)
    {
        if ($request->has('ar')) {
            $events = $events->where('archived',$request->ar);
        }else{
            $events = $events->where('archived',0);
        }
        return $events;
    }

    public function handleSearch(Request $request,$events)
    {
        if ($request->has('s')) {
            $events = $events->where('title','like','%'.$request->s.'%')
            ->orWhere('description','like','%'.$request->s.'%')
            ->orWhere('type','like','%'.$request->s.'%')
            ->orWhere('location','like','%'.$request->s.'%');
        }
        return $events;
    }

    public function handleOrder(Request $request,$events)
    {
        if ($request->has('ob')) {
            // sort direction (default = asc)
            $sort_direction = 'asc';
            if ($request->has('od')) {
                if (in_array($request->od, ['asc', 'desc'])) {
                    $sort_direction = $request->od;
                }
            }
            $events = $events->orderBy($request->ob, $sort_direction);
        }
        return $events;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePartisipantRequest $request)
    {
        $user   = auth()->user();
        $event  = EventReport::find($request->event_report_id);
        if (!is_null($event)) {
            if ($event->approved === 1) {
                $partisipants = EventPartisipant::firstOrCreate(
                    [
                    'event_report_id' => $request->event_report_id 
                    ],
                    [
                    'event_report_id' => $request->event_report_id,
                    'volunteer_id' => $user->id
                    ]
                    );

                $partisipants->event;
                return response()->success($partisipants);
            }
        }
        return response()->fail($event);
    }

    /**
     * Display the specified resource.
     *
     * @param  \BajakLautMalaka\PmiRelawan\EventPartisipant  $partisipants
     * @return \Illuminate\Http\Response
     */
    public function show(EventPartisipant $partisipants)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \BajakLautMalaka\PmiRelawan\EventPartisipant  $partisipants
     * @return \Illuminate\Http\Response
     */
    public function edit(EventPartisipant $partisipants)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \BajakLautMalaka\PmiRelawan\EventPartisipant  $partisipants
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePartisipantRequest $request, EventPartisipant $partisipants)
    {
        $partisipants->request_join = $request->request_join;
        $partisipants->admin_id     = auth()->user()->id;
        $partisipants->save();
        $partisipants->event;
        return response()->success($partisipants);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \BajakLautMalaka\PmiRelawan\EventPartisipant  $partisipants
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventPartisipant $partisipants)
    {
        //
    }
}
