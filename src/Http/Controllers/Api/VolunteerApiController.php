<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use PDF;

use BajakLautMalaka\PmiRelawan\Volunteer;

class VolunteerApiController extends Controller
{
    protected $paginate;

    public function __construct()
    {
        $this->paginate = 10;
    }

    public function index(Request $request, Volunteer $volunteer)
    {
        $volunteer = $this->handleVolunteerType($request, $volunteer);
        $volunteer = $this->handleVolunteerSubType($request, $volunteer);
        $volunteer = $this->handleVolunteerCity($request, $volunteer);
        $volunteer = $this->handleVolunteerUnit($request, $volunteer);
        $volunteer = $this->handleSearchKeyword($request, $volunteer);

        return response()->success($volunteer->paginate());
    }

    private function handleVolunteerType(Request $request, $volunteer)
    {
        if ($request->has('t')) {
            $volunteer = $volunteer->where('type', $request->t);
        }
        return $volunteer;
    }

    private function handleVolunteerSubType(Request $request, $volunteer)
    {
        if ($request->has('st')) {
            $volunteer = $volunteer->where('sub_type', $request->st);
        }
        return $volunteer;
    }

    private function handleVolunteerCity(Request $request, $volunteer)
    {
        if ($request->has('c')) {
            $volunteer = $volunteer->where('city', $request->c);
        }
        return $volunteer;
    }

    private function handleVolunteerUnit(Request $request, $volunteer)
    {
        if ($request->has('u')) {
            $volunteer = $volunteer->where('unit', $request->u);
        }
        return $volunteer;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateVolunteer $request)
    {
        $path = $request->image->store('volunteers','public');
        User::create($request->only('name','email','password'));
        $volunteer = new Volunteer;
        
        return asset((Storage::url($path)));
    }

    private function handleSearchKeyword(Request $request, $volunteer)
    {
        if ($request->has('s')) {
            $volunteer = $volunteer->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->s.'%');
            });
        }
        return $volunteer;
    }

    public function show(Volunteer $volunteer)
    {
        return response()->success($volunteer);
    }

    public function print(Request $request, Volunteer $volunteers)
    {
        $pdfTitle = 'Volunteers';
        // get data with it's all filter
        $volunteers = $this->handleVolunteerType($request, $volunteers);
        $volunteers = $this->handleVolunteerSubType($request, $volunteers);
        $volunteers = $this->handleVolunteerCity($request, $volunteers);
        $volunteers = $this->handleVolunteerUnit($request, $volunteers);
        $volunteers = $this->handleSearchKeyword($request, $volunteers);

        $volunteers = $volunteers->get();
        $html = view('volunteer::table-volunteers', [
            'volunteers' => $volunteers
        ])->render();

        PDF::SetTitle($pdfTitle);
        PDF::AddPage();
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(public_path('export-volunteers.pdf'), 'f');

        // return print
        return response()->success(['url' => url('export-volunteers.pdf')]);
    }

    public function printProfile(Volunteer $volunteer)
    {
        $volunteer = $volunteer->first();
        $pdfTitle = 'Volunteer Profile';
        $html = view('volunteer::profile-volunteer', [
            'volunteer' => $volunteer
        ])->render();

        PDF::SetTitle($pdfTitle);
        PDF::AddPage();
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(public_path('export-profile-volunteer.pdf'), 'f');

        return response()->success(['url' => url('export-profile-volunteer.pdf')]);
    }

    public function update(Request $request, Volunteer $volunteer)
    {
        if ($request->has('image_file')) {
            $image      = $request->file('image_file');
            $extension  = $image->getClientOriginalExtension();
            $file_name  = $image->getFilename() . '.' . $extension;

            Storage::disk('public')->put($file_name,  File::get($image));

            $image_url = url('storage/' . $file_name);
            $volunteer->image = $image_url;
        }

        $volunteer->update($request->input());
        return response()->success($volunteer);
    }

    public function destroy(Volunteer $volunteer)
    {
        $volunteer->delete();
        return response()->success($volunteer);
    }
}