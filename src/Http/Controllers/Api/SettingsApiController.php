<?php
namespace BajakLautMalaka\PmiRelawan\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use BajakLautMalaka\PmiRelawan\City;
use BajakLautMalaka\PmiRelawan\Subdistrict;
use BajakLautMalaka\PmiRelawan\Village;
use BajakLautMalaka\PmiRelawan\Membership;
use BajakLautMalaka\PmiRelawan\UnitVolunteer;
use DB;

class SettingsApiController extends Controller{

	public function checkForUpdate()
	{
		$unitvolunteers = UnitVolunteer::select(DB::raw('"units", MAX(updated_at)'));
		$memberships 	= Membership::select(DB::raw('"memberships", MAX(updated_at)'));
		$villages 		= Village::select(DB::raw('"villages", MAX(updated_at)'));
		$subdistricts 	= Subdistrict::select(DB::raw('"subdistricts", MAX(updated_at)'));
		$city 			= City::select(DB::raw('"cities" as settings,MAX(updated_at) as last_updated'))
		->union($subdistricts)
		->union($villages)
		->union($memberships)
		->union($unitvolunteers)
		->get();

		return response()->success($city);
	}
}