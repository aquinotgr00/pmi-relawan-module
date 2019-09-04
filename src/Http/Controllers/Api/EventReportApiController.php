<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use BajakLautMalaka\PmiRelawan\EventReport;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreEventReportRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateEventReportRequest;
use BajakLautMalaka\PmiRelawan\Scopes\OrderByLatestScope;
use BajakLautMalaka\PmiRelawan\Traits\RelawanTrait;
use Illuminate\Support\Facades\Auth;

class EventReportApiController extends Controller
{
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
        $report = $report->withCount([
            'participants',
            'participants AS approved_participants'=>function($query) {
                $query->where('approved',true);
            }]
        )->with(['admin','volunteer','village.subdistrict.city']);
        // TODO : hide all "qualifications" attributes in BajakLautMalaka\PmiRelawan\Volunteer
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
            $report = $report->whereNull('approved');
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

    public function handleByVolunteerID(Request $request, $report)
    {
        if ($request->has('v_id')) {
            $report = $report->where('volunteer_id',$request->v_id);
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
        $rsvp      = EventReport::make($request->input());
        if($request->is('api/admin/*')) {
            $rsvp->admin_id = Auth::id();
            $rsvp->approved = true;    // automatically approved

            $mail_to = Auth::user()->email;
        }
        else {
            $rsvp->volunteer_id = Auth::user()->volunteer->id;
            $mail_to = Auth::user()->email;
        }

        // keep original file
        $request->image_file->store('event-reports/originals','public');

        // ... and resize another one to 320x240
        $rsvp->image = $request->image_file->store('event-reports','public');
        $resized = Image::make($request->image_file)->resize(320, 240, function ($constraint) {
            $constraint->aspectRatio();
        })->encode();
        Storage::disk('public')->put($rsvp->image, $resized);
        
        try {
            $rsvp->save();
            $rsvp->sendEventReportStatus($mail_to,$rsvp);
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
    public function show(EventReport $report)
    {
        $report->load('participants','activities','village.subdistrict.city');
        return response()->success($report);
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
        //$this->handleChangeImage($request,$report,'event-images');
        $report->fill($request->only('title','description','village_id'));
        $report->save();

        if ($request->has('archived')) {
            $report->archived = $report->id;
            $report->save();
            $report->delete();
        }
        if ($request->has('approved')) {
            $report->approved = $request->approved;
            if(!$request->approved) {
                $report->reason_rejection = $request->reason_rejection;
                $report->archived = $report->id;
            }
            $report->save();
            if (isset($report->volunteer->user->email)) {
                $email = $report->volunteer->user->email;
                $report->sendEventReportStatus($email, $report);
            }
        }
        
        return response()->success($report->load(['admin','volunteer','village.subdistrict.city']));
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

    private function handleUploadImage(Request $request,string $hashName)
    {
        if ($request->hasFile('image_file')) {

            try{

                $file   = $request->file('image_file');
                $path   = $file->hashName($hashName);
                $image  = Image::make($file);

                $image->resize(450, 350, function ($constraint) {
                    $constraint->aspectRatio();
                });

                Storage::disk('public')->put($path, (string) $image->encode());
                $pieces         = explode('/', $path);
                $last_index     = count($pieces) - 1;
                $file_name      = $pieces[$last_index];
                $image_url      = url("storage/$path");

                $request->merge([
                    'image' => $image_url,
                    'image_file_name' => $file_name
                    ]);
                
            }catch(\Throwable $e) {
                return response()->fail(['message'=>'gagal mengunggah gambar :(']);
            }

        }
    }

    private function handleChangeImage(Request $request,$report,string $hashName)
    {
        if ($request->hasFile('image_file')) {
            $file_name  = $report->image_file_name;
            $full_path  = storage_path("app/public/$hashName/$file_name");
            if (file_exists($full_path)) {
                unlink($full_path);
                $this->handleUploadImage($request,$hashName);
            }
        }
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
