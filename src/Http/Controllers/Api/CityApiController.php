<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use BajakLautMalaka\PmiRelawan\City;
use Illuminate\Http\Request;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreCityRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateCityRequest;

class CityApiController extends Controller
{
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
        $city = $city->with('subdistricts');
        $city = $city->paginate();
        return response()->success($city);
    }

    public function handleSearch(Request $request,$city)
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

    public function handleByProvinceId(Request $request,$city)
    {
        if ($request->has('p_id')) {
            $city = $city->where('province_id',$request->p_id);
        }
        return $city;
    }

    public function handleOrder(Request $request, $city)
    {
        if ($request->has('ob')) {
            $sort_direction = 'asc';
            if ($request->has('od')) {
                if (in_array($request->od, ['asc', 'desc'])) {
                    $sort_direction = $request->od;
                }
            }
            $city = $city->orderBy($request->ob, $sort_direction);
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
        $city = City::create($request->except('_token'));
        $city->province;
        return response()->success($city);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $city = City::with('province')->find($id);
        if (is_null($city)) {
            return response()->fail($city);
        }else{
            return response()->success($city);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCityRequest $request, int $id)
    {
        $city = City::with('province')->find($id);
        if (is_null($city)) {
            return response()->fail($city);
        }else{
            $city->update($request->except('id','_token'));
            return response()->success($city);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $city = City::find($id);
        if (is_null($city)) {
            return response()->fail(['message'=> false]);
        }else{
            $message = 'deleted';
            if ($city->subdistricts->count() > 0) {
                $message = 'please delete this items first :';
                foreach ($city->subdistricts as $key => $value) {
                    $message .='['.$value->id.'] '.$value->name;
                }
            }else{
                $city->delete();
            }
            return response()->success(['message'=>$message]);
        }
    }
}
