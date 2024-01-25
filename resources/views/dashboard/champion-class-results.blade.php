<!DOCTYPE html>
<html lang="en">

    <?php
    ?>


    @include('dashboard.shared.css')


    <style>
        @media print
        {
            .no-print, .no-print *
            {
                display: none !important;
            }
        }
      
        .bokka ,.mantessa{
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            
        }
        .bokka tr td{
            border : solid 1px #ccc;
            padding: 8px;;
            font-size: 13px;
        }
        

    </style>


    <body style="background-color: #fff;">











       

                <div style="padding: 1rem;">
<!--                    <button class="btn btn-warning m-2 no-print" onclick="window.print();">Print List</button>  -->
                    <?php
                    $c =  App\Models\ChampionClass::find(Route::input('id'));
                      $cmp = \App\Models\Competition::find(Route::input('cmp_id'));
                 //   $comp = App\Models\Competition::find($c->competition_id);
                    $id = $c->id;
                     
                    
                    
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
                    
                    
                    
                    
                    
                    
                        	$data = new stdClass();
      
        
        $data->data =   \App\Models\ChampionRating::where('class_id'  ,$id)->where('champion_id' ,Route::input('cmp_id'))->get();
        $data->count_scores = count($data->data);
                    if ($data->count_scores > 0) {
                        
                        
                        
                        
                        
        
        $data->refresh = count($data->data) < count($judges);
      //  die(count($data->data) ." ". count($judges));
        
        
        $controller = new App\Http\Controllers\Dashboard\ChampionClassesController();
        $horses = $controller->getQualifiedHorses($id ,Route::input('cmp_id'));
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
        
    
        $data->gold = App\Models\HorseRegistration::whereIn('id' ,$list)->where('champion_score' ,'>' ,0)
               
                
                
            ;
                 
                if(    $cmp->champion_calc_type == 'normal'){
                  $data->gold =    $data->gold   ->orderBy('gold_count' ,'DESC');
                }
                
                $data->gold =    $data->gold   ->orderBy('champion_score' ,'DESC')->orderBy('total_points', 'DESC') // tie
                                            ->orderBy('total_c1', 'DESC') ///tie
                                            ->orderBy('total_c2', 'DESC')
                                            ->orderBy('judge_selection', 'DESC')->first();
        $data->gold->horse = \App\Models\Horse::find($data->gold ->horse_id);
        
          $data->silver = App\Models\HorseRegistration::whereIn('id' ,$list)->whereNotIn('id' ,[$data->gold->id])->where('champion_score' ,'>' ,0)
                 ->orderBy('champion_score' ,'DESC')->orderBy('total_points', 'DESC') // tie
                                            ->orderBy('total_c1', 'DESC') ///tie
                                            ->orderBy('total_c2', 'DESC')
                                            ->orderBy('judge_selection', 'DESC')->first();
          
          $data->silver->horse = \App\Models\Horse::find($data->silver ->horse_id);
          
          
            $data->bronze = App\Models\HorseRegistration::whereIn('id' ,$list)
                    ->whereNotIn('id' ,[$data->gold->id , $data->silver->id])->where('champion_score' ,'>' ,0)
                 ->orderBy('champion_score' ,'DESC')->orderBy('total_points', 'DESC') // tie
                                            ->orderBy('total_c1', 'DESC') ///tie
                                            ->orderBy('total_c2', 'DESC')
                                            ->orderBy('judge_selection', 'DESC')->first();
             $data->bronze->horse = \App\Models\Horse::find($data->bronze ->horse_id);
         }
                        
                        
                        
                        ?> 

           

<div style="text-align: center;
     padding: 2rem;">
    <h3>{{$c->name_en}}</h3>
    
    <h5 class="no-print"><a href="?siganture=yes">Open judges version (signature)</a></h5>
    

    
    
</div>                                         
                              

<table class="bokka" style="width: 100% ; <?=isset($_GET['siganture']) ? "display : none" : "" ?>" >
                                <tr style="background-color: #eeeeee;font-weight: bold"><td style="text-align: left;">Horse Number</td>
    <?php
      $three = [];
    foreach (\App\Models\CompetitionGroup::where('start_dob', '>=', $c->start_dob)
            ->where('end_dob', '<=', $c->end_dob)->
            where('competition_id' ,Route::input('cmp_id'))->
            where('gender', $c->gender)->get() as $class) {

        $sections = App\Models\HorseRegistration::select(['sectionLabel'])->where('group_id', $class->id)->distinct()->get();
        $single = count($sections) == 1;
        ?>


                                <?php
                                
                                foreach ($sections as $section) { 
                                    ?>
                           

                                         
                            
                            
                            <?php

                                    $data_all = App\Models\HorseRegistration::where('status', 1)
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

                                     
                                    foreach ( $data_all as $d){
                                      $three[] = $d;  
                                    }
                                }
            }
                                      usort($three, fn($a, $b) => $a->id - $b->id);
                                    
                                    foreach (  $three as $reg) { 
                                        ?>
                                                
                                    <td>{{$reg->reg_number}}</td>
                                                <?php
                                        
                                        
                                    }

                                      
                                        ?>
                                </tr>
                                
                                
                                <?php 
                                
                                
                            
                                
                                foreach ($judges as $judge){ ?>
                                
                                <tr >
                                    <td style="text-align: left;">{{$judge->name()}}</td>
                                    
                                    <?php foreach (  $three as $reg){ ?>
                                    <td id="cell_{{$reg->id}}_{{$judge->id}}"></td>
                                    <?php } ?>
                                </tr>
                                
                                <?php } ?>
                                
                                <tr>
                                    <td colspan="100%" style="text-align: center;border: none">
                                        <div style="height: 1rem"></div>
                                       
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td> Points</td>
                                     <?php foreach (  $three as $reg){ ?>
                                    <td id="cell_total_{{$reg->id}}"></td>
                                    <?php } ?>
                                </tr>
                                
                                
                                
                                  <tr <?=$cmp->champion_calc_type == 'points-based' ?  'style="display: none"' : "" ?> >
                                    <td>  Number of gold</td>
                                     <?php foreach (  $three as $reg){ ?>
                                    <td id="cell_totalg_{{$reg->id}}"></td>
                                    <?php } ?>
                                </tr>
                                
                                

                                            </table>
                                       




                     
                          
                                
                                

                                            
                                            
    @if(!$data->refresh)                                    
                                            
<div style="text-align: center;padding: 1rem;"><h3>Final Results</h3></div>        
                                            
                                    <table class="bokka" style="
                             width: 100%">
                               
                                <tr>
                                    <td style="background-color: #eeeeee"><b>Gold Champion</b></td>
                                    <td >
                                        
                                        <b>  {{$data->gold->reg_number}} </b><br />
                                        {{$data->gold->horse->name_en}} <br />
                                         {{$data->gold->horse->owner_name_en}} 
                                    </td>
                                    
                                </tr>
                                
                                  <tr>
                                         <td style="background-color: #eeeeee"><b>Silver Champion</b></td>
                                      <td >
                                          <b> {{$data->silver->reg_number}} </b> <br />
                                        {{$data->silver->horse->name_en}} <br />
                                         {{$data->silver->horse->owner_name_en}}
                                      
                                      </td>
                                    
                                    
                                </tr>
                                
                                  <tr>
                                       <td style="background-color: #eeeeee"><b>Bronze Champion</b></td>
                                      <td >
                                      
                                          <b>{{$data->bronze->reg_number}} </b><br />
                                        {{$data->bronze->horse->name_en}} <br />
                                         {{$data->bronze->horse->owner_name_en}}
                                      </td>
                                    
                                    
                                </tr>
                                
                            </table>    
     @endif                     
            
     <?php
     if(isset($_GET['siganture'])){
     ?>           
             
        <div style="text-align: center;padding: 1rem;"><h3>Judges Signatures</h3></div>  
     <table class="bokka" style="
                             width: 100%">
         <tr style="background-color: #eeeeee">
             <td><b>Judge</b></td>
             <td><b>Signature</b></td>
             
         </tr>


   <?php foreach ($judges as $judge){ ?>
                                
                                <tr>
                                    <td style="text-align: left;">{{$judge->name()}}</td>
                                    
                                    <td></td>
                                </tr>
                                
                                <?php } ?>



     <?php } ?>
</table>

                 </div> 
            









        @include('dashboard.shared.js')
        <script>
        
        
            let data = JSON.parse(`<?=json_encode($data->data);?>`);
                        
                    
           let points = data;
           for(var i=0;i<points.length;i++){
          
             document.getElementById('cell_' + points[i].gold_id+'_'+points[i].judge_id ).innerHTML = '4';  
             document.getElementById('cell_' + points[i].silver_id+'_'+points[i].judge_id ).innerHTML = '2';  
             document.getElementById('cell_' + points[i].bronze_id+'_'+points[i].judge_id ).innerHTML = '1';  
           }
           let totals = JSON.parse(`<?=json_encode($data->totals);?>`)
            for(var i=0;i<totals.length;i++){
          
             document.getElementById('cell_total_' + totals[i].horse_id).innerHTML = totals[i].total_score;  
             document.getElementById('cell_totalg_' + totals[i].horse_id).innerHTML =totals[i].count_gold;  
          
           }
            
            
            
        
        </script>

        
        
          <?php }else{ ?> <h5>No standings yet</h5>  <?php } ?>
    </body>

</html>
