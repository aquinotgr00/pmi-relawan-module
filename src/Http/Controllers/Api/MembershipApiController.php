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
      //$membership = $this->handleSearch($request,$membership);
      $membership = $this->handleOrder($request,$membership);
      $membership = $this->handleByParentId($request,$membership);
      $membership = $this->handlePaginate($request,$membership);
      $membership = $this->handleByLevel($request,$membership);
      return response()->success($membership);
    }

    private function handleExplodeString(string $membership)
    {
      $membership = trim(preg_replace('/\s\s+/', '', $membership));
      $membership = explode('<br/>', $membership);
      $data = [];
      foreach ($membership as $key => $value) {
        $pieces = explode('^', $value);
        $id     = trim(preg_replace('/\s\s+/', '', $pieces[0]));
        $id     = intval($id);

        if ($id !== 0) {
          $name   =  (isset($pieces[1]))? trim(preg_replace('/\s\s+/', '', $pieces[1])) : '';
          $data[] = [ 'id' => $id , 'name' => $name ];
        }
      }
      return $data;
    }

    private function handleSearch(Request $request,$collection)
    {
      if ($request->has('s')) {
        $keyword      = strtolower($request->s);
        $collection   = $collection->reject(function($element) use ($keyword) {
          return mb_strpos(strtolower($element['name']), $keyword) === false;
        });
        $collection = $collection->values(); 

      }
      return $collection;
    }

    private function handleByLevel(Request $request,$membership)
    {
      $level      = ($request->has('l') && !is_null(json_decode($request->l)))? json_decode($request->l) : [0,1];
      $view       = view('volunteer::membership', compact('membership', 'level'))->render();
      $items      = $this->handleExplodeString($view);
      $value      = 'mark';
      $data       = collect($items);
      $data       = $this->handleSearch($request,$data);
      if ($membership instanceof \Illuminate\Pagination\LengthAwarePaginator) {
        $data = [
          'current_page' => $membership->currentPage(),
          'data' => $data,
          'first_page_url'=> $membership->onFirstPage(),
          'from'=> $membership->firstItem(),
          'last_page'=> $membership->lastPage(),
          'last_page_url'=> $membership->lastPage(),
          'next_page_url'=> $membership->nextPageUrl(),
          'path'=> 'http://localhost:8000/api/admin/settings/village',
          'per_page'=> 15,
          'prev_page_url'=> null,
          'to'=> 15,
          'total'=> $membership->total()
        ];
      }
      return $data;
    }

    private function handleByParentId(Request $request,$membership)
    {
      if ($request->has('p_id')) {
        $membership = $membership->where('parent_id',$request->p_id);
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
