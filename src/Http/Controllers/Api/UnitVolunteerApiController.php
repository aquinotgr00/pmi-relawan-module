<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use BajakLautMalaka\PmiRelawan\Membership;
use BajakLautMalaka\PmiRelawan\UnitVolunteer;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreUnitRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateUnitRequest;
use BajakLautMalaka\PmiRelawan\Traits\RelawanTrait;
use BajakLautMalaka\PmiRelawan\Events\UnitCreated;

class UnitVolunteerApiController extends Controller
{
    use RelawanTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,UnitVolunteer $unit)
    {
        $unit = $this->handleSearch($request,$unit);
        $unit = $this->handleByCityId($request,$unit);
        $unit = $this->handleByMembership($request,$unit);
        $unit = $this->handleOrder($request,$unit);
        $unit = $unit->with('membership');
        $unit = $unit->with('city');
        $unit = $this->handlePaginate($request, $unit);
        return response()->success($unit);
    }

    private function handleSearch(Request $request,$unit)
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

    private function handleByCityId(Request $request,$unit)
    {
        if ($request->has('c_id')) {
            $unit = $unit->where('city_id',$request->c_id);
        }
        return $unit;
    }

    private function handleByMembership(Request $request,$unit)
    {
        if ($request->has('p_id')) {
            $membership = Membership::with('subMember')->where('parent_id',$request->p_id)->pluck('id');
            $unit = $unit->where('membership_id',$request->p_id)->orWhereIn('membership_id',$membership);
        }
        return $unit;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUnitRequest $request)
    {
        $unit = UnitVolunteer::create($request->only('name','city_id','membership_id'));
        if ($unit) {
            broadcast(new UnitCreated($unit));
        }
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
        if (isset($unit->membership)) {
            $unit->membership;
        }
        $unit->city;
        return response()->success($unit);
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
        $unit->update($request->only('name','city_id','membership_id'));
        if (isset($unit->membership)) {
            $unit->membership;
        }
        if (isset($unit->subMembership)) {
            $unit->subMembership;
        }
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
        try {
            $unit->delete();
            return response()->success($unit);    
        } catch ( \Illuminate\Database\QueryException $e) {
            $collection = collect(['message' => 'Error! Unit masih memiliki anggota']);
            $unit       = $collection->merge($unit);
            return response()->fail($unit);    
        }
    }
}
