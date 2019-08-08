<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use BajakLautMalaka\PmiRelawan\Province;
use Illuminate\Http\Request;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreProvinceRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateProvinceRequest;

class ProvinceApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Province $province)
    {
        $province = $this->handleSearch($request,$province);
        $province = $this->handleOrder($request,$province);
        $province = $province->with('cities.subdistricts.urbanVillages');
        $province = $province->paginate();
        return response()->success($province);
    }

    public function handleSearch(Request $request,$province)
    {
        if ($request->has('s')) {
            $province = $province->where('name','like','%'.$request->s.'%');
        }
        return $province;
    }

    public function handleOrder(Request $request, $province)
    {
        if ($request->has('ob')) {
            $sort_direction = 'asc';
            if ($request->has('od')) {
                if (in_array($request->od, ['asc', 'desc'])) {
                    $sort_direction = $request->od;
                }
                $province = $province->orderBy($request->ob, $sort_direction);
            }
        }
        return $province;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProvinceRequest $request)
    {
        $province = Province::create($request->except('_token'));
        $province->with('cities.subdistricts.urbanVillages');
        return response()->success($province);
    }

    /**
     * Display the specified resource.
     *
     * @param  Province $province
     * @return \Illuminate\Http\Response
     */    
    public function show(Province $province)
    {
        if (isset($province->cities)) {
            foreach ($province->cities as $key => $value) {
                if (isset($value->subdistricts)) {
                    foreach ($value->subdistricts as $index => $sub) {
                        if (isset($sub->urbanVillages)) {
                            $sub->urbanVillages;
                        }
                    }
                }
            }
        }
        return response()->success($province);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Province $province
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProvinceRequest $request, Province $province)
    {
        $province->update($request->except('_token','_method'));
        if (isset($province->cities)) {
            foreach ($province->cities as $key => $value) {
                if (isset($value->subdistricts)) {
                    foreach ($value->subdistricts as $index => $sub) {
                        if (isset($sub->urbanVillages)) {
                            $sub->urbanVillages;
                        }
                    }
                }
            }
        }
        return response()->success($province);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Province $province)
    {
        $message = 'deleted';
        if ($province->cities->count() > 0) {
            $message = 'please delete this items first :';
            foreach ($province->cities as $key => $value) {
                $message .='['.$value->id.'] '.$value->name;
            }
            return response()->success(['message'=>$message]);
        }else{
            $province->delete();
            return response()->success($province);
        }
    }
}
