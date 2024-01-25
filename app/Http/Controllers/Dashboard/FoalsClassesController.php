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
use App\Models\FoalRegistration;
use Carbon\Carbon;
use App\Models\Horse;

class FoalsClassesController extends Controller
{

    
      public function getActiveChampionClass($id)
	{

	$data = new stdClass();
        
        $cmp=Competition::where('status' ,1)->where('active_phase' ,3)->first();
        
        $data->data = \App\Models\FoalRating::where('class_id'  ,$id)->get();
        foreach ( $data->data as $d){
            $d->first_id = HorseRegistration::find($d->first_id)->reg_number;
            $d->second_id = HorseRegistration::find($d->second_id)->reg_number;
            $d->third_id = HorseRegistration::find($d->third_id)->reg_number;
            $d->fourth_id = HorseRegistration::find($d->fourth_id)->reg_number;
            $d->fifth_id = HorseRegistration::find($d->fifth_id)->reg_number;
        }
        $data->count_scores = count($data->data);
        $data->current_class =$cmp ->active_class;
        $cnt_judges = \App\Models\User::where('user_type' ,0)->where('status' ,1)->count();
        $data->refresh = count($data->data) < $cnt_judges;
        
             $data_all = \App\Models\HorseRegistration::where('group_id', $id)
                    
                                         
                                            ->get();  
                
                   
                 
                                 $horses = [];
                                      
                                        foreach ( $data_all as $reg) {


                                    //    $reg->horse = \App\Models\Horse::find($reg->horse_id);
                                        $horses[]=$reg;
                                        
                                        }

        
         
         if(!$data->refresh){
        
              
       $list = [];
      
        foreach (  $horses as $reg){
              $list[]=$reg->id;
   
            
        }
    
  
             
             
             
             
      
        $data->first = HorseRegistration::whereIn('id' ,$list)->where('foal_points' ,'>' ,0)
                 ->orderBy('foal_points' ,'DESC')->orderBy('count_selected_foals' ,'DESC')->first();
        $data->first->horse = \App\Models\Horse::find($data->first ->horse_id);
        //-0------------------------
           $data->second = HorseRegistration::whereIn('id' ,$list)
                   ->whereNotIn('id' ,[$data->first->id])
                  ->where('foal_points' ,'>' ,0)
                 ->orderBy('foal_points' ,'DESC')->orderBy('count_selected_foals' ,'DESC')->first();
        $data->second->horse = \App\Models\Horse::find($data->second ->horse_id);
            //-0------------------------
        
            $data->third = HorseRegistration::whereIn('id' ,$list)
                   ->whereNotIn('id' ,[$data->first->id ,$data->second->id])
                 ->where('foal_points' ,'>' ,0)
                 ->orderBy('foal_points' ,'DESC')->orderBy('count_selected_foals' ,'DESC')->first();
        $data->third->horse = \App\Models\Horse::find($data->third ->horse_id);
            //-0------------------------
              $data->fourth = HorseRegistration::whereIn('id' ,$list)
                   ->whereNotIn('id' ,[$data->first->id ,$data->second->id,$data->third->id])
                  ->where('foal_points' ,'>' ,0)
                 ->orderBy('foal_points' ,'DESC')->orderBy('count_selected_foals' ,'DESC')->first();
        $data->fourth->horse = \App\Models\Horse::find($data->fourth ->horse_id);
            //-0------------------------
              $data->fifth = HorseRegistration::whereIn('id' ,$list)
                   ->whereNotIn('id' ,[$data->first->id ,$data->second->id,$data->third->id,$data->fourth->id])
               ->where('foal_points' ,'>' ,0)
                 ->orderBy('foal_points' ,'DESC')->orderBy('count_selected_foals' ,'DESC')->first();
        $data->fifth->horse = \App\Models\Horse::find($data->fifth ->horse_id);
            //-0------------------------
    
        
         }
        

		return response()->json($data ,200);
	}
    
     
    
    
        public function readHorses(Request $request){
       $counter=0;
  $json = file_get_contents($request->file('json'), 0, null, null);
          $json = json_decode($json, true);
            $doc =  $json["Data"];
          
            $classes = [];
            $map = [];
             $objects = [];
            foreach ($doc as $d){
           
                //     4/3/2020      43924
                

   //  echo $d["DOB"]." : ";

//           
//                
//        
//                
//                
//                echo "<hr />";
//                continue;
//                
                
            $h = new \App\Models\Horse();
        if(\App\Models\Horse::where('reg_no_en' ,$d["Reg. No(EN)"])->count() == 0){    
            
            
            $h->reg_no_en = $d["Reg. No(EN)"];
            $h->name_en = $d["Horse Name(EN)"];
            $h->name_ar = $d["Horse Name(AR)"];
            $h->gender = $d["Gender"];
            $h->color_en = $d["Horse Color(EN)"];
            $h->color_ar = $d["Horse Color(AR)"];
            
            
                    $excel_date = $d["DOB"]; //here is that value 41621 or 41631
$unix_date = ($excel_date - 25569) * 86400;
$excel_date = 25569 + ($unix_date / 86400);
$unix_date = ($excel_date - 25569) * 86400;
            $h->dob =   gmdate("Y-m-d", $unix_date);
     
            $h->sire_name_en = $d["Sire Name(EN)"];
            if(isset($d['Sire Name(AR)']))
            $h->sire_name_ar = $d["Sire Name(AR)"];
            $h->dam_en = $d["Dam(EN)"];
             if(isset($d['Dam(AR)']))
            $h->dam_ar = $d["Dam(AR)"];
            $h->stud_name_en = $d["Stud Name(EN)"];
            $h->stud_name_ar = $d["Stud Name(AR)"];
            $h->owner_name_en = $d["Owner Name(EN)"];
            $h->owner_name_ar = $d["Owner Name(AR)"];
            $h->breeder_name_en = $d["Breeder Name(EN)"];
            if(isset($d["Breeder Name(AR)"]))
            $h->breeder_name_ar = $d["Breeder Name(AR)"];
            
            $h->owner_country_name_en = $d["Owner Country Name(EN)"];
            $h->owner_country_name_ar = $d["Owner Country Name(EN)"];
            $h->save();
        }else{
            $h = \App\Models\Horse::where('reg_no_en' ,$d["Reg. No(EN)"])->first();
        }
        
        
             //------compare-----
            
             $date1 = Carbon::createFromFormat('Y-m-d', $h->dob );
        $date2 = Carbon::createFromFormat('Y-m-d', $request->dateBarrier);
 
       $result = $date1->gt($date2);   
       if(     $result) {
            //---end of compare-------
            $counter++;
        
        
        
        
            $reg = new \App\Models\FoalRegistration();
            $reg->reg_number= $d["Horse Show No."];
            $reg->horse_id = $h->id;
            $reg->gender = $d["Gender"];
            $reg->champion_id = $request->champion_id;
            $reg->class_name = $d["Classes"];
            $reg->save();
            
            
        }   
            
            
                
//                
//                 $classes[]=$d["Classes"];
//                 if(!array_key_exists($d["Classes"] ,$map)){
//                  $map[$d["Classes"]] = 0;  
//                  
//                  $cl = new \stdClass();
//                  $cl->en = $d["Classes"];
//                  $cl->ar = $d["Classes (AR)"];
//                  $objects[$d["Classes"]]= $cl;
//                 
//                  
//                 }else{
//                     $map[$d["Classes"]]++;  
//                 }
            }
            return response()->json("Successfully imported ".    $counter." horses" ,200);
 
            
   }  
    
    
    
    
    
    
    
    
  
//      public function getActiveChampionClass($id)
//	{
//
//	$data = new stdClass();
//        
//        
//        $data->data =   \App\Models\ChampionRating::where('class_id'  ,$id)->get();
//        $data->count_scores = count($data->data);
//        $data->current_class = Competition::where('status' ,1)->where('active_phase' ,2)->first()->active_class;
//        $data->refresh = count($data->data) < 8;
//        
//        $horses = $this->getQualifiedHorses($id);
//         $totals = [];
//       $list = [];
//        foreach (  $horses as $h){
//              $list[]=$h->id;
//          $p = new stdClass();
//          $p->horse_id = $h->id;
//          $p->total_score = \App\Models\ChampionRating::where('class_id'  ,$id)->where('gold_id' ,$h->id)->count()*4
//                  +  \App\Models\ChampionRating::where('class_id'  ,$id)->where('silver_id' ,$h->id)->count()*2
//                  +   \App\Models\ChampionRating::where('class_id'  ,$id)->where('bronze_id' ,$h->id)->count();
//          $p->count_gold = \App\Models\ChampionRating::where('class_id'  ,$id)->where('gold_id' ,$h->id)->count();
//             $totals[] = $p;
//            
//        }
//         $data->totals = $totals;
//        
//         
//         if(!$data->refresh){
//        
//      
//        $data->gold = HorseRegistration::whereIn('id' ,$list)->where('champion_score' ,'>' ,0)
//                 ->orderBy('gold_count' ,'DESC')->orderBy('champion_score' ,'DESC')->orderBy('total_points', 'DESC') // tie
//                                            ->orderBy('total_c1', 'DESC') ///tie
//                                            ->orderBy('total_c2', 'DESC')
//                                            ->orderBy('judge_selection', 'DESC')->first();
//        $data->gold->horse = \App\Models\Horse::find($data->gold ->horse_id);
//        
//          $data->silver = HorseRegistration::whereIn('id' ,$list)->whereNotIn('id' ,[$data->gold->id])->where('champion_score' ,'>' ,0)
//                 ->orderBy('champion_score' ,'DESC')->orderBy('total_points', 'DESC') // tie
//                                            ->orderBy('total_c1', 'DESC') ///tie
//                                            ->orderBy('total_c2', 'DESC')
//                                            ->orderBy('judge_selection', 'DESC')->first();
//          
//          $data->silver->horse = \App\Models\Horse::find($data->silver ->horse_id);
//          
//          
//            $data->bronze = HorseRegistration::whereIn('id' ,$list)
//                    ->whereNotIn('id' ,[$data->gold->id , $data->silver->id])->where('champion_score' ,'>' ,0)
//                 ->orderBy('champion_score' ,'DESC')->orderBy('total_points', 'DESC') // tie
//                                            ->orderBy('total_c1', 'DESC') ///tie
//                                            ->orderBy('total_c2', 'DESC')
//                                            ->orderBy('judge_selection', 'DESC')->first();
//             $data->bronze->horse = \App\Models\Horse::find($data->bronze ->horse_id);
//         }
//        
//
//		return response()->json($data ,200);
//	}
//    
//    
//    
//    
//    
    public function updateActiveClass($id, $class_id)
	{

       Competition::where('id' ,'>' ,0)->update(["status" =>-1 ,"active_class" =>-1]);
		$d = Competition::find($id);


                $d->active_phase = 3;
		$d->status =  1;
                $d->active_class =  $class_id;
		$d->active_section =  "ALL";
		$d->save();
                foreach (\App\Models\User::where('status' ,1)->where('user_type' ,0)->get() as $u){
                    $u->sendNotification("update", -1, "update", null);
                }
	   //  ChampionClass::find($class_id)->notifyJudges();

		return response()->json([
			'status' => 200,
       	]);
	}
//    
//    
    
    public function setHorsePresent($id)
	{
        $h = 
        FoalRegistration::find($id);
        $h->status=1;
        $h->save();
		return response()->json("ok", 200);
	}
        
            public function setHorseAbsent($id)
	{
      $h = 
        FoalRegistration::find($id);
        $h->status=-1;
        $h->save();
		return response()->json("ok", 200);
	}
  	
    public function fetchClassHorses($id)
	{
            $holder = new stdClass();
                 $data = [];

                   $data_all = \App\Models\HorseRegistration::where('group_id', $id )
                                         
                                            ->get();  
                
                   
                 
                                
                                      
                                        foreach ( $data_all as $reg) {


                                        $reg->horse = \App\Models\Horse::find($reg->horse_id);
                                        $data[]=$reg;
                                        
                                        }
 
                $holder->data = $data;

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
