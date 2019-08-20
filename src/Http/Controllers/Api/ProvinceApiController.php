<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use BajakLautMalaka\PmiRelawan\Province;
use Illuminate\Http\Request;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreProvinceRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateProvinceRequest;
use BajakLautMalaka\PmiRelawan\Traits\RelawanTrait;

class ProvinceApiController extends Controller
{
    use RelawanTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Province $province)
    {
        $province = $this->handleSearch($request,$province);
        $province = $this->handleOrder($request,$province);
        $province = $this->handlePaginate($request,$province);
        return response()->success($province);
    }

    private function handleSearch(Request $request,$province)
    {
        if ($request->has('s')) {
            $province = $province->where('name','like','%'.$request->s.'%');
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
        $province = Province::create($request->all());
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
        $province->with('cities.subdistricts.villages');
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
        $province->update($request->all());
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
        try {
            $province->delete();
            return response()->success($province);    
        } catch ( \Illuminate\Database\QueryException $e) {
            $collection = collect(['message' => 'Error! provinsi memiliki sub item']);
            $province   = $collection->merge($province);
            return response()->fail($province);    
        }
    }
}
