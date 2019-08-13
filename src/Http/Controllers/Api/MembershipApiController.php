<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use BajakLautMalaka\PmiRelawan\Membership;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreMembershipRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateMembershipRequest;

class MembershipApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Membership $membership)
    {
        $membership = $this->handleSearch($request,$membership);
        $membership = $this->handleOrder($request,$membership);
        $membership = $this->handleByParent($request,$membership);
        $membership = $membership->paginate();
        return response()->success($membership);
    }

    private function handleSearch(Request $request,$membership)
    {
        if ($request->has('s')) {
            $membership = $membership->where('name','like','%'.$request->s.'%')
            ->orWhere('code','like','%'.$request->s.'%');
        }
        return $membership;
    }

    private function handleOrder(Request $request, $membership)
    {
        if ($request->has('ob')) {
            $sort_direction = 'asc';
            if ($request->has('od')) {
                if (in_array($request->od, ['asc', 'desc'])) {
                    $sort_direction = $request->od;
                }
            }
            $membership = $membership->orderBy($request->ob, $sort_direction);
        }
        return $membership;
    }

    private function handleByParent(Request $request, $membership)
    {
        if ($request->has('p_id')) {
            $membership = $membership->where('parent_id',$request->p_id);
            $membership = $membership->with('parentMember');
        }elseif ($request->has('sub')) {
            $membership = $membership->where('parent_id','<>',NULL);
            $membership = $membership->with('parentMember');
        }else{
            $membership = $membership->whereNull('parent_id');
            $membership = $membership->with('subMember');
        }
        return $membership;
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
    public function store(StoreMembershipRequest $request)
    {
        $membership = Membership::create($request->except('_token'));
        return response()->success($membership);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function show(Membership $membership)
    {
        if (isset($membership->subMember->units)) {
            $membership->subMember;
            $membership->subMember->units;
        }
        return response()->success($membership);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function edit(Membership $membership)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMembershipRequest $request, Membership $membership)
    {
        $membership->update($request->except('_token','_method'));
        
        if (isset($membership->subMember->units)) {
            $membership->subMember;
            $membership->subMember->units;
        }
        return response()->success($membership);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function destroy(Membership $Membership)
    {
        $membership->delete();
        return response()->success($membership);
    }
}
