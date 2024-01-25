<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use stdClass;
use App\Models\HorseRegistration;
use App\Models\ChampionClass;

class ChampionClassesController extends Controller
{

  
      public function getActiveChampionClass($id)
	{

	$data = new stdClass();
        
          $cmp = Competition::where('status', 1)->first();
      
        $data->current_class = Competition::where('status' ,1)->where('active_phase' ,2)->first()->active_class;
        $judges = \App\Models\User::where('user_type' ,0)
                                        ->where('status' ,1)
                                        ->whereIn('id' ,
                                                \App\Models\ChampionJudge::where('champion_id' ,$id)
                                        ->pluck('judge_id')
                                        
                                        )
                                        ->orderBy('order_label')->get();
                        
        $jlist = [];
        foreach ($judges as $j){
                   $jlist[]=$j->id;          
                        }
        
          $data->data =   \App\Models\ChampionRating::where('class_id'  ,$id)->whereIn('judge_id' ,
               $jlist 
                )->get();
        $data->count_scores = count($data->data);
        
        
      //  die("s : " . count(  $judges));
        
        
        $data->refresh = count($data->data) < count(  $judges);
        
        $horses = $this->getQualifiedHorses($id ,$cmp->id);
         $totals = [];
       $list = [];
        foreach (  $horses as $h){
              $list[]=$h->id;
          $p = new stdClass();
          $p->horse_id = $h->id;
          $p->total_score = \App\Models\ChampionRating::where('class_id'  ,$id)->where('gold_id' ,$h->id)->count()*4
                  +\App\Models\ChampionRating::where('class_id'  ,$id)->where('silver_id' ,$h->id)->count()*2
                  +\App\Models\ChampionRating::where('class_id'  ,$id)->where('bronze_id' ,$h->id)->count();
          $p->count_gold = \App\Models\ChampionRating::where('class_id'  ,$id)->where('gold_id' ,$h->id)->count();
             $totals[] = $p;
            
        }
         $data->totals = $totals;
        
         
         if(!$data->refresh){
        
      $eval_type = $cmp->champion_calc_type;
      
        $data->gold = HorseRegistration::whereIn('id' ,$list)->where('champion_score' ,'>' ,0);
                 
                if(      $eval_type == 'normal'){
                  $data->gold =    $data->gold   ->orderBy('gold_count' ,'DESC');
                }
                
                $data->gold =    $data->gold 
                        
                        
                        
                        ->orderBy('champion_score' ,'DESC')->orderBy('total_points', 'DESC') // tie
                                            ->orderBy('total_c1', 'DESC') ///tie
                                            ->orderBy('total_c2', 'DESC')
                                            ->orderBy('judge_selection', 'DESC')->first();
        $data->gold->horse = \App\Models\Horse::find($data->gold ->horse_id);
        
          $data->silver = HorseRegistration::whereIn('id' ,$list)->whereNotIn('id' ,[$data->gold->id])->where('champion_score' ,'>' ,0)
                 ->orderBy('champion_score' ,'DESC')->orderBy('total_points', 'DESC') // tie
                                            ->orderBy('total_c1', 'DESC') ///tie
                                            ->orderBy('total_c2', 'DESC')
                                            ->orderBy('judge_selection', 'DESC')->first();
          
          $data->silver->horse = \App\Models\Horse::find($data->silver ->horse_id);
          
          
            $data->bronze = HorseRegistration::whereIn('id' ,$list)
                    ->whereNotIn('id' ,[$data->gold->id , $data->silver->id])->where('champion_score' ,'>' ,0)
                 ->orderBy('champion_score' ,'DESC')->orderBy('total_points', 'DESC') // tie
                                            ->orderBy('total_c1', 'DESC') ///tie
                                            ->orderBy('total_c2', 'DESC')
                                            ->orderBy('judge_selection', 'DESC')->first();
             $data->bronze->horse = \App\Models\Horse::find($data->bronze ->horse_id);
         }
        

		return response()->json($data ,200);
	}
    
    
    
    
    
    public function updateActiveClass($id, $class_id)
	{
 Competition::where('id' ,'>' ,0)->update(["status" =>-1 ,"active_class" =>-1]);
		$d = Competition::find($id);


                $d->active_phase = 2;
                $d->status = 1;
		$d->active_class =  $class_id;
		$d->active_section =  "ALL";
		$d->save();

	     ChampionClass::find($class_id)->notifyJudges();

		return response()->json([
			'status' => 200,
       	]);
	}
    
    
    public function getQualifiedHorses($id ,$cmp_id){
        
        
         $data = [];
               $c = \App\Models\ChampionClass::find($id);
          
               
                foreach (\App\Models\CompetitionGroup::where('start_dob', '>=', $c->start_dob)
               ->where('end_dob', '<=', $c->end_dob)->where('gender', $c->gender)->where('competition_id' ,$cmp_id)->get() as $class) {
                    
                    $sections = \App\Models\HorseRegistration::select(['sectionLabel'])->where('group_id', $class->id)->distinct()->get();
        $single = count($sections) == 1; 
                 
            foreach ($sections as $section) { 
                   $data_all = \App\Models\HorseRegistration::where('status', 1)
                                            ->where('group_id', $class->id)
                                            ->where('total_points', '>', 0)
                                            ->where('sectionLabel', $section->sectionLabel)
                                          
                                            ->
                                            orderBy('total_points', 'DESC') // tie
                                            ->orderBy('total_c1', 'DESC') ///tie
                                            ->orderBy('total_c2', 'DESC')
                                            ->orderBy('judge_selection', 'DESC')
                                            ->take(3)
                                            ->get();  
                
                   
                          $three = [];
                                    foreach ( $data_all as $d){
                                      $three[] = $d;  
                                    }
                                      usort($three, fn($a, $b) => $a->id - $b->id);
                                      
                                        foreach (  $three as $reg) {

//                                        $horse = ;
//                                        $data = json_decode($reg->results_json);
                                        unset($reg->results_json);
                                        $reg->horse = \App\Models\Horse::find($reg->horse_id);
                                        $reg->class =  $single ? $c->name_en . "" : $c->name_en . " Section " .$reg->sectionLabel;
                                        
                                        $data[]=$reg;
                                        
                                        }
                                      
                   
                
            }         
                    
                    
                    
                } 
                return $data;
        
    }
    
  	public function fetchClassHorses($id ,$cmp_id)
	{
            $holder = new stdClass();
            $data = $this->getQualifiedHorses($id ,$cmp_id);  
               
               
                $holder->data = $data;
//             $holder
//		$data = CompetitionGroup::orderBy('id', 'asc')->paginate(25);
		return response()->json($holder, 200);
	}  
    
    
    
//    
//    
//    public function setHorsePresent($id)
//	{
//        $h = 
//        HorseRegistration::find($id);
//        $h->status=1;
//        $h->save();
//		return response()->json("ok", 200);
//	}
//        
//            public function setHorseAbsent($id)
//	{
//      $h = 
//        HorseRegistration::find($id);
//        $h->status=-1;
//        $h->save();
//		return response()->json("ok", 200);
//	}
//        
//        
//    public function fetchJudgesList($class_id ,$section)
//	{
//		$data = \App\Models\ClassJudge::where('class_id' ,$class_id) ->where('section' ,$section)->get();
//		return response()->json($data, 200);
//	}
//    
//	public function fetchAll()
//	{
//		$data = CompetitionGroup::orderBy('id', 'asc')->paginate(25);
//		return response()->json($data, 200);
//	}
//
//	public function fetchAllThis()
//	{
//		$data = CompetitionGroup::where('competition_id', Route::input('id'))->orderBy('id', 'asc')->paginate();
//	
//		$info = new stdClass();
//		$info->data = $data;
//		$info->competition =  Competition::find(Route::input('id'));;
//foreach ($data->getCollection() as $cd){
//    $cd->sections = HorseRegistration::select(['sectionLabel'])->where('group_id' ,$cd->id)->groupBy(['sectionLabel'])->get();
//}
// 
//		 return response()->json($info, 200);
//
//		
//	}
//
//	public function store(Request $request)
//	{
//
//		$validator = Validator::make($request->all(), [
//			'name_en' => 'required',
//			'name_ar' => 'required',
//			'competition_id' => 'required',
//			// 'status' => 'required',
//			// 'current_horse' => 'required',
//		]);
//		if ($validator->fails()) {
//			return response()->json($this->failedValidation($validator), 400);
//		}
//
//		$dData = [
//			'name_en' => $request->name_en,
//			'name_ar' => $request->name_ar,
//			'competition_id' => $request->competition_id,
//			'status' => 1,
//			'current_horse' => -1,
//		];
//
//		CompetitionGroup::create($dData);
//		return response()->json([
//			'status' => 200,
//		]);
//	}
//
//
//	public function update(Request $request)
//	{
//
//		// $validator = Validator::make($request->all(), [
//		// 	'name_en' => 'required',
//		// 	'name_ar' => 'required',
//		// 	'competition_id' => 'required',
//		// 	'status' => 'required',
//		// 	'current_horse' => 'required',
//		// ]);
//		// if ($validator->fails()) {
//		// 	return response()->json($this->failedValidation($validator), 400);
//		// }
//
//		$d = CompetitionGroup::find($request->id);
//		if ($d) {
//			$dData = [
//				'name_en' => $request->name_en,
//				'name_ar' => $request->name_ar,
//				// 'competition_id' => $request->competition_id,
//				'status' => $request->status,
//				// 'current_horse' => $request->current_horse,
//			];
//
//			$d->update($dData);
//
//			// if ($request->has('name_en')) $d->name_en = $request->name_en;
//			// if ($request->has('name_ar')) $d->name_ar = $request->name_ar;
//			// if ($request->has('status')) $d->status = $request->status;
//
//
//			return response()->json([
//				'status' => 200,
//			]);
//		} else {
//			return response()->json([
//				'message' => $request->id . ' Not found',
//				'status' => 401,
//			]);
//		}
//	}
//	public function updateJudges(Request $request)
//	{
//            
//            
//            $d = CompetitionGroup::find($request->id);
//		if ($d) {
//                    $section = $request->section;
//                    \App\Models\ClassJudge::where('class_id' ,$request->id)->where('section' , $section)->delete();
//                    if($request->has('judges')){
//                        foreach ($request->judges as $j){
//                            $c = new \App\Models\ClassJudge();
//                            $c->judge_id = $j;
//                            $c->class_id = $request->id;
//                            $c->section = $section;
//                            $c->save();
//                        }
//                        
//                        
//                    }
//                    
//                    
//
//			return response()->json([  "Ok done " , 'status' => 200,
//			]);
//		} else {
//			return response()->json([
//				'message' => $request->id . ' Not found',
//				'status' => 401,
//			]);
//		}
//	}
//
//	public function delete(Request $request)
//	{
//		$id = $request->id;
//
//		CompetitionGroup::destroy($id);
//	}
}
