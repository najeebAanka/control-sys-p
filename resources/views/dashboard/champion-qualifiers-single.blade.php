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


    <body >











        <div class="row">

            <div class="container">

                <div style="padding: 1rem;">
<!--                    <button class="btn btn-warning m-2 no-print" onclick="window.print();">Print List</button>  -->
                    <?php
                     $cmp_id = Route::input('cmp_id');
                    $c =  App\Models\ChampionClass::find(Route::input('id'));
                    if ( $c) {
                        ?> 

                    <div style="">
                    
                        
                        
                        
                        <div style="    text-align: center;
    font-size: 1.3rem;">
                            
                          <b>  {{$c->name_en}} </b>  
                        </div> 
                            <br />


    <?php
   
    foreach (\App\Models\CompetitionGroup::where('start_dob', '>=', $c->start_dob)
            ->where('end_dob', '<=', $c->end_dob)->where('gender', $c->gender)->where('competition_id' ,  $cmp_id)->get() as $class) {

        $sections = App\Models\HorseRegistration::select(['sectionLabel'])->where('group_id', $class->id)->distinct()->get();
        $single = count($sections) == 1;
        ?>


                                <?php
                                foreach ($sections as $section) { 
                                    ?>
                            <div class="ss-gh">
                              <div style="    text-align: center;
    font-size: 1.1rem;margin-bottom: 1rem;">
                            <?php
                                    echo $class->name_en . ( $single ? "" : "  Section " . $section->sectionLabel." " )
                                    . "<br />"; ?>
                              </div>
                            
                            
                            
                            <div class="line" >

                                            <table class="table table-bordered" >
                            
                            
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
                                            ->get()
                                            ;

                                       $three = [];
                                    foreach ( $data_all as $d){
                                      $three[] = $d;  
                                    }
                                      usort($three, fn($a, $b) => $a->id - $b->id);
                                    
                                    foreach (  $three as $reg) {

                                        $horse = App\Models\Horse::find($reg->horse_id);
                                        $data = json_decode($reg->results_json);
                                        ?>



                                        
                                                <tr><td style="text-align: center;
                                                        width: 5%;
                                                        vertical-align: middle;
                                                        color: #000;
                                                        ">
                                                        Rank.<br />      <span style=" font-weight: bold;
                                                                               font-size: 2rem;"> {{$reg->getRanking()}}</span> 

                                                    </td><td style="font-weight: bold;text-align: left; width: 70%;
                                                             vertical-align: middle">No: {{$reg->reg_number}}<br /> {{$horse->name_en}}
                                                        <br />
                                                        
                                                        <span style="    font-size: 14px;
                                                              font-weight: normal;"> <b>Owner : </b> {{$horse->owner_name_en}} </span>

                                                        <br />
                                                        <span style="    font-size: 14px;
                                                              font-weight: normal;"> <b>Breeder : </b> {{$horse->breeder_name_en}} </span>

                                                    </td>


                                                    <td style="text-align: center;vertical-align: middle" >   <p style="

                                                                                                                 ">
                                                            <span>Type</span><br />
                                                            <span style="
                                                                  font-weight: bold;">{{number_format($reg->total_c1 ,2)}}</span></p></td>


                                                    <td style="text-align: center;vertical-align: middle" >   <p style="

                                                                                                                 ">
                                                            <span>Movement</span><br />
                                                            <span style="
                                                                  font-weight: bold;">{{number_format($reg->total_c2 ,2)}}</span></p></td>


                                                    <td style="text-align: center;vertical-align: middle" >   <p style="

                                                                                                                 ">
                                                            <span>Points</span><br />
                                                            <span style="
                                                                  font-weight: bold;">{{number_format($reg->total_points ,2)}}</span></p></td>



                                                </tr>




                                          






            <?php } ?>
                            </table>  




                                        </div>  
                            </div>
                      
                                                <?php
        } ?>

                     
                          
                                
                                



    <?php } ?>
   </div>
                      

<?php } ?>
                 </div>   </div>




            </div>










        @include('dashboard.shared.js')
        <script>
       window.print();
        </script>

    </body>

</html>
