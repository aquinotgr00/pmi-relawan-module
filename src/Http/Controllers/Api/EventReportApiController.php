<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use BajakLautMalaka\PmiRelawan\EventReport;
use BajakLautMalaka\PmiRelawan\Volunteer;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreEventReportRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateEventReportRequest;
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
        $report = $this->handleOrder($request,$report);
        $report = $this->handleApprovedStatus($request,$report);
        $report = $this->handleEmergencyStatus($request,$report);
        $report = $this->handleArchivedStatus($request,$report);
        $report = $report->withCount([
            'participants',
            'participants AS approved_participants'=>function($query) {
                $query->where('approved',true);
            }]
        )->with(['participants','activities','village.subdistrict.city']);
        $report = $report->paginate();

        return response()->success($report);
    }

    private function handleSearch(Request $request,$report)
    {
        if ($request->has('s')) {
            $report = $report->where('title','like','%'.$request->s.'%')
            ->orWhere('description','like','%'.$request->s.'%')
            ->orWhere('type','like','%'.$request->s.'%')
            ->orWhere('location','like','%'.$request->s.'%');
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
            $report = $report->whereRaw('archived = id')->withTrashed();
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
            $rsvp->volunteer_id = Auth::id();
        }
        $rsvp->image = $request->image_file->store('event-reports','public');
        
        // resize to 320x240
        $resized = Image::make($request->image_file)->resize(320, 240, function ($constraint) {
            $constraint->aspectRatio();
        })->encode();
        Storage::disk('public')->put($rsvp->image, $resized);
        
        // keep original file
        $request->image_file->store('event-reports/originals','public');
        $rsvp->save();
        response()->success($rsvp);
    }

    /**
     * Display the specified resource.
     *
     * @param  \BajakLautMalaka\PmiRelawan\EventReport  $event
     * @return \Illuminate\Http\Response
     */
    public function show(EventReport $report)
    {
        $report->participants;
        $report->activities;
        if (isset($report->village)) {
            $report->village->subdistrict->city->province;
        }
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
        $this->handleChangeImage($request,$report,'event-images');
        
        if ($request->has('approved')) {
            $report->approved = $request->approved;
            if(!$request->approved) {
                $report->archived = $report->id;
            }
            $report->save();
        } else {
            $report->update($request->except('_method'));
        }
        $report->participants;
        $report->activities;
        if (isset($report->village)) {
            $report->village->subdistrict->city->province;
        }
        return response()->success($report);
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
