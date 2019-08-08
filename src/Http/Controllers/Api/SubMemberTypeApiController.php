<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use BajakLautMalaka\PmiRelawan\SubMemberType;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreSubTypeRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateSubTypeRequest;

class SubMemberTypeApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,SubMemberType $subtype)
    {
        $subtype = $this->handleSearch($request,$subtype);
        $subtype = $this->handleByTyepeId($request,$subtype);
        $subtype = $this->handleOrder($request,$subtype);
        $subtype = $subtype->with('type');
        $subtype = $subtype->with('units');
        $subtype = $subtype->paginate();
        return response()->success($subtype);
    }

    public function handleSearch(Request $request,$subtype)
    {
        if ($request->has('s')) {
            $subtype = $subtype->where('name','like','%'.$request->s.'%')
            ->orWhere('code','like','%'.$request->s.'%')
            ->orWhereHas('type', function ($query) use ($request) {
                $query->where('name','like','%'.$request->s.'%');
            });
        }
        return $subtype;
    }

    public function handleByTyepeId(Request $request,$subtype)
    {
        if ($request->has('t_id')) {
            $subtype = $subtype->where('member_type_id',$request->t_id);
        }
        return $subtype;
    }

    public function handleOrder(Request $request, $subtype)
    {
        if ($request->has('ob')) {
            $sort_direction = 'asc';
            if ($request->has('od')) {
                if (in_array($request->od, ['asc', 'desc'])) {
                    $sort_direction = $request->od;
                }
            }
            $subtype = $subtype->orderBy($request->ob, $sort_direction);
        }
        return $subtype;
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
    public function store(StoreSubTypeRequest $request)
    {
        $subtype = SubMemberType::create($request->except('_token'));
        return response()->success($subtype);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SubMemberType  $subtype
     * @return \Illuminate\Http\Response
     */
    public function show(SubMemberType $subtype)
    {
        if (isset($subtype->units)) {
            $subtype->units;
        }
        $subtype->type;
        return response()->success($subtype);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SubMemberType  $subtype
     * @return \Illuminate\Http\Response
     */
    public function edit(SubMemberType $subtype)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SubMemberType  $subtype
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubTypeRequest $request, SubMemberType $subtype)
    {
        $subtype->update($request->except('_token','_method'));
        if (isset($subtype->units)) {
            $subtype->units;
        }
        $subtype->type;
        return response()->success($subtype);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SubMemberType  $subtype
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubMemberType $subtype)
    {
        $subtype->delete();
        return response()->success($subtype);
    }
}
