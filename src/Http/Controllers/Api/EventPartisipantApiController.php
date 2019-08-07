<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use BajakLautMalaka\PmiRelawan\EventPartisipant;
use BajakLautMalaka\PmiRelawan\EventReport;
use BajakLautMalaka\PmiRelawan\Http\Requests\StorePartisipantRequest;
use Illuminate\Http\Request;

class EventPartisipantApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, EventPartisipant $partisipants)
    {
        $user            = auth()->user();
        $partisipants    = $partisipants->where('volunteer_id',$user->id)->paginate();
        foreach ($partisipants as $key => $value) {
            $value->event;
        }
        return response()->success($partisipants);
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
        $user    = auth()->user();

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
    public function update(Request $request, EventPartisipant $partisipants)
    {
        //
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
