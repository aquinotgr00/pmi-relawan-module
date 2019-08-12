<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Intervention\Image\Facades\Image;
use BajakLautMalaka\PmiRelawan\EventReport;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreEventReportRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateEventReportRequest;

class EventReportApiController extends Controller
{
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
        $report = $report->with('partisipants')->with('activities');
        $report = $report->paginate();

        return response()->success($report);
    }

    public function handleSearch(Request $request,$report)
    {
        if ($request->has('s')) {
            $report = $report->where('title','like','%'.$request->s.'%')
            ->orWhere('description','like','%'.$request->s.'%')
            ->orWhere('type','like','%'.$request->s.'%')
            ->orWhere('location','like','%'.$request->s.'%');
        }
        return $report;
    }

    public function handleOrder(Request $request,$report)
    {
        if ($request->has('ob')) {
            // sort direction (default = asc)
            $sort_direction = 'asc';
            if ($request->has('od')) {
                if (in_array($request->od, ['asc', 'desc'])) {
                    $sort_direction = $request->od;
                }
            }
            $report = $report->orderBy($request->ob, $sort_direction);
        }
        return $report;
    }

    public function handleApprovedStatus(Request $request,$report)
    {
        if ($request->has('ap')) {
            $report = $report->where('approved',$request->ap);
            $report = $report->where('archived',0);
        }elseif ($request->has('p')) {
            $report = $report->whereNull('approved');
        }
        return $report;
    }

    public function handleEmergencyStatus(Request $request,$report)
    {
        if ($request->has('e')) {
            $report = $report->where('emergency',$request->e);
        }
        return $report;
    }

    public function handleArchivedStatus(Request $request,$report)
    {
        if ($request->has('ar')) {
            $report = $report->where('archived',$request->ar);
        }
        return $report;
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
    public function store(StoreEventReportRequest $request)
    {
        $this->handleUploadImage($request,'event-images');
        $event_reports = EventReport::create($request->except('imaga_file','_token'));
        return response()->success($event_reports);
    }

    /**
     * Display the specified resource.
     *
     * @param  \BajakLautMalaka\PmiRelawan\EventReport  $event
     * @return \Illuminate\Http\Response
     */
    public function show(EventReport $report)
    {
        $report->partisipants;
        $report->activities;
        return response()->success($report);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \BajakLautMalaka\PmiRelawan\EventReport  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(EventReport $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
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
            $report->save();
        }elseif ($request->has('archived')) {
            $report->archived = (!$report->archived);
            $report->save();
        }else{
            $report->update($request->except('_method'));
        }
        $report->partisipants;
        $report->activities;
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

    public function handleUploadImage(Request $request,string $hashName)
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

    public function handleChangeImage(Request $request,$report,string $hashName)
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