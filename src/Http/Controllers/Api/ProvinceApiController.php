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
        $province = $province->get();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $province = Province::with('cities.subdistricts.urbanVillages')->find($id);
        if (is_null($province)) {
            return response()->fail($province);
        }else{
            return response()->success($province);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProvinceRequest $request, int $id)
    {
        $province = Province::with('cities.subdistricts.urbanVillages')->find($id);
        if (is_null($province)) {
            return response()->fail($province);
        }else{
            $province->update($request->except('id','_token'));
            return response()->success($province);
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
        $province = Province::find($id);
        if (is_null($province)) {
            return response()->fail(['message'=> false]);
        }else{
            $message = 'deleted';
            if ($province->cities->count() > 0) {
                $message = 'please delete this items first :';
                foreach ($province->cities as $key => $value) {
                    $message .='['.$value->id.'] '.$value->name;
                }
            }else{
                $province->delete();
            }
            return response()->success(['message'=>$message]);
        }
    }
}
