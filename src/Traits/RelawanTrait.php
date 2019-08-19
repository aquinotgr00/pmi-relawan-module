<?php

namespace BajakLautMalaka\PmiRelawan\Traits;

use Illuminate\Http\Request;

trait RelawanTrait
{
	private function handlePaginate(Request $request, $model)
	{
		if ($request->has('page')) {
			$model = $model->paginate();
		}else{
			$model = $model->get();
		}
		return $model;
	}

	private function handleOrder(Request $request, $city)
	{
		if ($request->has('ob')) {
			$sort_direction = 'asc';
			if ($request->has('od')) {
				if (in_array($request->od, ['asc', 'desc'])) {
					$sort_direction = $request->od;
				}
			}
			$city = $city->orderBy($request->ob, $sort_direction);
		}
		return $city;
	}

	public function setLeadingZeroCode($number,$length)
	{
		$code   = str_pad($number,$length,"0",STR_PAD_LEFT);
		return $code;
	}
}