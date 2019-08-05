<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use BajakLautMalaka\PmiRelawan\Subdistrict;
use Illuminate\Http\Request;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreSubdistrictRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateSubdistrictRequest;

class SubdistrictApiController extends Controller
{
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
        $subdistrict = $subdistrict->with('urbanVillages');
        $subdistrict = $subdistrict->get();
        return response()->success($subdistrict);
    }

    public function handleSearch(Request $request,$subdistrict)
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

    public function handleByCityId(Request $request,$subdistrict)
    {
        if ($request->has('c_id')) {
            $subdistrict = $subdistrict->where('city_id',$request->c_id);
        }
        return $subdistrict;
    }

    public function handleOrder(Request $request, $subdistrict)
    {
        if ($request->has('ob')) {
            // sort direction (default = asc)
            $sort_direction = 'asc';
            if ($request->has('od')) {
                if (in_array($request->od, ['asc', 'desc'])) {
                    $sort_direction = $request->od;
                }
            }
            $subdistrict = $subdistrict->orderBy($request->ob, $sort_direction);
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
        $subdistrict = Subdistrict::create($request->except('_token'));
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
    public function show(int $id)
    {
        $subdistrict = Subdistrict::with('city.province')->with('urbanVillages')->find($id);
        if (is_null($subdistrict)) {
            return response()->fail($subdistrict);
        }else{
            return response()->success($subdistrict);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubdistrictRequest $request, int $id)
    {
        $subdistrict = Subdistrict::with('city.province')->with('urbanVillages')->find($id);
        if (is_null($subdistrict)) {
            return response()->fail($subdistrict);
        }else{
            $subdistrict->update($request->except('id','_token'));
            return response()->success($subdistrict);
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
        $subdistrict = Subdistrict::find($id);
        if (is_null($subdistrict)) {
            return response()->fail(['message'=> false]);
        }else{
            $message = 'deleted';
            if ($subdistrict->urbanVillages->count() > 0) {
                $message = 'please delete this items first :';
                foreach ($subdistrict->urbanVillages as $key => $value) {
                    $message .='['.$value->id.'] '.$value->name;
                }
            }else{
                $subdistrict->delete();
            }
            return response()->success(['message'=>$message]);
        }
    }
}
