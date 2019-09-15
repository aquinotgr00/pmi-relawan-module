<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use BajakLautMalaka\PmiRelawan\EventReport;
use BajakLautMalaka\PmiRelawan\EventActivity;

use BajakLautMalaka\PmiRelawan\Events\CommentPosted;

class ChatApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function showActivities($eventId)
    {
        $activities = EventActivity::where('event_report_id', $eventId)->paginate(8);
        return response()->success($activities);
    }

    public function storeActivity(Request $request)
    {
        $user = auth()->user();
        $request->request->add([
            'volunteer_id' => $user->id
        ]);
        $activity = EventActivity::create($request->all());

        broadcast(new CommentPosted($user, $activity))->toOthers();

        return response()->success($activity);
    }
}