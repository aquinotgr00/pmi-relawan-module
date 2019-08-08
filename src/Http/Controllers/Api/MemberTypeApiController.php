<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use BajakLautMalaka\PmiRelawan\MemberType;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreTypeRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateTypeRequest;
use Illuminate\Http\Request;

class MemberTypeApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,MemberType $type)
    {
        $type = $this->handleSearch($request,$type);
        $type = $this->handleOrder($request,$type);
        $type = $type->with('subtypes.units');
        $type = $type->paginate();
        return response()->success($type);
    }

    public function handleSearch(Request $request,$type)
    {
        if ($request->has('s')) {
            $type = $type->where('name','like','%'.$request->s.'%')
            ->orWhere('code','like','%'.$request->s.'%');
        }
        return $type;
    }

    public function handleOrder(Request $request, $type)
    {
        if ($request->has('ob')) {
            $sort_direction = 'asc';
            if ($request->has('od')) {
                if (in_array($request->od, ['asc', 'desc'])) {
                    $sort_direction = $request->od;
                }
            }
            $type = $type->orderBy($request->ob, $sort_direction);
        }
        return $type;
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
    public function store(StoreTypeRequest $request)
    {
        $type = MemberType::create($request->except('_token'));
        return response()->success($type);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MemberType  $type
     * @return \Illuminate\Http\Response
     */
    public function show(MemberType $type)
    {
        if (isset($type->subtypes)) {
            $type->subtypes;
            foreach ($type->subtypes as $key => $value) {
                if (isset($value->units)) {
                    $value->units;
                }
            }
        }
        return response()->success($type);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MemberType  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(MemberType $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MemberType  $type
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTypeRequest $request, MemberType $type)
    {
        $type->update($request->except('_token','_method'));
        if (isset($type->subtypes)) {
            $type->subtypes;
            foreach ($type->subtypes as $key => $value) {
                if (isset($value->units)) {
                    $value->units;
                }
            }
        }
        return response()->success($type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MemberType  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(MemberType $type)
    {
        $type->delete();
        return response()->success($type);
    }
}
