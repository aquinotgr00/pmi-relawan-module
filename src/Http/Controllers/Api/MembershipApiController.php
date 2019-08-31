<?php

namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use BajakLautMalaka\PmiRelawan\Membership;
use BajakLautMalaka\PmiRelawan\UnitVolunteer;
use BajakLautMalaka\PmiRelawan\Http\Requests\StoreMembershipRequest;
use BajakLautMalaka\PmiRelawan\Http\Requests\UpdateMembershipRequest;
use BajakLautMalaka\PmiRelawan\Traits\RelawanTrait;
use BajakLautMalaka\PmiRelawan\Events\MembershipCreated;

class MembershipApiController extends Controller
{
  use RelawanTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Membership $membership)
    {
      $membership = $this->handleByLevel($request,$membership);
      $membership = $this->handleSearch($request,$membership);
      $membership = $this->handleOrder($request,$membership);
      $membership = $membership->with('subMember');
      $membership = $this->handlePaginate($request,$membership);
      $membership = $this->handleCircularCollection($request,$membership);
      return response()->success($membership);
    }

    private function handleSearch(Request $request,$membership)
    {
      if ($request->has('s')) {
        $membership = $membership->where('name','like','%'.$request->s.'%')
        ->orWhereHas('subMember', function ($query) use ($request) {
          $query->where('name','like','%'.$request->s.'%');
        })
        ->orWhere('code','like','%'.$request->s.'%');
      }
      return $membership;
    }

    private function handleByLevel(Request $request,$membership)
    {
      if ($request->has('l')) {
        if ($request->l > 0) {
          $membership = $membership->where('parent_id',$request->l);
        }else{
          $membership = $membership->whereNull('parent_id');    
        }
      }else{
        $membership = $membership->whereNull('parent_id')->with('subMember');    
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
      $membership = Membership::create($request->all());
      if ($membership) {
        broadcast(new MembershipCreated($membership));
      }
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
      $membership->update($request->only('name','parent_id'));
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
    public function destroy(Membership $membership)
    {
      $membership = $membership->delete();
      return response()->success($membership);
    }

    private function handleCircularCollection(Request $request,$membership)
    {
      if ($request->has('sub')) {
        $data = [];
        foreach ($membership as $key => $value) {
          if ($value->subMember->count() > 0) {
            $parent = collect([
              'circular' => $value->name,
              'id' => $value->id,
              'parent_id' => $value->parent_id,
              'name' => $value->name,
              'code' => $value->code,
              'created_at' => $value->created_at,
              'updated_at' => $value->updated_at,
              ]);
            array_push($data, $parent);
            foreach ($value->subMember as $index => $sub) {
              $member     = $value->name;
              $member    .=" > ".$sub->name;
              $obj = collect(['circular' => $member]);
              $sub = $obj->merge($sub);
              $data[] = $sub;
            }
          }else{
            $data[] = collect([
              'circular' => $value->name,
              'id' => $value->id,
              'parent_id' => $value->parent_id,
              'name' => $value->name,
              'code' => $value->code,
              'created_at' => $value->created_at,
              'updated_at' => $value->updated_at,
              ]);
          }
        }
        $ori  = collect($membership);
        if (isset($ori['data'])) {
          $mixed       = collect(['data'=>$data]);
          $membership  = $ori->merge($mixed);
        }else{
          $membership  = collect($data);
        }
      }   

      return $membership;
    }

    public function getAmountVolunteers(Membership $membership)
    {
      $membership = $membership->whereNull('parent_id')->with('subMember')->get();
      
      $membership = $membership->map(function ($member) {

        $subMember = $member->subMember->map(function($sub) {

          $send     = $sub->units->reduce(function ($count, $units) use($sub) {
            
            $f      = $units->volunteers->where('gender','female')->count();
            $m      = $units->volunteers->where('gender','male')->count();
            $all    = $f + $m;
            
            $count = [
            'title' => $sub->name,
            'f' => $f,
            'm' => $m,
            'all' => $all
            ];
            return $count;

          }, [
            'title' => $sub->name,
            'f' => 0,
            'm' => 0,
            'all' => 0
          ]);
          
          return $send;

        });
        
        $amount =  $subMember->map(function($item){
          return $item['all'];
        });

        $data = [
        'id' => $member->id,
        'title' => $member->name,
        'amount' => $amount->sum(),
        'subMember' => $subMember
        ];
        return $data;

      });

      return response()->success($membership);
    }
  }
