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
                        
                        <div style="text-align: center;padding: 1rem;">
                            <button class="btn btn-warning m-2 no-print" onclick="window.print();">Print List</button>  
                            <a class="btn btn-info m-2 no-print"  href="{{url('qualifications-top/'.Route::input('class').'/'.Route::input('section'))}}">Show Top 10</a>  
                             <a class="btn btn-success m-2 no-print"  href="{{url('qualifications-top-printable/'.Route::input('class').'/'.Route::input('section'))}}">Print Top 10</a>  
                            
                        </div>
                    
                        

                        <?php
                        $class = \App\Models\CompetitionGroup::find(Route::input('class'));
                        
                         $count_sections = App\Models\HorseRegistration::select(['sectionLabel'])->where('group_id', Route::input('class'))->distinct()->count('sectionLabel');
            $is_single_section = $count_sections <= 1;
                        
                        $data = App\Models\HorseRegistration::where('status', 1)
                                ->where('group_id' ,Route::input('class'))
                                ->where('sectionLabel' ,Route::input('section'));
                        $categories = \App\Models\EvaluateCategory::get();
                        
                        ?>
                        <div style="text-align: center">  <p style="font-weight: bold;
    font-size: 1.5rem;">{{$class->name_en}} <?= $is_single_section ? "" : " Section ".Route::input('section')?></p>
                        
                        <hr />
                        </div>
                      
                        <?php

                        foreach ($data
                                
                                ->
                                 orderBy('total_points' ,'DESC') // tie
           ->orderBy('total_c1', 'DESC') ///tie
       ->orderBy('total_c2', 'DESC')
      ->orderBy('judge_selection', 'DESC')
                                
                                
                                
                                ->get() as $reg) {
                            if ($reg->total_points > 0) {
                                $horse = App\Models\Horse::find($reg->horse_id);
                                 $data = json_decode($reg->results_json);
                                ?> 

                                <div >

                                    <table class="table table-borderless">
                                        <tr><td style="text-align: left;
                                                width: 5%;
    vertical-align: middle;
    font-weight: bold;
    font-size: 2rem;">
                                            {{$reg->getRanking()}}
                                                        
                                            </td><td style="color: #000;font-weight: bold;text-align: left; ">{{$reg->reg_number}}- {{$horse->name_en}}
                                            <br />
                                            <b>Owner : </b> {{$horse->owner_name_en}}
                                            </td>
                                            <td style="text-align: right" class="no-print"><a class="btn btn-success" target="blank" href="{{url('horse-results/'.$reg->id)}}">Show on screen</a> | 
                                            
                                            <a class="btn btn-danger" target="blank" href="{{url('horse-results-print/'.$reg->id)}}">Print</a>
                                            </td></tr>
                                  
                                 
                                      
                                        <tr>

                                            <td colspan="100%">

                                                <table class="vn">

                                                    <tr class="bg-light">
                                                        <td>Judges</td>

        <?php
        foreach ($categories as $c) {
            ?>
                                                            <th>{{$c->name}}</th>
                                                            <?php
                                                        }
                                                        ?>
                                                        <th></th>
                                                        <th rowspan="{{count($data->judges_totals)+2}}" style="text-align: center;
    vertical-align: middle;
    "><div>
                                                                Total Marks <br />
        {{$reg->total_marks}}
        <hr />
         Points <br />
        <p style="    color: green;
                                                                      font-weight: bold;
                                                                      font-size: 1.3rem;">{{number_format($reg->total_points ,2)}} </p>
                                                                
                                                        </div></th>
                                                    </tr>


        <?php
       
        $judge_index = 0;
        foreach ($data->lines as $line) {
            ?>
                                                        <tr>
                                                            <th>{{\App\Models\User::find($line->judge_id)->name()}}</th>
                                                        <?php
                                                        foreach ($line->cats as $c) {
                                                            ?>
                                                                <td>{{$c->score}}</td>
                                                                <?php
                                                            }
                                                            ?>

                                                            <th>{{$data->judges_totals[ $judge_index]->total}}</th>


                                                        </tr>   
            <?php
            $judge_index++;
        }
        ?>
                                                    <tr>
                                                        <td>

                                                        </td> 
        <?php foreach ($data->cats_totals as $d) { ?>
                                                            <th>{{$d->total}}</th>
        <?php } ?>
                                                        <th></th>
                                                    </tr>

                                                </table>       





                                            </td>
                                        </tr>
                                    </table>  




                                </div>      

    <?php }
} ?>


                    </div>









                </div>
       

   

  
        @include('dashboard.shared.js')


    </body>

</html>
