<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use Math;

class HelperController extends Controller {

    
 public function convert(){
$this->readHorses();
     }  

        private function readHorses(){
       
  $json = file_get_contents("https://dashboard.ejudge.ae/public/data.json", 0, null, null);
          $json = json_decode($json, true);
               $doc =  $json["DUBAI INTERNATIONAL ARABIAN HOR"];
          
            $classes = [];
            $map = [];
             $objects = [];
            foreach ($doc as $d){
           
                //     4/3/2020      43924
                

//                echo $d["DOB"]." : ";
//           
//                
//        
//                
//                
//                echo "<hr />";
//                continue;
//                
                
            $h = new \App\Models\Horse();
            
            
            
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
            $h->sire_name_ar = $d["Sire Name(AR)"];
            $h->dam_en = $d["Dam(EN)"];
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
            
            $reg = new \App\Models\HorseRegistration();
            $reg->reg_number= $d["Horse Show No."];
            $reg->horse_id = $h->id;
            $reg->sectionLabel = trim(str_replace("Section " , "" ,$d["Section"]));
            $reg->group_id = $this->getClassByNameEn($d["Classes"]);
            $reg->save();
            
            
            
            
            
                
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
 
            
   }  
     
     
     private function  getClassByNameEn($name){
         
      return \App\Models\CompetitionGroup::where('name_en' ,$name)->first()->id;   
     }
     private function readClasses(){
       
  $json = file_get_contents("https://dashboard.ejudge.ae/public/data.json", 0, null, null);
          $json = json_decode($json, true);
            $doc =  $json["DUBAI INTERNATIONAL ARABIAN HOR"];
          
            $classes = [];
            $map = [];
             $objects = [];
            foreach ($doc as $d){
                
                
            
                
                
                 $classes[]=$d["Classes"];
                 if(!array_key_exists($d["Classes"] ,$map)){
                  $map[$d["Classes"]] = 0;  
                  
                  $cl = new \stdClass();
                  $cl->en = $d["Classes"];
                  $cl->ar = $d["Classes_1"];
                  $objects[$d["Classes"]]= $cl;
                 
                  
                 }else{
                     $map[$d["Classes"]]++;  
                 }
            }
     $classes = array_unique($classes);
     echo count($classes)." classes found.";
     foreach ( $classes as $c){
      echo  "<br />".$c." has : " . $map[$c]." horses."; 
             $g = new \App\Models\CompetitionGroup();
             $v = $objects[$c];
                $g->name_en = $v->en;
                $g->name_ar = $v->ar;
                $g->competition_id = 22;
                $g->save();
 
     }
   }  
     
     
    

}