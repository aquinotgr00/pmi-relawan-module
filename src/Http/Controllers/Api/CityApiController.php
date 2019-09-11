<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use BajakLautMalaka\PmiRelawan\City;
use Illuminate\Http\Request;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreCityRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateCityRequest;
use BajakLautMalaka\PmiRelawan\Traits\RelawanTrait;

class CityApiController extends Controller
{
    use RelawanTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, City $city)
    {
        $city = $this->handleByProvinceId($request,$city);
        $city = $this->handleSearch($request,$city);
        $city = $this->handleOrder($request,$city);
        $city = $city->with('province');
        $city = $city->with('units');
        $city = $city->with('subdistricts.villages');
        $city = $this->handlePaginate($request,$city);
        return response()->success($city);
    }

    private function handleSearch(Request $request,$city)
    {
        if ($request->has('s')) {
            $city = $city->where('name','like','%'.$request->s.'%')
            ->orWhere('type','like','%'.$request->s.'%')
            ->orWhere('postal_code','like','%'.$request->s.'%')
            ->orWhereHas('province', function ($query) use ($request) {
                $query->where('name','like','%'.$request->s.'%');
            });

        }
        return $city;
    }

    private function handleByProvinceId(Request $request,$city)
    {
        if ($request->has('p_id')) {
            $city = $city->where('province_id',$request->p_id);
        }
        return $city;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        $city = City::create($request->all());
        $city->province;
        return response()->success($city);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        
        //$city->province;
        /*if (isset($city->subdistricts)) {
            $city->subdistricts;
        }*/
        return response()->success($city);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCityRequest $request, City $city)
    {
        $city->update($request->all());
        $city->province;
        return response()->success($city);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        try {
            $city->delete();
            return response()->success($city);    
        } catch ( \Illuminate\Database\QueryException $e) {
            $collection = collect(['message' => 'Error! Kabupaten/Kota memiliki sub item']);
            $city       = $collection->merge($city);
            return response()->fail($city);    
        }
    }
}
