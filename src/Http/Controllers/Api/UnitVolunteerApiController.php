<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use BajakLautMalaka\PmiRelawan\UnitVolunteer;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreUnitRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateUnitRequest;

class UnitVolunteerApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,UnitVolunteer $unit)
    {
        $unit = $this->handleByCityId($request,$unit);
        $unit = $this->handleBySubId($request,$unit);
        $unit = $this->handleOrder($request,$unit);
        $unit = $unit->with('membership');
        $unit = $unit->with('city');
        $unit = $unit->paginate();
        return response()->success($unit);
    }

    public function handleSearch(Request $request,$unit)
    {
        if ($request->has('s')) {
            $unit = $unit->where('name','like','%'.$request->s.'%')
            ->orWhereHas('membership', function ($query) use ($request) {
                $query->where('name','like','%'.$request->s.'%');
            })
            ->orWhereHas('city', function ($query) use ($request) {
                $query->where('name','like','%'.$request->s.'%');
            });
        }
        return $unit;
    }

    public function handleByCityId(Request $request,$unit)
    {
        if ($request->has('c_id')) {
            $unit = $unit->where('city_id',$request->c_id);
        }
        return $unit;
    }

    public function handleBySubId(Request $request,$unit)
    {
        if ($request->has('s_id')) {
            $unit = $unit->where('submember_type_id',$request->s_id);
        }
        return $unit;
    }

    public function handleOrder(Request $request, $unit)
    {
        if ($request->has('ob')) {
            $sort_direction = 'asc';
            if ($request->has('od')) {
                if (in_array($request->od, ['asc', 'desc'])) {
                    $sort_direction = $request->od;
                }
            }
            $unit = $unit->orderBy($request->ob, $sort_direction);
        }
        return $unit;
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
    public function store(StoreUnitRequest $request)
    {
        $unit = UnitVolunteer::create($request->except('_token'));
        return response()->success($unit);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UnitVolunteer  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(UnitVolunteer $unit)
    {
        $unit->membership->type;
        $unit->city;
        return response()->success($unit);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UnitVolunteer  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(UnitVolunteer $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UnitVolunteer  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUnitRequest $request, UnitVolunteer $unit)
    {
        $unit->update($request->except('_token','_method'));
        $unit->membership->type;
        $unit->city;
        return response()->success($unit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UnitVolunteer  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(UnitVolunteer $unit)
    {
        $unit->delete();
        return response()->success($unit);
    }
}
