<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CompetitionController extends Controller
{

	public function fetchAll()
	{
		$data = Competition::orderBy('id', 'desc')->paginate(25);

		foreach ($data as $c) {
// 			$c->logo = url("storage/competitions") . "/" . $c->logo;
// 			$c->logo = $c->buildStorageBase() . "/" . $c->logo;


			$c->logo = url("storage") . "/" . $c->logo;
			$c->count_classes = 12;
			$c->count_horses = 44;
		}

		return response()->json($data, 200);
	}

	public function store(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'name_en' => 'required',
			'name_ar' => 'required',
			'description_en' => 'required',
			'description_ar' => 'required',
			'score_calc_type' => 'required',
			
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}


		$file = $request->file('logo');
		$uniqueFileName = uniqid() . '.' . $file->getClientOriginalExtension();
// 		$fileName = date('Y') . "/" . date("m") . "/" . date("d") . "/" .           $uniqueFileName;
		$fileName = $uniqueFileName;
		
// 		$file->storeAs('public/competitions', $fileName);
		$file->storeAs('public/', $fileName);


		$dData = [
			'name_en' => $request->name_en,
			'name_ar' => $request->name_ar,
			'description_en' => $request->description_en,
			'description_ar' => $request->description_ar,
			'logo' => $fileName,
			'score_calc_type' => $request->score_calc_type,
			'prize_report_header' => 'prize report header',
			'prize_report_footer' => 'prize report footer',
			'prize_owner_name' => 'prize owner name',
			'prize_owner_description' => 'prize owner description',
			'prize_currency' => 'prize currency',
		];

		Competition::create($dData);
		return response()->json([
			'status' => 200,
		]);
	}


	public function update(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'name_en' => 'required',
			'name_ar' => 'required',
			'description_en' => 'required',
			'description_ar' => 'required',
			'score_calc_type' => 'required',
			'active_phase' => 'required',
			'prize_report_header' => 'required',
			'prize_report_footer' => 'required',
			'prize_owner_name' => 'required',
			'prize_owner_description' => 'required',
			'prize_currency' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		if ($request->hasFile('logo')) {

			$file = $request->file('logo');
			$uniqueFileName = uniqid() . '.' . $file->getClientOriginalExtension();
// 			$fileName = date('Y') . "/" . date("m") . "/" . date("d") . "/" .           $uniqueFileName;

			$fileName = $uniqueFileName;
			
// 			$file->storeAs('public/competitions', $fileName);
			$file->storeAs('public/', $fileName);


			$d = Competition::find($request->id);
			if ($d) {
				$dData = [
					'name_en' => $request->name_en,
					'name_ar' => $request->name_ar,
					'description_en' => $request->description_en,
					'description_ar' => $request->description_ar,
					'logo' => $fileName,
					'score_calc_type' => $request->score_calc_type,
					'active_phase' => $request->active_phase,
					'prize_report_header' => $request->prize_report_header,
			'prize_report_footer' => $request->prize_report_footer,
			'prize_owner_name' => $request->prize_owner_name,
			'prize_owner_description' => $request->prize_owner_description,
			'prize_currency' => $request->prize_currency,
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
		} else {

			// $file = $request->file('logo');
			// $uniqueFileName = uniqid() . '.' . $file->getClientOriginalExtension();
			// $fileName = date('Y') . "/" . date("m") . "/" . date("d") . "/" . $uniqueFileName;
			// $file->storeAs('public/competitions', $fileName);


			$d = Competition::find($request->id);
			if ($d) {
				$dData = [
					'name_en' => $request->name_en,
					'name_ar' => $request->name_ar,
					'description_en' => $request->description_en,
					'description_ar' => $request->description_ar,
					// 'logo' => $fileName,
					'score_calc_type' => $request->score_calc_type,
					'active_phase' => $request->active_phase,
					'prize_report_header' => $request->prize_report_header,
			'prize_report_footer' => $request->prize_report_footer,
			'prize_owner_name' => $request->prize_owner_name,
			'prize_owner_description' => $request->prize_owner_description,
			'prize_currency' => $request->prize_currency,
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
	}

	public function delete(Request $request)
	{
		$id = $request->id;

		Competition::destroy($id);
	}
}
