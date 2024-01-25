<?php

namespace App\Http\Controllers\Helpers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Monitor;
use App\Models\HorsePoint;
use App\Models\HorseRegistration;
use App\Models\Horse;
class MonitorController extends Controller {

    
    
    
    
    
    
        
    public function resultsApi($competition_code ,$number){
     /*
      * {
    "number": "1",
    "score": "89.88",
    "full_results": [
        {
            "judge": {
                "ref": "1",
                "title": "A - Mr.",
                "skipped": true
            },
            "scoring": [
                {
                    "name": "TYPE",
                    "skipped": true,
                    "value": "18"
                },
                {
                    "name": "H&N",
                    "skipped": true,
                    "value": "18"
                },
                {
                    "name": "BODY",
                    "skipped": true,
                    "value": "18"
                },
                {
                    "name": "LEGS",
                    "skipped": true,
                    "value": "16"
                },
                {
                    "name": "MOVE",
                    "skipped": true,
                    "value": "18"
                }
            ]
        },
        {
            "judge": {
                "ref": "2",
                "title": "B - Mr.",
                "skipped": false
            },
            "scoring": [
                {
                    "name": "TYPE",
                    "skipped": true,
                    "value": "19"
                },
                {
                    "name": "H&N",
                    "skipped": false,
                    "value": "18.5"
                },
                {
                    "name": "BODY",
                    "skipped": false,
                    "value": "18.5"
                },
                {
                    "name": "LEGS",
                    "skipped": false,
                    "value": "15.5"
                },
                {
                    "name": "MOVE",
                    "skipped": false,
                    "value": "19"
                }
            ]
        },
        {
            "judge": {
                "ref": "3",
                "title": "C - Mr.",
                "skipped": false
            },
            "scoring": [
                {
                    "name": "TYPE",
                    "skipped": false,
                    "value": "18.5"
                },
                {
                    "name": "H&N",
                    "skipped": true,
                    "value": "18"
                },
                {
                    "name": "BODY",
                    "skipped": true,
                    "value": "18"
                },
                {
                    "name": "LEGS",
                    "skipped": false,
                    "value": "15.5"
                },
                {
                    "name": "MOVE",
                    "skipped": true,
                    "value": "20"
                }
            ]
        },
        {
            "judge": {
                "ref": "4",
                "title": "D - Mr.",
                "skipped": false
            },
            "scoring": [
                {
                    "name": "TYPE",
                    "skipped": true,
                    "value": "18"
                },
                {
                    "name": "H&N",
                    "skipped": false,
                    "value": "18.5"
                },
                {
                    "name": "BODY",
                    "skipped": true,
                    "value": "19"
                },
                {
                    "name": "LEGS",
                    "skipped": true,
                    "value": "15"
                },
                {
                    "name": "MOVE",
                    "skipped": true,
                    "value": "18"
                }
            ]
        },
        {
            "judge": {
                "ref": "5",
                "title": "E - Mrs.",
                "skipped": false
            },
            "scoring": [
                {
                    "name": "TYPE",
                    "skipped": false,
                    "value": "18"
                },
                {
                    "name": "H&N",
                    "skipped": false,
                    "value": "18"
                },
                {
                    "name": "BODY",
                    "skipped": false,
                    "value": "18.5"
                },
                {
                    "name": "LEGS",
                    "skipped": false,
                    "value": "16"
                },
                {
                    "name": "MOVE",
                    "skipped": false,
                    "value": "18.5"
                }
            ]
        },
        {
            "judge": {
                "ref": "6",
                "title": "F - Mr.",
                "skipped": false
            },
            "scoring": [
                {
                    "name": "TYPE",
                    "skipped": false,
                    "value": "18.5"
                },
                {
                    "name": "H&N",
                    "skipped": false,
                    "value": "18.5"
                },
                {
                    "name": "BODY",
                    "skipped": false,
                    "value": "18.5"
                },
                {
                    "name": "LEGS",
                    "skipped": false,
                    "value": "15.5"
                },
                {
                    "name": "MOVE",
                    "skipped": false,
                    "value": "19"
                }
            ]
        },
        {
            "judge": {
                "ref": "7",
                "title": "G - Mr.",
                "skipped": false
            },
            "scoring": [
                {
                    "name": "TYPE",
                    "skipped": false,
                    "value": "18"
                },
                {
                    "name": "H&N",
                    "skipped": false,
                    "value": "18.5"
                },
                {
                    "name": "BODY",
                    "skipped": false,
                    "value": "19"
                },
                {
                    "name": "LEGS",
                    "skipped": false,
                    "value": "16"
                },
                {
                    "name": "MOVE",
                    "skipped": false,
                    "value": "19"
                }
            ]
        },
        {
            "judge": {
                "ref": "8",
                "title": "H - Mr.",
                "skipped": false
            },
            "scoring": [
                {
                    "name": "TYPE",
                    "skipped": false,
                    "value": "18.5"
                },
                {
                    "name": "H&N",
                    "skipped": false,
                    "value": "18"
                },
                {
                    "name": "BODY",
                    "skipped": false,
                    "value": "18"
                },
                {
                    "name": "LEGS",
                    "skipped": false,
                    "value": "15.5"
                },
                {
                    "name": "MOVE",
                    "skipped": false,
                    "value": "20"
                }
            ]
        },
        {
            "judge": {
                "ref": "9",
                "title": "I - Mr.",
                "skipped": false
            },
            "scoring": [
                {
                    "name": "TYPE",
                    "skipped": false,
                    "value": "18"
                },
                {
                    "name": "H&N",
                    "skipped": false,
                    "value": "18"
                },
                {
                    "name": "BODY",
                    "skipped": false,
                    "value": "18"
                },
                {
                    "name": "LEGS",
                    "skipped": false,
                    "value": "16"
                },
                {
                    "name": "MOVE",
                    "skipped": false,
                    "value": "19"
                }
            ]
        },
        {
            "judge": {
                "ref": "10",
                "title": "J - Mrs.",
                "skipped": false
            },
            "scoring": [
                {
                    "name": "TYPE",
                    "skipped": false,
                    "value": "18.5"
                },
                {
                    "name": "H&N",
                    "skipped": true,
                    "value": "19"
                },
                {
                    "name": "BODY",
                    "skipped": false,
                    "value": "18.5"
                },
                {
                    "name": "LEGS",
                    "skipped": false,
                    "value": "16"
                },
                {
                    "name": "MOVE",
                    "skipped": false,
                    "value": "19.5"
                }
            ]
        },
        {
            "judge": {
                "ref": "11",
                "title": "K - Mrs.",
                "skipped": false
            },
            "scoring": [
                {
                    "name": "TYPE",
                    "skipped": false,
                    "value": "18.5"
                },
                {
                    "name": "H&N",
                    "skipped": false,
                    "value": "18.5"
                },
                {
                    "name": "BODY",
                    "skipped": false,
                    "value": "18"
                },
                {
                    "name": "LEGS",
                    "skipped": true,
                    "value": "16.5"
                },
                {
                    "name": "MOVE",
                    "skipped": false,
                    "value": "19"
                }
            ]
        },
        {
            "judge": {
                "ref": "12",
                "title": "L - Mr.",
                "skipped": true
            },
            "scoring": [
                {
                    "name": "TYPE",
                    "skipped": true,
                    "value": "18"
                },
                {
                    "name": "H&N",
                    "skipped": true,
                    "value": "18"
                },
                {
                    "name": "BODY",
                    "skipped": true,
                    "value": "18"
                },
                {
                    "name": "LEGS",
                    "skipped": true,
                    "value": "16"
                },
                {
                    "name": "MOVE",
                    "skipped": true,
                    "value": "18"
                }
            ]
        }
    ]
}
      */   
    $reg = HorseRegistration::join('competition_groups' ,'competition_groups.id' ,'horse_registrations.group_id')
            ->join('competitions' ,'competitions.id' ,'competition_groups.competition_id')
            ->select(['horse_registrations.*'])
            ->where('competitions.name_en' ,$competition_code)
            ->where('horse_registrations.reg_number' ,$number)->first();
      $result = new \stdClass();
    if($reg){
     
    $judges_id = (\App\Models\ClassJudge::where('class_id' ,$reg->group_id)->pluck('judge_id'))->toArray()  ;  
    $horse = Horse::find($reg->horse_id);

    
    
   $result->number = $reg->reg_number;
   $result->score = $reg->total_points;   
   $result->full_results = [];
   $data = json_decode($reg->results_json );
   $map = [];
   foreach ($data as $res){
       foreach ($res as $line){
   
     $object = new \stdClass();
     if(isset($line->judge_id)){
    
        if(!in_array($line->judge_id, $judges_id ))  continue; 
         
           if(in_array($line->judge_id, $map ))  continue;    
             $map[]= $line->judge_id; 
     $j = User::find($line->judge_id);
     
     $object_temp  =new \stdClass();
     $object_temp->ref = $j->id;
     $object_temp->title = $j->order_label." - ".($j->gender == 'male' ? 'Mr':'Mrs');
   
     //-----------------
      $arr = [];
       if(isset($line->cats)){
         $list = $line->cats;  
       usort($list, fn($a, $b) => $a->score - $b->score);       
           
           
      for($i=0;$i<count($list);$i++){
          $cat = $list[$i];
        $a = new \stdClass();
        $a->name = $cat->cat_name;
        $a->skipped = ($i==0 || $i==4) ? true :  false;
        $a->value = $cat->score;
        $arr[]=$a;
      }
      
    
       }
     
      
       

     $object_temp->skipped = !(count($arr) > 0)  ;  
     $object->judge =  $object_temp;
     $object->scoring = $arr;
     
     
   $result->full_results[]=$object;   
   
     }
   }
   }
   
   
         return response()->json($result ,200);   
    }else{
     $result->message = "Horse of number (".$number.") was not found in (".$competition_code.") !";  
        return response()->json($result ,400);  
    }
    
    
   
        
    }
    
    
    
 
const MONITOR_ID = 'default-monitor';     
    


public function current(){
$p  = Monitor::where('screen_id' , self::MONITOR_ID)->first();
return $p->content;
}


private function persist($object){
$p  = Monitor::where('screen_id' , self::MONITOR_ID)->first();
$p->content = json_encode($object);
$p->save();    
}

public function loadDefaultScreen(){


$view = new \stdClass();
$view->view_code = 'splash';
$this->persist($view);
    
} 

public function loadHorseEvaluation($horse_reg_id){


$view = new \stdClass();
$view->view_code = 'eval-horse';
$view->data = HorsePoint::where('reg_id', $horse_reg_id)->get();
$view->cats = \App\Models\EvaluateCategory::get();
$view->judges = User::where('user_type' ,0)->get();
$this->persist($view);
    
} 

    

}