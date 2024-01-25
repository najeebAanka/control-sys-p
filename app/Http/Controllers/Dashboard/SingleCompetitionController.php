<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionGroup;
use App\Models\Horse;
use App\Models\HorseRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use stdClass;

class SingleCompetitionController extends Controller
{

	public function fetchSingle($id)
	{
		$data = Competition::find($id);


// 		$data->logo = url("storage/competitions") . "/" . $data->logo;
		$data->logo = url("storage/") . "/" . $data->logo;
		$data->count_classes = 12;
		$data->count_horses = 44;


		return response()->json($data, 200);
	}
	public function fetchAll()
	{
		$data = Competition::where('id', Route::input('id'))->orderBy('id', 'desc')->paginate(25);

		foreach ($data as $c) {
			$c->logo = url("storage/competitions") . "/" . $c->logo;
			$c->count_classes = 12;
			$c->count_horses = 44;
		}

		return response()->json($data, 200);
	}

	// public function fetchAllThis()
	// {
	// 	$data = CompetitionGroup::where('competition_id' ,Route::input('id'))->paginate(25);
	//    $info = new stdClass();
	//    $info->data = $data;
	//    $info->competition =  Competition::find(Route::input('id'));;

	// 	return response()->json($data, 200);
	// }


	public function fetchClassHorses($id ,$section)
	{
		$data = HorseRegistration::where('group_id', $id)->where('sectionLabel', $section)->orderBy('id', 'asc')->paginate(200);

		foreach ($data->getCollection() as $d) {
			$d->horse = Horse::select(['name_en'])->find($d->horse_id);
		}


		$wrapper = new stdClass();
		$wrapper->data = $data;
		$wrapper->current_class_object = CompetitionGroup::find($id);

		return response()->json($wrapper, 200);
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
		$fileName = date('Y') . "/" . date("m") . "/" . date("d") . "/" . $uniqueFileName;
		$file->storeAs('public/competitions', $fileName);


		$dData = [
			'name_en' => $request->name_en,
			'name_ar' => $request->name_ar,
			'description_en' => $request->description_en,
			'description_ar' => $request->description_ar,
			'logo' => $fileName,
			'score_calc_type' => $request->score_calc_type
		];

		Competition::create($dData);
		return response()->json([
			'status' => 200,
		]);
	}
	public function update(Request $request)
	{



		if ($request->hasFile('logo')) {

			$file = $request->file('logo');
			$uniqueFileName = uniqid() . '.' . $file->getClientOriginalExtension();
			$fileName = date('Y') . "/" . date("m") . "/" . date("d") . "/" . $uniqueFileName;
			$file->storeAs('public/competitions', $fileName);


			$d = Competition::find($request->id);
			if ($d) {
				$dData = [
					'name_en' => $request->name_en,
					'name_ar' => $request->name_ar,
					'description_en' => $request->description_en,
					'description_ar' => $request->description_ar,
					'logo' => $fileName,
					'score_calc_type' => $request->score_calc_type,
					'active_phase' => $request->active_phase
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
				// $dData = [
				// 	'name_en' => $request->name_en,
				// 	'name_ar' => $request->name_ar,
				// 	'description_en' => $request->description_en,
				// 	'description_ar' => $request->description_ar,
				// 	// 'logo' => $fileName,
				// 	'score_calc_type' => $request->score_calc_type,
				// 	'active_phase' => $request->active_phase
				// ];

				if ($request->has('name_en')) $d->name_en = $request->name_en;
				if ($request->has('name_ar')) $d->name_ar = $request->name_ar;
				if ($request->has('description_en')) $d->description_en = $request->description_en;
				if ($request->has('description_ar')) $d->description_ar = $request->description_ar;
				if ($request->has('score_calc_type')) $d->score_calc_type = $request->score_calc_type;
				if ($request->has('active_phase')) $d->active_phase = $request->active_phase;

				$d->save();
				// $d->update($dData);
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

	public function updateActiveClass($id, $class_id ,$section)
	{
 Competition::where('id' ,'>' ,0)->update(["status" =>-1 ,"active_class" =>-1]);

		$d = Competition::find($id);


                $d->active_phase = 1;
                $d->status = 1;
		$d->active_class =  $class_id;
		$d->active_section =  $section;
		$d->save();


		CompetitionGroup::query()->update(['current_horse' => -1]);
	//	CompetitionGroup::find($class_id)->notifyJudges();

		return response()->json([
			'status' => 200,
		]);
	}

        
        public function notifyJudges($id){
            CompetitionGroup::find($id)->notifyJudges();
            return response()->json([
			'status' => 200,
		]);
        }
        
	public function updateActiveHorse($id, $horse_id, $comp_id)
	{

		$d = CompetitionGroup::find($id);


		CompetitionGroup::query()->update(['current_horse' => -1]);
		$d->current_horse =  $horse_id;
		$d->save();


		Competition::where('id', $comp_id)->update(['active_horse' => $horse_id   , 'active_class' => $id ,'active_phase' => 1]);
		

		return response()->json([
			'status' => 200,
		]);
	}

	public function delete(Request $request)
	{
		$id = $request->id;

		Competition::destroy($id);
	}
}
