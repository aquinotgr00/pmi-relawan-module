<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

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
            $volunteer = $volunteer->where('sub_type', $request->s);
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

    private function handleSearchKeyword(Request $request, $volunteer)
    {
        if ($request->has('s')) {
            $volunteer = $volunteer->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->s.'%');
            });
        }
        return $volunteer;
    }

    public function print(Request $request)
    {
        return response()->json($request->all());
    }

    public function update(Request $request, $id)
    {
        return response()->json($request->all());
    }

    public function delete($id)
    {
        return response()->json(['message' => 'deleted.']);
    }
}