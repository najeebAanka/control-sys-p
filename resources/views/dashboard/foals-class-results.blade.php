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
                    $c = App\Models\CompetitionGroup::find(Route::input('id'));
                    $id = $c->id;
                     
                    
                    
                           $judges = \App\Models\User::where('user_type' ,0)
                                        ->where('status' ,1)
//                                        ->whereIn('id' ,
//                                                \App\Models\ClassJudge::where('class_id' ,$id)
//                                        ->pluck('judge_id')
//                                        
//                                        )
                                        ->orderBy('order_label')->get();
                        
        $jlist = [];
        foreach ($judges as $j){
                   $jlist[]=$j->id;          
                        }
                    
                    
 $data = new stdClass();
      $cmp_id = $c->competition_id;
        
        $data->data = \App\Models\FoalRating::where('class_id'  ,$id)->where('champion_id' ,$cmp_id )->get();
       
        foreach ($data->data as $d){
           
              $d->first_id = App\Models\HorseRegistration::find($d->first_id)->reg_number;
            $d->second_id =  App\Models\HorseRegistration::find($d->second_id)->reg_number;
            $d->third_id =  App\Models\HorseRegistration::find($d->third_id)->reg_number;
            $d->fourth_id =  App\Models\HorseRegistration::find($d->fourth_id)->reg_number;
            $d->fifth_id =  App\Models\HorseRegistration::find($d->fifth_id)->reg_number;
            
        }
        
           $data->count_scores = count($data->data);
             if ($data->count_scores > 0) {
        
     
               
                        
                        
                        
                        
                        
        
        $data->refresh = count($data->data) < count($judges);
      //  die(count($data->data) ." ". count($judges));
        
        
     
        $horses = \App\Models\HorseRegistration::where('group_id', $c->id)
                    ->where('status' ,1) ->get();
         $totals = [];
       $list = [];
        foreach (  $horses as $h){
              $list[]=$h->id;
          $p = new stdClass();
          $p->horse_id = $h->id;
        
        }
         
         if(!$data->refresh){
        
      
      $data->first = App\Models\HorseRegistration::whereIn('id' ,$list)->where('foal_points' ,'>' ,0)
                 ->orderBy('foal_points' ,'DESC')->orderBy('count_selected_foals' ,'DESC')->first();
        $data->first->horse = \App\Models\Horse::find($data->first ->horse_id);
        //-0------------------------
           $data->second = App\Models\HorseRegistration::whereIn('id' ,$list)
                   ->whereNotIn('id' ,[$data->first->id])
                  ->where('foal_points' ,'>' ,0)
                 ->orderBy('foal_points' ,'DESC')->orderBy('count_selected_foals' ,'DESC')->first();
        $data->second->horse = \App\Models\Horse::find($data->second ->horse_id);
            //-0------------------------
        
            $data->third = App\Models\HorseRegistration::whereIn('id' ,$list)
                   ->whereNotIn('id' ,[$data->first->id ,$data->second->id])
                 ->where('foal_points' ,'>' ,0)
                 ->orderBy('foal_points' ,'DESC')->orderBy('count_selected_foals' ,'DESC')->first();
        $data->third->horse = \App\Models\Horse::find($data->third ->horse_id);
            //-0------------------------
              $data->fourth = App\Models\HorseRegistration::whereIn('id' ,$list)
                   ->whereNotIn('id' ,[$data->first->id ,$data->second->id,$data->third->id])
                  ->where('foal_points' ,'>' ,0)
                 ->orderBy('foal_points' ,'DESC')->orderBy('count_selected_foals' ,'DESC')->first();
        $data->fourth->horse = \App\Models\Horse::find($data->fourth ->horse_id);
            //-0------------------------
              $data->fifth = App\Models\HorseRegistration::whereIn('id' ,$list)
                   ->whereNotIn('id' ,[$data->first->id ,$data->second->id,$data->third->id,$data->fourth->id])
               ->where('foal_points' ,'>' ,0)
                 ->orderBy('foal_points' ,'DESC')->orderBy('count_selected_foals' ,'DESC')->first();
        $data->fifth->horse = \App\Models\Horse::find($data->fifth ->horse_id);
            //-0------------------------
    
         }
                        
                        
                        
                        ?> 

           

<div style="text-align: center;
     padding: 2rem;">
    <h3><?=$c->name_en?></h3>
    
    <h5 class="no-print"><a href="?siganture=yes">Open judges version (signature)</a></h5>
    

    
    
</div>                                         
                              



       <table class="mantessa"  style="width: 100% ; <?=isset($_GET['siganture']) ? "display : none" : "" ?>">
           <tr style="background-color: #e1e1e1"><td style="text-align: left;"><span style="font-weight: bold">Judges</span></td>
                                    <td>1st</td>
                                    <td>2nd</td>
                                    <td>3rd</td>
                                    <td>4th</td>
                                    <td>5th</td>
                                      
                                
                                </tr>
                                
                                
                                <?php foreach (  $judges as $judge){
                                    
                                    ?>
                                
                                <tr>
                                    <td style="text-align: left;
    font-size: 15px;
    font-weight: normal;
    min-width: 12rem;"> {{$judge->name()}}</td>
                                    
                                    
                                    <td  id="cell_{{$judge->id}}_first"></td>
                                    <td  id="cell_{{$judge->id}}_second"></td>
                                    <td   id="cell_{{$judge->id}}_third"></td>
                                    <td   id="cell_{{$judge->id}}_fourth"></td>
                                    <td   id="cell_{{$judge->id}}_fifth"></td>
                                   
                                </tr>
                                
                                <?php } ?>
                                
                                <tr>
                                    <td colspan="100%" style="text-align: center;border: none">
                                        <div style="height: 1rem"></div>
                                       
                                    </td>
                                </tr>
                                
                            
                                
                                   <tr>
                                    <td colspan="100%" style="text-align: center;border: none">
                                           <div style="height: 1rem"></div>
                                      
                                    </td>
                                </tr>
                                
                               
                                
                                

                                            </table>

                     
                          
                                
                                

                                            
                                            
    @if(!$data->refresh)                                    
                                            
<div style="text-align: center;padding: 1rem;"><h3>Final Results</h3></div>        
                                            
                                    <table class="bokka" style="
                             width: 100%">
                               
                                <tr>
                                    <td style="background-color: #eeeeee"><b>1st place</b></td>
                                    <td >
                                        
                                        <b>  {{$data->first->reg_number}} </b><br />
                                        {{$data->first->horse->name_en}} <br />
                                         {{$data->first->horse->owner_name_en}} 
                                    </td>
                                        <td>Points <br /> {{$data->first->foal_marks}}</td>
                                </tr>
                                
                                  <tr>
                                         <td style="background-color: #eeeeee"><b>2nd place</b></td>
                                      <td >
                                          <b> {{$data->second->reg_number}} </b> <br />
                                        {{$data->second->horse->name_en}} <br />
                                         {{$data->second->horse->owner_name_en}}
                                      
                                      </td>
                                        <td>Points <br /> {{$data->second->foal_marks}}</td>
                                    
                                </tr>
                                
                                  <tr>
                                       <td style="background-color: #eeeeee"><b>3rd place</b></td>
                                      <td >
                                      
                                          <b>{{$data->third->reg_number}} </b><br />
                                        {{$data->third->horse->name_en}} <br />
                                         {{$data->third->horse->owner_name_en}}
                                      </td>
                                        <td>Points <br /> {{$data->third->foal_marks}}</td>
                                    
                                </tr>
                                
                                
                                   <tr>
                                       <td style="background-color: #eeeeee"><b>4th place</b></td>
                                      <td >
                                      
                                          <b>{{$data->fourth->reg_number}} </b><br />
                                        {{$data->fourth->horse->name_en}} <br />
                                         {{$data->fourth->horse->owner_name_en}}
                                      </td>
                                        <td>Points <br /> {{$data->fourth->foal_marks}}</td>
                                    
                                </tr>
                                
                                
                                   <tr>
                                       <td style="background-color: #eeeeee"><b>5th place</b></td>
                                      <td >
                                      
                                          <b>{{$data->fifth->reg_number}} </b><br />
                                        {{$data->fifth->horse->name_en}} <br />
                                         {{$data->fifth->horse->owner_name_en}}
                                      </td>
                                      
                                      <td>Points <br /> {{$data->fifth->foal_marks}}</td>
                                    
                                    
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
          console.log("setting "+points[i].first_id+" in " + 'cell_' +points[i].judge_id +"_first")
             document.getElementById('cell_' +points[i].judge_id +"_first").innerHTML = '<span class="td-score-cell">'+ points[i].first_id + '</span>'; 
             document.getElementById('cell_' +points[i].judge_id +"_second").innerHTML ='<span class="td-score-cell">'+ points[i].second_id+ '</span>'; ; 
             document.getElementById('cell_' +points[i].judge_id +"_third").innerHTML = '<span class="td-score-cell">'+points[i].third_id+ '</span>'; ; 
             document.getElementById('cell_' +points[i].judge_id +"_fourth").innerHTML = '<span class="td-score-cell">'+points[i].fourth_id+ '</span>'; ; 
             document.getElementById('cell_' +points[i].judge_id +"_fifth").innerHTML = '<span class="td-score-cell">'+points[i].fifth_id+ '</span>'; ; 


           }
            
            
            
        
        </script>

        
        
          <?php }else{ ?> <h5>No standings yet</h5>  <?php } ?>
    </body>

</html>
