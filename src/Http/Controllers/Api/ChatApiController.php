<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use BajakLautMalaka\PmiRelawan\EventReport;
use BajakLautMalaka\PmiRelawan\EventActivity;

use BajakLautMalaka\PmiRelawan\Events\CommentPosted;

class ChatApiController extends Controller
{
    public function showActivities($eventId)
    {
        $activities = EventActivity::where('event_report_id', $eventId)->paginate(8);
        return response()->success($activities);
    }

    public function storeActivity(Request $request)
    {
        $user = auth()->user();
        if (auth()->guard('admin')->check()) {
            $request->request->add([
                'admin_id' => $user->id
            ]);
        } if ($user->volunteer) {
            $request->request->add([
                'volunteer_id' => $user->id
            ]);
        }
        $activity = EventActivity::create($request->all());

        broadcast(new CommentPosted($user, $activity))->toOthers();

        return response()->success($activity);
    }

    public function delete(EventActivity $eventActivity)
    {
        $eventActivity->delete();

        return response()->success($eventActivity);
    }
}