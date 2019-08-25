<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PDF;

use App\User;
use BajakLautMalaka\PmiRelawan\Volunteer;
use BajakLautMalaka\PmiRelawan\Qualification;
use BajakLautMalaka\PmiRelawan\Membership;
use BajakLautMalaka\PmiRelawan\Subdistrict;
use BajakLautMalaka\PmiRelawan\City;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreVolunteerRequest;

class VolunteerApiController extends Controller
{
    protected $paginate;

    public function __construct()
    {
        $this->paginate = 10;
    }

    public function index(Request $request, Volunteer $volunteer)
    {
        $volunteer = $this->handleApproved($request, $volunteer);
        $volunteer = $this->handleVolunteerType($request, $volunteer);
        $volunteer = $this->handleVolunteerSubType($request, $volunteer);
        $volunteer = $this->handleVolunteerCity($request, $volunteer);
        $volunteer = $this->handleVolunteerUnit($request, $volunteer);
        $volunteer = $this->handleVolunteerSubdistrict($request, $volunteer);
        $volunteer = $this->handleSearchKeyword($request, $volunteer);
        $admins = $volunteer->with('unit.membership.parentMember')->with('qualifications')->paginate();

        return response()->success(compact('admins'));
    }

    private function handleApproved(Request $request, $volunteer)
    {
        if ($request->has('v')) {
            $volunteer = $volunteer->whereVerified($request->v);
        }

        return $volunteer;
    }

    private function handleVolunteerType(Request $request, $volunteer)
    {
        if ($request->has('t')) {
            $membershipId = null;
            $membership = Membership::where('name', $request->t)->first();
            if ($membership)
                $membershipId = $membership->id;

            $volunteer = $volunteer->whereHas('unit.membership', function ($q) use ($membershipId) {
                $q->where('parent_id', $membershipId);
            });
        }
        return $volunteer;
    }

    private function handleVolunteerSubType(Request $request, $volunteer)
    {
        if ($request->has('st')) {
            $membershipId = null;
            $membership = Membership::where('name', $request->st)->first();
            if ($membership)
                $membershipId = $membership->id;

            $volunteer = $volunteer->whereHas('unit.membership', function ($q) use ($membershipId) {
                $q->where('id', $membershipId);
            });
        }
        return $volunteer;
    }

    private function handleVolunteerCity(Request $request, $volunteer)
    {
        if ($request->has('c')) {
            $city = City::find($request->c);
            $volunteer = $volunteer->where('city', $city->name);
        }
        return $volunteer;
    }
    
    private function handleVolunteerSubdistrict(Request $request, $volunteer)
    {
        if ($request->has('sd')) {
            $subdistrict = Subdistrict::find($request->sd);
            $volunteer = $volunteer->where('subdistrict', $subdistrict->name);
        }
        return $volunteer;
    }

    private function handleVolunteerUnit(Request $request, $volunteer)
    {
        if ($request->has('u')) {
            $volunteer = $volunteer->whereHas('unit', function ($q) use ($request) {
                $q->where('id', $request->u);
            });
        }
        return $volunteer;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVolunteerRequest $request)
    {
        $user = null;
        $request->merge(['password' => bcrypt($request->password)]);
        DB::transaction(function () use ($request, &$user) {
            $user = User::create($request->only('name','email','password'));
            $this->createVolunteer($request, $user);
            //return asset((Storage::url($path)));
        });
        
        if($user) {
            return response()->success([
                'access_token'=>$user->createToken('PMI')->accessToken
            ]);
        }
        
    }
    
    private function createVolunteer(StoreVolunteerRequest $request, User $user)
    {
        $volunteer = new Volunteer;
        $volunteer->fill($request->except('email','password','password_confirmation'));
        $volunteer->image = $request->image->store('volunteers','public');
        $volunteer->user_id = $user->id;
        $volunteer->save();
        $volunteer->qualifications()->saveMany(
            collect($request->qualifications)->map(function($qualification, $key) {
                return new Qualification([
                    'description'=>$qualification['description'], 
                    'category'=>$qualification['category']]) ;
            })->all()
        );
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
        $volunteer->unit;
        $volunteer->unit->membership;
        $volunteer->unit->membership->parentMember;
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
        $volunteer->unit->membership->parentMember;
        return response()->success($volunteer);
    }

    public function destroy(Volunteer $volunteer)
    {
        $volunteer->delete();
        return response()->success($volunteer);
    }

    public function approveVolunteer(Request $request, Volunteer $volunteer)
    {
        $volunteer->approveVolunteer();
        if ($request->status == 3) {
            $volunteer->delete();
        }
        return response()->success($volunteer);
    }
}
