<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\ChampionClass;
use stdClass;
use App\Models\HorseRegistration;

class CompetitionGroupController extends Controller
{

    
    
    public function setHorsePresent($id)
	{
        $h = 
        HorseRegistration::find($id);
        $h->status=1;
        $h->save();
		return response()->json("ok", 200);
	}
        
            public function setHorseAbsent($id ,$action)
	{
      $h = 
        HorseRegistration::find($id);
        $h->status=$action;
        $h->save();
		return response()->json("ok", 200);
	}
        
        
    public function fetchJudgesList($class_id ,$section)
	{
		$data = \App\Models\ClassJudge::where('class_id' ,$class_id) ->where('section' ,$section)->get();
		return response()->json($data, 200);
	}
	
	public function fetchChampionJudgesList($id)
	{
		$data = \App\Models\ChampionJudge::where('champion_id' ,$id)->get();
		return response()->json($data, 200);
	}
    
	public function fetchAll()
	{
		$data = CompetitionGroup::orderBy('id', 'asc')->paginate(25);
		return response()->json($data, 200);
	}


	public function fetchAllClasses()
	{
		$data = CompetitionGroup::where('competition_id', Route::input('id'))->orderBy('id', 'asc')->paginate(25);
	
 
		 return response()->json($data, 200);

		
	}



	public function fetchAllThis()
	{
		$data = CompetitionGroup::where('competition_id', Route::input('id'))->where('group_type' ,'normal')->orderBy('id', 'asc')->paginate();
	
		$info = new stdClass();
		$info->data = $data;
		$info->competition =  Competition::find(Route::input('id'));;
foreach ($data->getCollection() as $cd){
    $cd->sections = HorseRegistration::select(['sectionLabel'])->where('group_id' ,$cd->id)->groupBy(['sectionLabel'])->get();
}
 
		 return response()->json($info, 200);

		
	}
	
	public function getGroupSections($id)
	{
		
	$data = HorseRegistration::select(['sectionLabel'])->where('group_id' ,$id)->groupBy(['sectionLabel'])->get();
	

		return response()->json($data, 200);

		
	}

	public function store(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'name_en' => 'required',
			'name_ar' => 'required',
			'competition_id' => 'required',
			'start_dob' => 'required',
			'end_dob' => 'required',
			'max_in_section' => 'required',
			'gender' => 'required',
			// 'status' => 'required',
			// 'current_horse' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		$dData = [
			'name_en' => $request->name_en,
			'name_ar' => $request->name_ar,
			'competition_id' => $request->competition_id,
			'status' => 1,
			'current_horse' => -1,
			'start_dob' => $request->start_dob,
			'end_dob' => $request->end_dob,
			'max_in_section' => $request->max_in_section,
			'gender' => $request->gender,
		];

		CompetitionGroup::create($dData);
		return response()->json([
			'status' => 200,
		]);
	}


	public function update(Request $request)
	{

		// $validator = Validator::make($request->all(), [
		// 	'name_en' => 'required',
		// 	'name_ar' => 'required',
		// 	'competition_id' => 'required',
		// 	'status' => 'required',
		// 	'current_horse' => 'required',
		// ]);
		// if ($validator->fails()) {
		// 	return response()->json($this->failedValidation($validator), 400);
		// }

		$d = CompetitionGroup::find($request->id);
		if ($d) {
			$dData = [
				'name_en' => $request->name_en,
				'name_ar' => $request->name_ar,
				'competition_id' => $request->competition_id,
				'status' => $request->status,
				// 'current_horse' => $request->current_horse,
				'start_dob' => $request->start_dob,
			'end_dob' => $request->end_dob,
			'max_in_section' => $request->max_in_section,
			'gender' => $request->gender
			];

			$d->update($dData);

			// if ($request->has('name_en')) $d->name_en = $request->name_en;
			// if ($request->has('name_ar')) $d->name_ar = $request->name_ar;
			// if ($request->has('status')) $d->status = $request->status;


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
	public function updateJudges(Request $request)
	{
            
            
            $d = CompetitionGroup::find($request->id);
		if ($d) {
                    $section = $request->section;
                    \App\Models\ClassJudge::where('class_id' ,$request->id)->where('section' , $section)->delete();
                    if($request->has('judges')){
                        foreach ($request->judges as $j){
                            $c = new \App\Models\ClassJudge();
                            $c->judge_id = $j;
                            $c->class_id = $request->id;
                            $c->section = $section;
                            $c->save();
                        }
                        
                        
                    }
                    
                    

			return response()->json([  "Ok done " , 'status' => 200,
			]);
		} else {
			return response()->json([
				'message' => $request->id . ' Not found',
				'status' => 401,
			]);
		}
	}
	
	public function updateChampionJudges(Request $request)
	{
            
            
            $d = ChampionClass::find($request->id);
	      	if ($d) {
                    // $section = $request->section;
                    \App\Models\ChampionJudge::where('champion_id' ,$request->id)->delete();
                    if($request->has('judges')){
                        foreach ($request->judges as $j){
                            $c = new \App\Models\ChampionJudge();
                            $c->judge_id = $j;
                            $c->champion_id = $request->id;
                            // $c->section = $section;
                            $c->save();
                        }
                        
                        
                    }
                    
                    

			return response()->json([  "Ok done " , 'status' => 200,
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

		CompetitionGroup::destroy($id);
	}
}
