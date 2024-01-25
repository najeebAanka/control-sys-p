<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ChampionClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class ChampionsClassesController extends Controller
{



	public function fetchAll()
	{
		$data = ChampionClass::orderBy('id', 'desc')->paginate(25);


		return response()->json($data, 200);
	}


	public function store(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'name_en' => 'required',
			'name_ar' => 'required',
			// 'competition_id' => 'required',
			'start_dob' => 'required',
			'end_dob' => 'required',
			'gender' => 'required',
			'age_info' => 'required',
			'status' => 'required',

		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		$dData = [
			'name_en' => $request->name_en,
			'name_ar' => $request->name_ar,
			// 'competition_id' => $request->competition_id,
			'status' => $request->status,
			'start_dob' => $request->start_dob,
			'end_dob' => $request->end_dob,
			'age_info' => $request->age_info,
			'gender' => $request->gender,
		];

		ChampionClass::create($dData);
		return response()->json([
			'status' => 200,
		]);
	}


	public function update(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'name_en' => 'required',
			'name_ar' => 'required',
			// 'competition_id' => 'required',
			'start_dob' => 'required',
			'end_dob' => 'required',
			'gender' => 'required',
			'age_info' => 'required',
			'status' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		$d = ChampionClass::find($request->id);
		if ($d) {

			// if ($request->has('name_en')) $d->name_en = $request->name_en;
			// if ($request->has('name_ar')) $d->name_ar = $request->name_ar;
			// if ($request->has('competition_id')) $d->competition_id = $request->competition_id;
			// if ($request->has('start_dob')) $d->start_dob = $request->start_dob;
			// if ($request->has('end_dob')) $d->end_dob = $request->end_dob;
			// if ($request->has('age_info')) $d->age_info = $request->age_info;
			// if ($request->has('gender')) $d->gender = $request->gender;
			// if ($request->has('status')) $d->gender = $request->status;

			// $d->save();

			$dData = [
				'name_en' => $request->name_en,
				'name_ar' => $request->name_ar,
				// 'competition_id' => $request->competition_id,
				'status' => $request->status,
				'start_dob' => $request->start_dob,
				'end_dob' => $request->end_dob,
				'age_info' => $request->age_info,
				'gender' => $request->gender,
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

		ChampionClass::destroy($id);
	}
}
