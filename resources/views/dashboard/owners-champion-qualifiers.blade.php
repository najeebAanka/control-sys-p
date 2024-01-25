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











        <div class="row">

            <div class="container">

                <div style="padding: 1rem;">
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <th>Winner horses</th>
                            <th>Actions</th>
                        </tr>
                        
               
                    <?php
                        $map = [];
                      $cmp_id = Route::input('cmp_id');
                      
                    $cmp = \App\Models\Competition::find($cmp_id);
                    $classes = App\Models\ChampionClass::get();
                    foreach ($classes as $c) {
                      

  
    foreach (\App\Models\CompetitionGroup::where('start_dob', '>=', $c->start_dob)
            ->where('end_dob', '<=', $c->end_dob)->where('gender', $c->gender)
            ->where('competition_id' , $cmp_id )
            ->get() as $class) {

        $sections = App\Models\HorseRegistration::select(['sectionLabel'])->where('group_id', $class->id)->distinct()->get();
        $single = count($sections) == 1;
        foreach ($sections as $section) { 
                           
                                    $data_all = App\Models\HorseRegistration::where('status', 1)
                                            ->where('group_id', $class->id)
                                            ->where('total_points', '>', 0)
                                            ->where('sectionLabel', $section->sectionLabel)
                                          
                                            ->
                                            orderBy('total_points', 'DESC') // tie
                                            ->orderBy('total_c1', 'DESC') ///tie
                                            ->orderBy('total_c2', 'DESC')
                                            ->orderBy('judge_selection', 'DESC')
                                            ->take(7)
                                            ->get()
                                            ;

                                  
                                    //  usort($three, fn($a, $b) => $a->id - $b->id);
                                    
                                    foreach (  $data_all as $reg) {
$map[]=$reg->horse_id;
}   }  } }



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
              
        
          $data->silver = App\Models\HorseRegistration::whereIn('id' ,$list)->whereNotIn('id' ,[$data->gold->id])->where('champion_score' ,'>' ,0)
                 ->orderBy('champion_score' ,'DESC')->orderBy('total_points', 'DESC') // tie
                                            ->orderBy('total_c1', 'DESC') ///tie
                                            ->orderBy('total_c2', 'DESC')
                                            ->orderBy('judge_selection', 'DESC')->first();
          
          
          
          
            $data->bronze = App\Models\HorseRegistration::whereIn('id' ,$list)
                    ->whereNotIn('id' ,[$data->gold->id , $data->silver->id])->where('champion_score' ,'>' ,0)
                 ->orderBy('champion_score' ,'DESC')->orderBy('total_points', 'DESC') // tie
                                            ->orderBy('total_c1', 'DESC') ///tie
                                            ->orderBy('total_c2', 'DESC')
                                            ->orderBy('judge_selection', 'DESC')->first();
            
           if(!in_array($data->gold->id, $map))$map[]=$data->gold->id;
           if(!in_array($data->silver->id, $map))$map[]=$data->gold->id;
           if(!in_array($data->bronze->id, $map))$map[]=$data->gold->id;
                    }


 foreach (App\Models\Horse::whereIn('id' ,$map)->select(['owner_name_en as name' ,DB::RAW('count(*) as total')])
         ->groupBy('owner_name_en')->orderBy('total' ,'desc')->get() as $owner){

?>
      
                    <tr>
                        <td>{{$owner->name}}</td>
                        <td>{{$owner->total}}</td>
                        <td><a  target="blank" href="{{url('dashboard/owners-champion-qualifiers-single/'.$cmp_id.'/'.$owner->name)}}" class="btn btn-success" href="{{$owner->total}}"> Generate prizes report</a>  </td>
                        
                        
                    </tr>
                      
                    
 <?php } ?>
                         </table>
                 </div>   </div>




            </div>










        @include('dashboard.shared.js')


    </body>

</html>
