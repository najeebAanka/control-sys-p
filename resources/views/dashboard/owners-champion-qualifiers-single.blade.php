<!DOCTYPE html>
<html lang="en">

    <?php
         $cmp_id = Route::input('cmp_id');
       $cmp = \App\Models\Competition::find($cmp_id);
    ?>

    
    


    @include('dashboard.shared.css')


    <style>
        @media print
        {
            .no-print, .no-print *
            {
                display: none !important;
            }
                     .ss-gh {
    break-inside: avoid;
  }
        }

        body{
            background-color: #fff;
        }
        .single-reg {
            background-color: #fff;
            padding: 1rem;
            margin-bottom: 1rem;

        }


        table {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        table tfoot thead {
            break-inside: auto;
            overflow: hidden;
        }
        .vn{
            width: 100%;
            border-collapse: collapse;
        }
        .vn tr td,.vn tr  th {
            border: 1px solid;
            text-align: center !important;
            padding: 2px;
        }
        table tr td ,table tr th{
            font-size: 14px;
        }

    </style>


    <body>











        <div style="padding: 1rem;">
            
            
            <h4> Dear Participant, </h4>
<p>The Higher organizing committee of the DUBAI INTERNATIONAL ARABIAN HORSE CHAMPIONSHIP would like to congratulate on your winning at 
       <?=
    $cmp->name_en
    ?> 
Please find your prize money report :
</p>
    
            
   <hr />      
   <h4>Owner : {{Route::input('owner')}}</h4>
                <div >
                    <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Horse</th>
                            <th>Class</th>
                            <th>Points</th>
                            <th>Rank</th>
                            <th>Amount</th>
                            <th>Deduction</th>
                            <th>Net Total</th>
                        </tr>
                        
               
                    <?php
                      $total_amount = 0;
                 
                    $classes = App\Models\ChampionClass::get();
                    foreach ($classes as $c) {
                      

  
    foreach (\App\Models\CompetitionGroup::where('start_dob', '>=', $c->start_dob)
            ->where('end_dob', '<=', $c->end_dob)->where('gender', $c->gender)
            ->where('competition_id' , $cmp_id )
            ->get() as $class) {

        $sections = App\Models\HorseRegistration::select(['sectionLabel'])->where('group_id', $class->id)->distinct()->get();
        $single = count($sections) == 1;
        foreach ($sections as $section) { 
                           
                                    $data_all = App\Models\HorseRegistration::
                                            join('horses' ,'horses.id' ,'horse_registrations.horse_id')
                                           ->select(['horse_registrations.id' ,'horse_registrations.horse_id'])->
                                            where('horse_registrations.status', 1)
                                         
                                            ->where('horse_registrations.group_id', $class->id)
                                            ->where('horse_registrations.total_points', '>', 0)
                                            ->where('horse_registrations.sectionLabel', $section->sectionLabel)
                                          
                                            ->
                                            orderBy('horse_registrations.total_points', 'DESC') // tie
                                            ->orderBy('horse_registrations.total_c1', 'DESC') ///tie
                                            ->orderBy('horse_registrations.total_c2', 'DESC')
                                            ->orderBy('horse_registrations.judge_selection', 'DESC')
                                            ->take(7)
                                            ->get()
                                            ;

                                  
                                    //  usort($three, fn($a, $b) => $a->id - $b->id);
                                    
                                    foreach (  $data_all as $reg_id) {
                                       $reg = App\Models\HorseRegistration::find($reg_id->id); 
                                       $horse = App\Models\Horse::find($reg_id->horse_id); 
                                       if($horse->owner_name_en == Route::input('owner')){

                          ?>              
              <tr>
                        <td>{{$reg->reg_number}}</td>
                        <td>{{$horse->name_en}}</td>
                        <td>{{$class->name_en}} </td>
                        <td>{{$reg->total_points}}</td>
                        <td>{{$rank=$reg->getRanking()}}</td>
                        <?php
                        $prize = App\Models\ClassPrize::where('class_id' ,-1)
                                ->where('competition_id' ,$cmp_id)->where('rank' ,$rank)->
                                where('target_type' ,'all-classes')->first();
                        if($prize){
                            $net = $prize->amount - ($prize->discount_fee *100.0  / $prize->amount);
                            $total_amount+=$net;
                            ?>
                         <td>{{$prize->amount}}</td>
                        <td>{{$prize->discount_fee}}</td>
                        <td>{{number_format( $net,0)}}</td>
                        <?php
                            
                        }
                        
                        ?>
                        
                        
                    </tr>   
                    <?php
                                        
                    }   }  } } }


?>
      
                  
                      

                         </table>
                 </div>   
            
            
            <h4>Champion classes</h4>
            <table class="table table-bordered">
                
                <tr>
                    <th>No</th>
                    <th>Horse Name</th>
                    <th>Place</th>
                    <th>Class</th>
                    <th>Amount</th>
                    <th>Deduction</th>
                    <th>Net total</th>
                    
                </tr>
<?php
 
                   
                      $data = new stdClass();
                      foreach (App\Models\ChampionClass::get() as $c)
                    {
         $controller = new App\Http\Controllers\Dashboard\ChampionClassesController();
        $horses = $controller->getQualifiedHorses($c->id ,$cmp_id);
        $list = [];
     foreach (  $horses as $h){
              $list[]=$h->id;
     }
     
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
                                  
                          
?>
  <?php if($data->gold->horse->owner_name_en == Route::input('owner')){ ?>          
 
                <tr>
                    <td>{{$data->gold->reg_number}}</td>  
                    <td>{{$data->gold->horse->name_en}}</td>  
                    <td>Gold Champion</td>  
                    <td>{{$c->name_en}}</td>  
                     <?php
                        $prize = App\Models\ClassPrize::where('class_id' ,$c->id)
                                ->where('competition_id' ,$cmp_id)->where('rank' ,1)->
                                where('target_type' ,'champion-class')->first();
                          if($prize){
                            $net = $prize->amount - ($prize->discount_fee *100.0  / $prize->amount);
                            $total_amount+=$net;
                            ?>
                         <td>{{$prize->amount}}</td>
                        <td>{{$prize->discount_fee}}</td>
                        <td>{{number_format( $net,0)}}</td>
                        <?php
                            
                        }
                        
                        ?>
                </tr>        
            
  <?php } ?>
                  <?php if($data->silver->horse->owner_name_en == Route::input('owner')){ ?>          
 
                <tr>
                    <td>{{$data->silver->reg_number}}</td>  
                    <td>{{$data->silver->horse->name_en}}</td>  
                    <td>Silver Champion</td>  
                    <td>{{$c->name_en}}</td>  
                     <?php
                        $prize = App\Models\ClassPrize::where('class_id' ,$c->id)
                                ->where('competition_id' ,$cmp_id)->where('rank' ,2)->
                                where('target_type' ,'champion-class')->first();
                          if($prize){
                            $net = $prize->amount - ($prize->discount_fee *100.0  / $prize->amount);
                            $total_amount+=$net;
                            ?>
                         <td>{{$prize->amount}}</td>
                        <td>{{$prize->discount_fee}}</td>
                        <td>{{number_format( $net,0)}}</td>
                        <?php
                            
                        }
                        
                        ?>
                </tr>        
            
  <?php } ?>
                  <?php if($data->bronze->horse->owner_name_en == Route::input('owner')){ ?>          
 
                <tr>
                    <td>{{$data->bronze->reg_number}}</td>  
                    <td>{{$data->bronze->horse->name_en}}</td>  
                    <td>Bronze Champion</td>  
                    <td>{{$c->name_en}}</td>  
                     <?php
                        $prize = App\Models\ClassPrize::where('class_id' ,$c->id)
                                ->where('competition_id' ,$cmp_id)->where('rank' ,3)->
                                where('target_type' ,'champion-class')->first();
                        if($prize){
                            $net = $prize->amount - ($prize->discount_fee *100.0  / $prize->amount);
                            $total_amount+=$net;
                            ?>
                         <td>{{$prize->amount}}</td>
                        <td>{{$prize->discount_fee}}</td>
                        <td>{{number_format( $net,0)}}</td>
                        <?php
                            
                        }
                        
                        ?>
                </tr>        
            
  <?php } ?>
            
            
        
                      <?php } ?>     

            </table>
            
            
            <p>Total Amount : {{number_format($total_amount ,0)}} AED</p>
            
            
            <p>
                Please fill and return the attached BANK DETAILS FORM for the prize money transfer, 
                you will also see the Prize Money shows the 0% deduction as management amenity Except Foal classes.
we sincerely hope that you enjoyed time at <?=$cmp->name_en?> and look forward to seeing you again in <?=date('Y')+1?>.
Yours Faithfully


<hr />
Qusai Obaidalla
Board Member & General Manager 

                
            </p>
            </div>










        @include('dashboard.shared.js')


    </body>

</html>
