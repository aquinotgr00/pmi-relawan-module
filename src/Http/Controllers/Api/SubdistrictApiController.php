<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use BajakLautMalaka\PmiRelawan\Subdistrict;
use Illuminate\Http\Request;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreSubdistrictRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateSubdistrictRequest;
use BajakLautMalaka\PmiRelawan\Traits\RelawanTrait;

class SubdistrictApiController extends Controller
{
    use RelawanTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Subdistrict $subdistrict)
    {
        $subdistrict = $this->handleByCityId($request,$subdistrict);
        $subdistrict = $this->handleSearch($request,$subdistrict);
        $subdistrict = $this->handleOrder($request, $subdistrict);
        $subdistrict = $subdistrict->with('city.province');
        $subdistrict = $subdistrict->with('villages');
        $subdistrict = $this->handlePaginate($request, $subdistrict); 
        return response()->success($subdistrict);
    }

    private function handleSearch(Request $request,$subdistrict)
    {
        if ($request->has('s')) {
            $subdistrict = $subdistrict->where('name','like','%'.$request->s.'%')
            ->orWhere('type','like','%'.$request->s.'%')
            ->orWhereHas('city', function ($query) use ($request) {
                $query->where('name','like','%'.$request->s.'%');
            });

        }
        return $subdistrict;
    }

    private function handleByCityId(Request $request,$subdistrict)
    {
        if ($request->has('c_id')) {
            $subdistrict = $subdistrict->where('city_id',$request->c_id);
        }
        return $subdistrict;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubdistrictRequest $request)
    {
        $subdistrict = Subdistrict::create($request->all());
        if (isset($subdistrict->city)) {
            $subdistrict->city;
            $subdistrict->city->province;
        }
        return response()->success($subdistrict);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Subdistrict $subdistrict)
    {
        $subdistrict->city->province;
        $selection  = collect([
            'selection' => \BajakLautMalaka\PmiRelawan\City::select(['id','name'])->get()
        ]);
        $subdistrict = $selection->merge($subdistrict);
        return response()->success($subdistrict);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubdistrictRequest $request, Subdistrict $subdistrict)
    {
        $subdistrict->update($request->all());
        $subdistrict->city;
        if (isset($subdistrict->villages)) {
            $subdistrict->villages;
        }
        return response()->success($subdistrict);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subdistrict $subdistrict)
    {
        try {
            $subdistrict->delete();
            return response()->success($subdistrict);    
        } catch ( \Illuminate\Database\QueryException $e) {
            $collection     = collect(['message' => 'Error! Kecamatan memiliki sub item']);
            $subdistrict    = $collection->merge($subdistrict);
            return response()->fail($subdistrict);    
        }
    }
}
