<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;

use BajakLautMalaka\PmiRelawan\EventActivity;

use BajakLautMalaka\PmiRelawan\Events\CommentPosted;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreEventActivityRequest;

class ChatApiController extends Controller
{
    public function index()
    {
        return response()->success(EventActivity::where('event_report_id',request('e'))->latest()->paginate(8));
    }

    public function store(StoreEventActivityRequest $request)
    {
        $user = auth()->user();

        $activity = EventActivity::make($request->input());

        if($request->has('media')) {
            $activity->comment_attachment = $request->media->store('activities','public');
        }
                
        $activity->admin_id = $user->volunteer?null:$user->id;
        $activity->volunteer_id = $user->volunteer?$user->volunteer->id:null;
        $activity->save();

        broadcast(new CommentPosted($user, $activity))->toOthers();

        return response()->success($activity);
    }

    public function destroy(EventActivity $comment)
    {
        $comment->delete();

        return response()->success($comment);
    }
}