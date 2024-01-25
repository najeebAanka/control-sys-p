<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\HorsePoint;
use App\Models\HorseRegistration;
use App\Models\Horse;
use App\Models\EvaluateCategory;
use App\Models\ClassJudge;

class CompetitionsController extends Controller {
/*
 * UPDATE horse_registrations set results_json=null ,total_marks=0  ,total_points=0 ,total_c1=0 ,total_c2=0 ,judge_selection=-1 ,champion_score=0 ,gold_count=0 ,foal_points=0 ,foal_marks=0 ,count_selected_foals=0 WHERE horse_registrations.id in (SELECT horse_registrations.id from horse_registrations join competition_groups on competition_groups.id = horse_registrations.group_id WHERE competition_groups.competition_id=22)
 */
    public function buildResults() {





        foreach (HorseRegistration::get() as $reg) {

            $count_judges = ClassJudge::where('class_id', $reg->group_id)->where('section', $reg->sectionLabel)->count();
            $count_categories = EvaluateCategory::count();

            if ($reg->total_points > 0) {

                $class = \App\Models\CompetitionGroup::find($reg->group_id);
                $competition = Competition::find($class->competition_id);

                // die($competition->score_calc_type);

                if ($competition->score_calc_type == 'flat') {
                    //implement later
                } else {


                    $total_marks = 0;
//                foreach (EvaluateCategory::get() as $c) {
//
//
//
//                    $cat_total = 0.0;
//                    $cat_total = HorsePoint::where('reg_id', $request->horse_reg_id)->where('category_id', $c->id)->sum('score') - HorsePoint::where('reg_id', $request->horse_reg_id)->where('category_id', $c->id)->max('score') - HorsePoint::where('reg_id', $request->horse_reg_id)->where('category_id', $c->id)->min('score');
//
////       for($i=1;$i<count($points);$i++){
////        
////       $cat_total+=   $points[$i]->score; 
////           
////       }
//                    $total_marks += $cat_total;
//                }




                    $json = new \stdClass();
                    $lines = [];

                    $judges = ClassJudge::where('class_id', $reg->group_id)->get();

                    foreach ($judges as $j) {
                        $line = new \stdClass();
                        $line->judge_id = $j->judge_id;
                        $line->judge_name = User::find($j->judge_id)->name;
                        $line->cats = [];
                        foreach (EvaluateCategory::get() as $c) {
                            $res = new \stdClass();
                            $res->cat_id = $c->id;
                            $res->cat_name = $c->name;
                            $res->score = HorsePoint::where('reg_id', $reg->id)->where('category_id', $c->id)->
                                            where('judge_id', $j->judge_id)->first()->score;
                            $line->cats[] = $res;
                        }

                        $lines[] = $line;
                    }


                    $json->lines = $lines;

                    $cats_totals = [];
                    $judges_totals = [];

                    $full_tie_priority_1 = EvaluateCategory::where('full_tie_priority', 1)->first()->id;
                    $full_tie_priority_2 = EvaluateCategory::where('full_tie_priority', 2)->first()->id;

                    foreach (EvaluateCategory::get() as $c) {

                        $object = new \stdClass();

                        $object->cat_id = $c->id;
                        $object->total = HorsePoint::where('reg_id', $reg->id)->where('category_id', $c->id)->sum('score') - HorsePoint::where('reg_id', $reg->id)->where('category_id', $c->id)->max('score') - HorsePoint::where('reg_id', $reg->id)->where('category_id', $c->id)->min('score');
                        if ($full_tie_priority_1 == $c->id)
                            $reg->total_c1 = $object->total;
                        if ($full_tie_priority_2 == $c->id)
                            $reg->total_c2 = $object->total;

                        $cats_totals[] = $object;
                        $total_marks += $object->total;
                    }

                    foreach ($judges as $j) {

                        $object = new \stdClass();

                        $object->judge_id = $j->judge_id;
                        $object->total = HorsePoint::where('reg_id', $reg->id)->where('judge_id', $j->judge_id)->sum('score');
                        $judges_totals[] = $object;
                    }


                    $json->cats_totals = $cats_totals;
                    $json->judges_totals = $judges_totals;

                    $reg->total_marks = $total_marks;
                    $reg->total_points = $total_marks / ($count_judges - 2);

                    $reg->results_json = json_encode($json);
                    $reg->save();
                }
            }
        }
    }

    public function submitHorseScores(Request $request) {


        $validator = Validator::make($request->all(), [
                    'horse_reg_id' => 'required|exists:horse_registrations,id',
        ]);

        if ($validator->fails()) {

            return response()->json(['error' => $this->failedValidation($validator)], 401);
        }

        if (HorsePoint::where('judge_id', Auth::id())->where('reg_id', $request->horse_reg_id)
                        ->count() != EvaluateCategory::count()
        ) {
            return response()->json(['error' => "Not all the categories were entered !"], 400);
        }

        HorsePoint::where('judge_id', Auth::id())->where('reg_id', $request->horse_reg_id)->update(['status' => 1]);

        $reg = HorseRegistration::find($request->horse_reg_id);
        $count_judges = ClassJudge::where('class_id', $reg->group_id)->where('section', $reg->sectionLabel)->count();
        $count_categories = EvaluateCategory::count();

        if (HorsePoint::where('reg_id', $request->horse_reg_id)->where('status', 1)->count() == $count_judges * $count_categories) {

            $class = \App\Models\CompetitionGroup::find($reg->group_id);
            $competition = Competition::find($class->competition_id);

            // die($competition->score_calc_type);

            if ($competition->score_calc_type == 'flat') {
                //implement later
            } else {


                $total_marks = 0;
//                foreach (EvaluateCategory::get() as $c) {
//
//
//
//                    $cat_total = 0.0;
//                    $cat_total = HorsePoint::where('reg_id', $request->horse_reg_id)->where('category_id', $c->id)->sum('score') - HorsePoint::where('reg_id', $request->horse_reg_id)->where('category_id', $c->id)->max('score') - HorsePoint::where('reg_id', $request->horse_reg_id)->where('category_id', $c->id)->min('score');
//
////       for($i=1;$i<count($points);$i++){
////        
////       $cat_total+=   $points[$i]->score; 
////           
////       }
//                    $total_marks += $cat_total;
//                }




                $json = new \stdClass();
                $lines = [];

                $judges = ClassJudge::where('class_id', $reg->group_id)->where('section', $reg->sectionLabel)->get();

                foreach ($judges as $j) {
                    $line = new \stdClass();
                    $line->judge_id = $j->judge_id;
                    $line->judge_name = User::find($j->judge_id)->name;
                    $line->cats = [];
                    foreach (EvaluateCategory::get() as $c) {
                        $res = new \stdClass();
                        $res->cat_id = $c->id;
                        $res->cat_name = $c->name;
                        $res->score = HorsePoint::where('reg_id', $request->horse_reg_id)->where('category_id', $c->id)->
                                        where('judge_id', $j->judge_id)->first()->score;
                        $line->cats[] = $res;
                    }

                    $lines[] = $line;
                }


                $json->lines = $lines;

                $cats_totals = [];
                $judges_totals = [];

                $full_tie_priority_1 = EvaluateCategory::where('full_tie_priority', 1)->first()->id;
                $full_tie_priority_2 = EvaluateCategory::where('full_tie_priority', 2)->first()->id;

                foreach (EvaluateCategory::get() as $c) {

                    $object = new \stdClass();

                    $object->cat_id = $c->id;
                    $object->total = HorsePoint::where('reg_id', $request->horse_reg_id)->where('category_id', $c->id)->sum('score') - HorsePoint::where('reg_id', $request->horse_reg_id)->where('category_id', $c->id)->max('score') - HorsePoint::where('reg_id', $request->horse_reg_id)->where('category_id', $c->id)->min('score');

                    if ($full_tie_priority_1 == $c->id)
                        $reg->total_c1 = $object->total;
                    if ($full_tie_priority_2 == $c->id)
                        $reg->total_c2 = $object->total;

                    $cats_totals[] = $object;
                    $total_marks += $object->total;
                }

                foreach ($judges as $j) {

                    $object = new \stdClass();

                    $object->judge_id = $j->judge_id;
                    $object->total = HorsePoint::where('reg_id', $request->horse_reg_id)->where('judge_id', $j->judge_id)->sum('score');
                    $judges_totals[] = $object;
                }


                $json->cats_totals = $cats_totals;
                $json->judges_totals = $judges_totals;

                $reg->total_marks = $total_marks;
                $reg->total_points = $total_marks / ($count_judges - 2);

                $reg->results_json = json_encode($json);
                $reg->save();
            }
        }





//        $m = new \App\Http\Controllers\Helpers\MonitorController();
//        $m->loadHorseEvaluation($request->horse_reg_id);
        return response()->json(['message' => "Submitted successfully "], 200);
    }

    public function submitChampionsRating(Request $request) {


        $validator = Validator::make($request->all(), [
                    'class_id' => 'required|exists:champion_classes,id',
                    'gold_id' => 'required|exists:horse_registrations,id',
                    'silver_id' => 'required|exists:horse_registrations,id',
                    'bronze_id' => 'required|exists:horse_registrations,id',
        ]);

        if ($validator->fails()) {

            return response()->json(['error' => $this->failedValidation($validator)], 401);
        }
    $championship_id = Competition::where('status' ,1)->first()->id;
    
     $judges = \App\Models\User::where('user_type' ,0)
                                        ->where('status' ,1)
                                        ->whereIn('id' ,
                                                \App\Models\ChampionJudge::where('champion_id' ,$request->class_id)
                                        ->pluck('judge_id')
                                        
                                        )
                                        ->orderBy('order_label')->get();
                        
        $jlist = [];
        foreach ($judges as $j){
                   $jlist[]=$j->id;          
                        }
    
        $count_judges = count($judges);

        if (\App\Models\ChampionRating::where('judge_id', Auth::id())->where('class_id', $request->class_id)->count() == 0) {
            $p = new \App\Models\ChampionRating();
            $p->judge_id = Auth::id();
            $p->silver_id = $request->silver_id;
            $p->gold_id = $request->gold_id;
            $p->bronze_id = $request->bronze_id;
            $p->class_id = $request->class_id;
            $p->champion_id =   $championship_id;
            $p->save();
//        $m = new \App\Http\Controllers\Helpers\MonitorController();
//        $m->loadHorseEvaluation($request->horse_reg_id);

            if (\App\Models\ChampionRating::where('class_id', $request->class_id)
                    ->where('champion_id' , $championship_id)
                    ->count() >= $count_judges) {

                $controller = new \App\Http\Controllers\Dashboard\ChampionClassesController();
                $horses = $controller->getQualifiedHorses($request->class_id ,$championship_id);
                foreach ($horses as $h) {

                    $reg = HorseRegistration::find($h->id);
                    $reg->champion_score = \App\Models\ChampionRating::where('class_id', $request->class_id)->where('gold_id', $h->id)->count() * 4 + \App\Models\ChampionRating::where('class_id', $request->class_id)->where('silver_id', $h->id)->count() * 2 + \App\Models\ChampionRating::where('class_id', $request->class_id)->where('bronze_id', $h->id)->count();
                    $reg->gold_count = \App\Models\ChampionRating::where('class_id', $request->class_id)->where('gold_id', $h->id)->count();
                    $reg->save();
                }
            }


            return response()->json(['message' => "Submitted successfully"], 200);
        } else {
            return response()->json(['message' => "Already submitted"], 200);
        }
    }
    
    
    
    
      public function submitFoalChampionsRating(Request $request) {


        $validator = Validator::make($request->all(), [
                    'class_id' => 'required',
                    'rank1' => 'required|exists:horse_registrations,id',
                    'rank2' => 'required|exists:horse_registrations,id',
                    'rank3' => 'required|exists:horse_registrations,id',
                    'rank4' => 'required|exists:horse_registrations,id',
                    'rank5' => 'required|exists:horse_registrations,id',
        ]);

        if ($validator->fails()) {

            return response()->json(['error' => $this->failedValidation($validator)], 401);
        }

        $count_judges = User::where('user_type', 0)->where('status' ,1)->count();
        $championship_id = Competition::where('status' ,1)->first()->id;

        if (\App\Models\FoalRating::where('judge_id', Auth::id())->where('class_id', $request->class_id)
                ->where('champion_id',   $championship_id )->count() == 0) {
            $p = new \App\Models\FoalRating();
            $p->judge_id = Auth::id();
            $p->first_id = $request->rank1;
            $p->second_id = $request->rank2;
            $p->third_id = $request->rank3;
            $p->fourth_id = $request->rank4;
            $p->fifth_id = $request->rank5;
            $p->class_id = $request->class_id;
            $p->champion_id =   $championship_id;
            $p->save();


            if (\App\Models\FoalRating::where('class_id', $request->class_id)
                          ->where('champion_id',   $championship_id )->count() >= $count_judges) {

//                $controller = new \App\Http\Controllers\Dashboard\ChampionClassesController();
//                $horses = $controller->getQualifiedHorses($request->class_id);
//                foreach ($horses as $h) {
//
//                    $reg = HorseRegistration::find($h->id);
//                    $reg->champion_score = \App\Models\ChampionRating::where('class_id', $request->class_id)->where('gold_id', $h->id)->count() * 4 + \App\Models\ChampionRating::where('class_id', $request->class_id)->where('silver_id', $h->id)->count() * 2 + \App\Models\ChampionRating::where('class_id', $request->class_id)->where('bronze_id', $h->id)->count();
//                    $reg->gold_count = \App\Models\ChampionRating::where('class_id', $request->class_id)->where('gold_id', $h->id)->count();
//                    $reg->save();
//                }
                
          $horses = \App\Models\HorseRegistration::where('group_id', $request->class_id)
                    
                                         
                                            ->get();      
             
      $id = $request->class_id;
        foreach (  $horses as $reg){
          $marks = 0;
             $marks +=   \App\Models\FoalRating::where('class_id'  ,$id)->where('first_id' ,$reg->id)
                   ->where('champion_id' ,$championship_id)->count()*31
                  
                  + \App\Models\FoalRating::where('class_id'  ,$id)->where('second_id' ,$reg->id)
                   ->where('champion_id' ,$championship_id)->count()*15
                  
                  + \App\Models\FoalRating::where('class_id'  ,$id)->where('third_id' ,$reg->id)
                   ->where('champion_id' ,$championship_id)->count()*7
                  
                  + \App\Models\FoalRating::where('class_id'  ,$id)->where('fourth_id' ,$reg->id)
                   ->where('champion_id' ,$championship_id)->count()*3
                  
                  + \App\Models\FoalRating::where('class_id'  ,$id)->where('fifth_id' ,$reg->id)
                   ->where('champion_id' ,$championship_id)->count()
                  ;
           ///   echo("ok : marks for " .$reg->id." is " . $marks."<br />");
             $reg->foal_marks = $marks ;
             $reg->count_selected_foals	 = \App\Models\FoalRating::where('class_id'  ,$id)->where(function($q) use ($reg){
              $q->where('fifth_id' ,$reg->id)
                      ->orWhere('first_id' ,$reg->id)
                      ->orWhere('second_id' ,$reg->id)
                      ->orWhere('third_id' ,$reg->id)
                      ->orWhere('fourth_id' ,$reg->id);
                      
                      
             })
                   ->where('champion_id' ,$championship_id)->count() ;
             $reg->	foal_points =  $reg->	foal_marks / $count_judges;
         
             $reg->save();
            
        }
    
                
                
            }


            return response()->json(['message' => "Submitted successfully"], 200);
        } else {
            return response()->json(['message' => "Lready submitted"], 200);
        }
    }
    
    
    

    public function saveSingleEvaluation(Request $request) {


        $validator = Validator::make($request->all(), [
                    'category_id' => 'required|exists:evaluate_categories,id',
                    'horse_reg_id' => 'required|exists:horse_registrations,id',
                    'score' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json(['error' => $this->failedValidation($validator)], 401);
        }

        $p = HorsePoint::where('judge_id', Auth::id())->where('reg_id', $request->horse_reg_id)
                        ->where('category_id', $request->category_id)->first();
        if (!$p) {
            $p = new \App\Models\HorsePoint();

            $p->judge_id = Auth::id();
            $p->reg_id = $request->horse_reg_id;
            $horseReg = HorseRegistration::find($request->horse_reg_id);
            $p->horse_id = $horseReg->horse_id;
            $p->category_id = $request->category_id;
        }

        $p->score = $request->score;
        $p->save();
        return response()->json(['message' => "saved successfully"], 200);
    }

    public function buildClass($id, $section, $for_chamapion = false) {
        $lang = app()->getLocale();

        if ($for_chamapion) {


            $data = \App\Models\ChampionClass::select(['id', 'name_' . $lang . ' as title'])->find($id);
            $data->can_judge = true;
            $data->is_there_active_horse = true;
            $controller = new \App\Http\Controllers\Dashboard\ChampionClassesController();
            
             $cmp = Competition::where('status', 1)->first();
            
            $horses = $controller->getQualifiedHorses($id ,$cmp->id);

//                    HorseRegistration::select(['id as number'])->orderBy('total_points', 'DESC')->take(10)->get();

            $list = [];
            foreach ($horses as $d) {
                if(isset($d->reg_number))
                if($d->present_in_championship == -1)      continue; // To be removed soon after DIAHC2023 ends ... 
                $h1 = new \stdClass();
                $h1->evaluation = "ignored";
                $h1->section = "";
         
                  $h1->number = $d->reg_number;
                $h1->id = $d->id;
                $h1->label = "Ranked";
                $h1->status = $d->status . "";
                $list[] = $h1;
            }

            $data->horses = $list;
            $data->is_submitted = \App\Models\ChampionRating::where('judge_id', Auth::id())->where('class_id', $id)
                    ->where('champion_id' , $cmp ->id)
                    ->count() > 0;

            if ($data->is_submitted) {
                $my_submission = \App\Models\ChampionRating::where('judge_id', Auth::id())->where('class_id', $id)->first();
                foreach ($data->horses as $d) {
                    if ($d->id == $my_submission->silver_id)
                        $d->evaluation = "silver";
                    if ($d->id == $my_submission->gold_id)
                        $d->evaluation = "gold";
                    if ($d->id == $my_submission->bronze_id)
                        $d->evaluation = "bronze";
                }
            }

            return $data;
        } else {

            $data = \App\Models\CompetitionGroup::select(['id', 'name_' . $lang . ' as title', 'current_horse'])->find($id);

            $data->count_sections = HorseRegistration::select(['sectionLabel'])->where('group_id', $id)->distinct()->count('sectionLabel');
            $is_single_section = $data->count_sections <= 1;
            $data->is_there_active_horse = $data->current_horse != -1 &&
                    ( ClassJudge::where('class_id', $id)->where('section', $section)->where('judge_id', Auth::id())->count() > 0 );
            $data->can_judge = ( ClassJudge::where('class_id', $id)->where('section', $section)->where('judge_id', Auth::id())->count() > 0 );

            $horse = new \stdClass();
            if ($data->is_there_active_horse) {
                $horse->is_submited = HorsePoint::where('judge_id', Auth::id())->where('reg_id', $data->current_horse)
                                ->where('status', 1)->count() == EvaluateCategory::count();

                $horseReg = HorseRegistration::find($data->current_horse);
                $horseObject = Horse::find($horseReg->horse_id);
                $horse->number =   $horseReg->reg_number;
                $horse->id = $horseReg->id;
                $horse->section = $is_single_section ? "" : "Section " . $horseReg->sectionLabel;
                $horse->evaluation_categories = \App\Models\EvaluateCategory::select(['id', 'name as code', 'min_score', 'max_score'
                            , 'normal_min_score', 'normal_max_score'
                        ])->get();
                foreach ($horse->evaluation_categories as $c) {
                    $val = HorsePoint::where('judge_id', Auth::id())->where('reg_id', $data->current_horse)
                                    ->where('category_id', $c->id)->first();
                    $c->previous_value = $val ? $val->score : -1;
                }
            }
//        else{
//            $data->current_horse=-1;
//        }
            $data->current_horse_object = $horse;

            $list = [];

            foreach (HorseRegistration::where('group_id', $id)->where('sectionLabel', $section)->get() as $reg) {

                $h1 = new \stdClass();
                   $h1->number = $reg->reg_number;
                $h1->id = $reg->id;
                $h1->status = "upcoming";
                $h1->label = "Upcoming";

                if ($data->current_horse == $reg->id) {
                    $h1->status = 'active';
                    $h1->label = "Active";
                }
                if ($reg->total_marks > 0) {
                    $h1->status = 'evaluated';
                    $h1->label = "Done";
                }
                if ($reg->status < 0) {
                    $h1->status = 'absent';
                    $h1->label = "Unavailable";
                    if ($reg->status == -1)
                        $h1->label = "Absent";
                    if ($reg->status == -2)
                        $h1->label = "Lame";

                    if ($reg->status == -3)
                        $h1->label = "Disqualified";
                }
                $h1->section = $reg->sectionLabel;

                $list[] = $h1;
            }

            $data->horses = $list;
            return $data;
        }
    }

    public function getClassData($id) {

        return response()->json($this->buildClass($id), 200);
    }

    public function getCompetitionById($id) {
        $lang = app()->getLocale();

        $c = Competition::select(['id', 'name_' . $lang . ' as title', 'description_' . $lang . ' as description', 'logo', 'active_class'])->find($id);
        $c->logo = $c->buildStorageBase() . "/" . $c->logo;
        $c->count_classes = 12;
        $c->count_horses = 44;
        return response()->json($c, 200);
    }

    public function getActiveCompetition() {
        $lang = app()->getLocale();

        if (Competition::where('status', 1)->count() == 0)
            return response()->json(['message' => "No active competitions"], 400);
        else {
            $c = Competition::select(['id', 'name_' . $lang . ' as title', 'description_' . $lang . ' as description'
                        , 'logo', 'active_class', 'active_phase as phase', 'active_section'])->where('status', 1)->orderBy('id', 'desc')->first();
            
            
            
            $c->logo = $c->buildStorageBase() . "/" . $c->logo;
            $c->count_classes = \App\Models\CompetitionGroup::where('competition_id', $c->id)->count();
            $c->count_horses = \App\Models\HorseRegistration::join('competition_groups', 'competition_groups.id', 'horse_registrations.group_id')
                            ->where('competition_groups.competition_id', $c->id)->count();

            $c->class_data = null;
            if ($c->active_class != -1) {
 $c->class_data = null;
 if( $c->phase == 1 )
                $c->class_data =  $this->buildClass($c->active_class, $c->active_section) ;
 if( $c->phase == 2 )
 $c->class_data = $this->buildClass($c->active_class, $c->active_section, true);
 
  if( $c->phase == 3 )
  $c->class_data = $this->buildClassFoals($c->active_class);
 
            }


            /*
             * 
             * class Person {
             * $name;
             * $age
             * }
             * 
             * $p->name;
             * 
             */



            return response()->json($c, 200);
        }
    }
   public function buildClassFoals($id) {
        $lang = app()->getLocale();

          $data = new \stdClass();
          $data->title= \App\Models\CompetitionGroup::find($id)->name_en;
          $data->id=$id;
            $data->can_judge = true;
            $data->is_there_active_horse = true;
       
            
            $data_all = \App\Models\HorseRegistration::where('group_id', $id)
                    ->where('status' ,1)
                                         
                                            ->get();  
                
                   
                 
                                
                                      
                                        foreach ( $data_all as $reg) {


                                        $reg->horse = \App\Models\Horse::find($reg->horse_id);
                                       
                                        
                                        }
            
            
            
            

//                    HorseRegistration::select(['id as number'])->orderBy('total_points', 'DESC')->take(10)->get();

            $list = [];
            foreach ($data_all as $d) {
                $h1 = new \stdClass();
                $h1->evaluation = "ignored";
                $h1->section = "";
                $h1->number = $d->reg_number;
                $h1->id = $d->id;
                $h1->label = "Ranked";
                $h1->status = $d->status . "";
                $list[] = $h1;
            }

            $data->horses = $list;
            
             
            
               $championship_id = Competition::where('status' ,1)->first()->id;
            $data->is_submitted = \App\Models\FoalRating::where('judge_id', Auth::id())->where('class_id', $id)
                 ->where('champion_id',   $championship_id )
                    ->count() > 0;

            if ($data->is_submitted) {
                $my_submission =\App\Models\FoalRating::where('judge_id', Auth::id())->where('class_id', $id)
                 ->where('champion_id',   $championship_id )->first();
              $data->my_submission =  $my_submission;
              
                foreach ($data->horses as $d) {
                    
                    
                    
                    if ($d->id == $my_submission->first_id)
                        $d->evaluation = "rank1";
                    
                      if ($d->id == $my_submission->second_id)
                        $d->evaluation = "rank2";
                      
                        if ($d->id == $my_submission->third_id)
                        $d->evaluation = "rank3";
                        
                          if ($d->id == $my_submission->fourth_id)
                        $d->evaluation = "rank4";
                          
                            if ($d->id == $my_submission->fifth_id)
                        $d->evaluation = "rank5";
                
                    
                }
            }

            return $data;
       
    }
    public function getOldCompetitions() {
        $lang = app()->getLocale();
        $data = Competition::select(['id', 'name_' . $lang . ' as title', 'description_' . $lang . ' as description', 'logo'])->where('status', -1)->orderBy('id', 'desc')->get();
        foreach ($data as $c) {
            $c->logo = $c->buildStorageBase() . "/" . $c->logo;
            $c->count_classes = 12;
            $c->count_horses = 44;
        }
        return response()->json($data, 200);
    }

    public function getTopHorses($class, $section) {



        $lang = app()->getLocale();
        $data = Competition::select(['id', 'name_' . $lang . ' as title', 'description_' . $lang . ' as description', 'logo'])->where('status', -1)->orderBy('id', 'desc')->get();
        foreach ($data as $c) {
            $c->logo = $c->buildStorageBase() . "/" . $c->logo;
            $c->count_classes = 12;
            $c->count_horses = 44;
        }
        return response()->json($data, 200);
    }

    public function currentHorseEval($reg_id) {

        $data = new \stdClass();

        $comp = \App\Models\Competition::where('status', 1)->orderBy('id', 'desc')->first();
        $c = \App\Models\CompetitionGroup::find($comp->active_class);

        $data->active_class = $comp->active_class;
        $data->active_horse = $c->current_horse;

        $data->evals = HorsePoint::where('reg_id', $reg_id)->where('status', 1)->get();
        $data->reg_full = HorseRegistration::find($reg_id);

        $data->rank = $data->reg_full->total_points > 0 ? $data->reg_full->getRanking() : "-/-";
        $data->refresh = true;
        //$data->reg_full->total_marks == "";





        $data->cat_total_scores = EvaluateCategory::get();

        $data->judge_total_scores = ClassJudge::where('class_id', $data->reg_full->group_id)
                ->where('section', $data->reg_full->sectionLabel)
                ->get();

        $data->total_judges = count($data->judge_total_scores);

        $class = \App\Models\CompetitionGroup::find($data->reg_full->group_id);
        $competition = Competition::find($class->competition_id);
        // die($competition->score_calc_type);

        if ($competition->score_calc_type == 'flat') {
            //implement later
        } else {



            foreach ($data->cat_total_scores as $d) {

                if (HorsePoint::where('reg_id', $reg_id)->where('category_id', $d->id)->where('status', 1)->count() ==
                        $data->total_judges
                ) {
                    $d->total = HorsePoint::where('reg_id', $reg_id)->where('category_id', $d->id)->sum('score') - HorsePoint::where('reg_id', $reg_id)->where('category_id', $d->id)->max('score') - HorsePoint::where('reg_id', $reg_id)->where('category_id', $d->id)->min('score');
                } else {
                    $d->total = "-/-";
                }
            }

            foreach ($data->judge_total_scores as $d) {

                if (HorsePoint::where('reg_id', $reg_id)->where('judge_id', $d->judge_id)->where('status', 1)->count() ==
                        count($data->cat_total_scores)
                ) {
                    $d->total = HorsePoint::where('reg_id', $reg_id)->where('judge_id', $d->judge_id)->sum('score');
                } else {
                    $d->total = "-/-";
                }
            }
        }




        $count_sections = HorseRegistration::select(['sectionLabel'])->where('group_id', $comp->active_section)->distinct()->count('sectionLabel');

        $topsObject = new \stdClass();
        $topsObject->is_single_section = $count_sections <= 1;
        $topsObject->cats = EvaluateCategory::get();
        $topsObject->data = HorseRegistration::where('status', 1)
                ->where('group_id', $c->id)
                ->where('total_points', '>', 0)
                ->where('sectionLabel', $comp->active_section)->
                orderBy('total_points', 'DESC') // tie
                ->orderBy('total_c1', 'DESC') ///tie
                ->orderBy('total_c2', 'DESC')
                ->orderBy('judge_selection', 'DESC')
                ->take(25)
                ->get();
        foreach ($topsObject->data as $d) {
            $d->ranking = $d->getRanking();
            $h = Horse::find($d->horse_id);
            $d->name = $h->name_en;
            $d->owner = $h->owner_name_en;
            $d->breeder = $h->breeder_name_en;
            $d->country = $h->owner_country_name_en;
            $d->total_points = number_format($d->total_points, 2);
        }


        $data->tops = $topsObject;

        return response()->json($data, 200);
    }

}
