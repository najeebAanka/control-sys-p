<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EvaluateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EvaluateCategoryController extends Controller
{

	public function fetchAll()
	{
		$data = EvaluateCategory::orderBy('id', 'desc')->paginate(25);
		return response()->json($data, 200);
	}

	public function store(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'code' => 'required',
			'min_score' => 'required',
			'max_score' => 'required',
			'normal_min_score' => 'required',
			'normal_max_score' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		$dData = [
			'name' => $request->name,
			'code' => $request->code,
			'min_score' => $request->min_score,
			'max_score' => $request->max_score,
			'normal_min_score' => $request->normal_min_score,
			'normal_max_score' => $request->normal_max_score
		];

		EvaluateCategory::create($dData);
		return response()->json([
			'status' => 200,
		]);
	}


	public function update(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'code' => 'required',
			'min_score' => 'required',
			'max_score' => 'required',
			'normal_min_score' => 'required',
			'normal_max_score' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		$d = EvaluateCategory::find($request->id);
		if ($d) {
			$dData = [
				'name' => $request->name,
				'code' => $request->code,
				'min_score' => $request->min_score,
				'max_score' => $request->max_score,
				'normal_min_score' => $request->normal_min_score,
				'normal_max_score' => $request->normal_max_score
			];

			$d->update($dData);
			return response()->json([
				'status' => 200,
			]);
		} else {
			return response()->json([
				'message' => $request->id . ' Not found',
				'status' => 401,
			]);
		}
	}

	public function delete(Request $request)
	{
		$id = $request->id;

		EvaluateCategory::destroy($id);
	}
}
