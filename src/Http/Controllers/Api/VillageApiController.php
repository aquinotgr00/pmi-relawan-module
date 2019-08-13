<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use BajakLautMalaka\PmiRelawan\Village;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreVillageRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateVillageRequest;
use BajakLautMalaka\PmiRelawan\Traits\RelawanTrait;

class VillageApiController extends Controller
{
    use RelawanTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Village $village)
    {
        $village = $this->handleBySubId($request,$village);
        $village = $this->handleByCityId($request,$village);
        $village = $this->handleSearch($request,$village);
        $village = $this->handleOrder($request,$village);
        $village = $village->with('subdistrict.city.province');
        $village = $this->handlePaginate($request,$village);

        $filtering  = collect([
            'filter_city' => \BajakLautMalaka\PmiRelawan\City::select(['id','name'])->get(),
            'filter_subdistrict' => \BajakLautMalaka\PmiRelawan\Subdistrict::getForFiltering($request),
        ]);
        
        $village = $filtering->merge($village);

        return response()->success($village);
    }

    private function handleSearch(Request $request,$village)
    {
        if ($request->has('s')) {
            $village = $village->where('name','like','%'.$request->s.'%')
            ->orWhereHas('subdistrict', function ($query) use ($request) {
                $query->where('name','like','%'.$request->s.'%');
            });

        }
        return $village;
    }

    private function handleBySubId(Request $request,$village)
    {
        if ($request->has('s_id')) {
            $village = $village->where('subdistrict_id',$request->s_id);
        }
        return $village;
    }

    private function handleByCityId(Request $request,$village)
    {
        if ($request->has('c_id')) {
            $village = $village->whereHas('subdistrict', function ($query) use ($request) {
                $query->where('city_id','like','%'.$request->c_id.'%');
            });
        }
        return $village;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVillageRequest $request)
    {
        $village = Village::create($request->except('_token'));
        if (isset($village->subdistrict)) {
            $village->subdistrict;
            $village->subdistrict->city->province;
        }
        return response()->success($village);
    }

    /**
     * Display the specified resource.
     *
     * @param  UrbanVillage $village
     * @return \Illuminate\Http\Response
     */
    public function show(Village $village)
    {
        $village->subdistrict->city->province;
        return response()->success($village);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVillageRequest $request, Village $village)
    {
        $village->update($request->except('_token','_method'));
        $village->subdistrict->city->province;
        return response()->success($village);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Village $village)
    {
        $village->delete();
        return response()->success($village);
    }
}
