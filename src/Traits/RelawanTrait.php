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
}