<!DOCTYPE html>
<html lang="en">

    <?php

    ?>
        

    @include('dashboard.shared.css')


    <style>

   
body{
    background-color: #000;
    color: #fff;
}

    </style>


    <body>

   

      

      

           



                <div class="row">

                    <div class="bv" style="max-width: 46.2rem;
    margin: 0 auto;">
                        
                    
                        

                        <?php
                        $class = \App\Models\CompetitionGroup::find(Route::input('class'));
                        
                         $count_sections = App\Models\HorseRegistration::select(['sectionLabel'])->where('group_id', Route::input('class'))->distinct()->count('sectionLabel');
            $is_single_section = $count_sections <= 1;
                        
                       $data_all = App\Models\HorseRegistration::where('status', 1)
                                ->where('group_id' ,Route::input('class'))
                                ->where('sectionLabel' ,Route::input('section'))
                                
                                
                                ;
                        $categories = \App\Models\EvaluateCategory::get();
                        
                        ?>
                        <div style="text-align: center">  <p style="font-weight: bold;margin-top: 2rem;
    font-size: 1.5rem;">{{$class->name_en}} <?= $is_single_section ? "" : " Section ".Route::input('section'); ?></p>
                        
                        <hr />
                        </div>
                      
                        <?php

                        foreach ($data_all->
                                     orderBy('total_points' ,'DESC') // tie
           ->orderBy('total_c1', 'DESC') ///tie
       ->orderBy('total_c2', 'DESC')
      ->orderBy('judge_selection', 'DESC')
                                
                                
                                
                                ->get() as $reg) {
                            if ($reg->total_points > 0) {
                                $horse = App\Models\Horse::find($reg->horse_id);
                                 $data = json_decode($reg->results_json);
                                ?> 

                        <div class="line" >

                            <table class="table table-borderless" style="    border-bottom: solid 1px #fff;">
                                        <tr><td style="text-align: center;
                                                width: 5%;
    vertical-align: middle;
    color: #fff;
   ">
                                          Rank.<br />      <span style=" font-weight: bold;
    font-size: 2rem;"> {{$reg->getRanking()}}</span> 
                                                        
                                            </td><td style="color: #fff;font-weight: bold;text-align: left; width: 70%;
                                                     vertical-align: middle">{{$reg->reg_number}}) {{$horse->name_en}}
                                            <br />
                                            <span style="    font-size: 0.8rem;
    font-weight: normal;"> <b>Owner : </b> {{$horse->owner_name_en}} </span>
    
      <br />
                                            <span style="    font-size: 0.8rem;
    font-weight: normal;"> <b>Breeder : </b> {{$horse->breeder_name_en}} </span>
    
                                            </td>
                                            
                                              <td style="text-align: right;vertical-align: middle">     <img style="height: 50px;" src="https://dashboard.ejudge.ae/public/flags/{{$horse->owner_country_name_en}}.gif" /></td>
                                            
                                            <td style="text-align: center;vertical-align: middle" >   <p style="    color: #fff;
                                                                     
                                                                    ">
                                                    <span>Points</span><br />
                                                    <span style="color: red;
    font-weight: bold;">{{number_format($reg->total_points ,2)}}</span></p></td></tr>
                                  
                                 
                                      
                                 
                                    </table>  




                                </div>      

    <?php }
} ?>


                    </div>









                </div>
       

   

  
        @include('dashboard.shared.js')


    </body>

</html>
