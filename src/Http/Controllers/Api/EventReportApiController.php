<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

use BajakLautMalaka\PmiRelawan\EventReport;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreEventReportRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateEventReportRequest;
use BajakLautMalaka\PmiRelawan\Scopes\OrderByLatestScope;
use BajakLautMalaka\PmiRelawan\Traits\RelawanTrait;
use BajakLautMalaka\PmiRelawan\Events\ReportSubmitted;
use BajakLautMalaka\PmiRelawan\Events\ReportApproved;
use BajakLautMalaka\PmiRelawan\Events\ReportRejected;

class EventReportApiController extends Controller
{
    private const GENERAL_DISCUSSION = 1;

    use RelawanTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,EventReport $report)
    {
        $report = $this->handleSearch($request,$report);
        $report = $this->handleByVolunteerID($request,$report);
        $report = $this->handleOrder($request,$report);
        $report = $this->handleApprovedStatus($request,$report);
        $report = $this->handleEmergencyStatus($request,$report);
        $report = $this->handleArchivedStatus($request,$report);
        $report = $this->handleJoinRequest($request,$report);
        $report = $this->shouldGetLastComment($request,$report);
        
        $report = $report
            ->withCount([
                'participants',
                'participants AS approved_participants'=>function($query) {
                    $query->where('approved',true);
                }])
            ->with(['admin','appUser','village.subdistrict.city'])
            ->has('appUser');
        $report = $report->paginate();
        return response()->success($report);
    }

    private function handleSearch(Request $request,$report)
    {
        if ($request->has('s')) {
            $report = $report->whereLike([
                'title', 'description', 'village.subdistrict.city.name', 'admin.name' , 'volunteer.user.name'
            ],$request->s);
        }
        return $report;
    }

    private function handleApprovedStatus(Request $request,$report)
    {
        if ($request->has('ap')) {
            $report = $report->where('approved',$request->ap);
            $report = $report->where('archived',0);
        }elseif ($request->has('p')) {
            $report = $report
                ->withTrashed()
                ->whereNull('approved')
                ->when(auth()->user()->volunteer, function($query) {
                    return $query->where('volunteer_id', auth()->user()->volunteer->id);
                });
        }
        return $report;
    }

    private function handleEmergencyStatus(Request $request,$report)
    {
        if ($request->has('e')) {
            $report = $report->where('emergency',$request->e);
        }
        return $report;
    }

    private function handleArchivedStatus(Request $request,$report)
    {
        if ($request->has('ar')) {
            $report = $report->whereRaw('archived = id')
                ->where('approved',1)
                ->withTrashed()
                ->withoutGlobalScope(OrderByLatestScope::class)
                ->orderBy('deleted_at','desc');
        }
        return $report;
    }

    private function handleByVolunteerID(Request $request, $report)
    {
        if ($request->has('v_id')) {
            $report = $report->where('volunteer_id',$request->v_id);
        }
        return $report;
    }

    private function handleJoinRequest(Request $request, $report)
    {
        if ($request->has('j')) {
            $report = $report
                ->where('id','!=',self::GENERAL_DISCUSSION)
                ->when($request->j==='approved', function($query) {
                    return $query->whereHas('participants',function(Builder $query) {
                        $query->where(function($subQuery) {
                            $subQuery
                                ->where('volunteer_id',auth()->user()->volunteer->id)
                                ->where('approved',1);
                        });
                    });
                })
                ->when($request->j==='other',function($query) {
                    return $query->where(function($subQuery) {
                        $subQuery->where('approved',1)
                        ->whereDoesntHave('participants', function(Builder $query) {
                            $query->where(function($subQuery) {
                                $subQuery
                                ->where('volunteer_id',auth()->user()->volunteer->id)
                                ->where('approved',1);
                            });
                        });
                    });
                });
        }
        
        return $report;
    }

    private function shouldGetLastComment(Request $request, $report)
    {
        if ($request->has('lc')) {
            $latestComments = DB::table('event_activities')
                ->select('event_report_id', DB::raw('MAX(id) AS last_comment_id'))
                ->whereNull('deleted_at')
                ->groupBy('event_report_id');

            $report = $report
                ->with(['activities'=>function($query) use ($latestComments) {
                    $query->joinSub($latestComments, 'latest_comments', function ($join) {
                        $join->on('id', '=', 'latest_comments.last_comment_id');
                    });
                }]);

            $report = $report;
        }

        return $report;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventReportRequest $request)
    {
        $rsvp = EventReport::make($request->input());
        if($request->is('api/admin/*')) {
            $rsvp->admin_id = Auth::id();
            $rsvp->approved = true;    // automatically approved
        }
        else {
            $rsvp->volunteer_id = Auth::user()->volunteer->id;
        }

        $this->resizeEventImage($request, $rsvp);

        try {
            $rsvp->save();
            event(new ReportSubmitted($rsvp));
            return response()->success($rsvp);
        } catch (Exception $e) {
            return response()->fail($e);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \BajakLautMalaka\PmiRelawan\EventReport  $event
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $requestByAppUser = request()->is('api/app/*');
        
        return response()->success(
            EventReport::withTrashed()
            ->with('participants','activities','village.subdistrict.city')
            ->when($requestByAppUser,function($query) {
                return $query->with('joinStatus');
            })
            ->find($id)
        );
    }

    /**
     * Update the specified resource in storage.
     * 
     * update mode : Approval, Archive, Revise
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \BajakLautMalaka\PmiRelawan\EventReport  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventReportRequest $request, EventReport $report)
    {
        if ($request->has('archived')) {
            $this->archive($report);
        } else {
            
            if ($request->hasFile('image_file')) {
                Storage::delete([$report->image,substr_replace($report->image,'originals/',14,0)]);
                $this->resizeEventImage($request, $report);
            }
    
            $report->fill($request->only('title','description','village_id'));
            
            if ($request->has('approved')) {
                $this->processApprovalOrRejection($request, $report);
            }
    
            $report->save();
        }
        
        return response()->success($report->load(['admin','volunteer','village.subdistrict.city']));
    }

    private function resizeEventImage($request, &$rsvp) {
        // keep original file
        $request->image_file->store('event-reports/originals','public');

        // ... and resize another one to 320x240
        $rsvp->image = $request->image_file->store('event-reports','public');
        $resized = Image::make($request->image_file)->resize(320, 240, function ($constraint) {
            $constraint->aspectRatio();
        })->encode();
        Storage::disk('public')->put($rsvp->image, $resized);
    }

    private function archive($report) {
        $report->archived = $report->id;
        $report->save();
        $report->delete();
    }

    private function processApprovalOrRejection($request, &$report) {
        $report->approved = $request->approved;
        if($request->approved) {
            event(new ReportApproved($report));
        }else{
            $report->reason_rejection = $request->reason_rejection;
            $report->archived = $report->id;

            event(new ReportRejected($report));
        }
        
        if (isset($report->volunteer->user->email)) {
            event(new ReportSubmitted($report));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \BajakLautMalaka\PmiRelawan\EventReport  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventReport $report)
    {
        $report->delete();
        return response()->success($report);
    }

    public function onlyTrashed(Request $request, EventReport $report)
    {
        $report = $report->onlyTrashed();
        $report = $this->handleSearch($request,$report);
        $report = $this->handleOrder($request,$report);
        $report = $report->paginate();
        return response()->success($report);
    }
}
