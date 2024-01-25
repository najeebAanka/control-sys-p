
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Other/html.html to edit this template
-->
<html>
    <head>
        <title>Horse evaluation</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style>

            body{
                background-color: #000;
                color: #fff;
                direction: ltr;
                font-family: system-ui;

            }

            .mantessa{
                width: 100%;
                border-collapse: collapse;
                border: none;
            }
            .swip{
                  width: 100%;
    border-collapse: collapse;
    border: none;
    margin: 0 auto;
    max-width: 800px;
            }
            .swip tr td{
                text-align: center;
               padding: 5px;
            }

            .mantessa tr td ,.mantessa tr th{
             text-align: center;
   
    border-color: #fff;
    border: 1px solid;
    font-weight: bold;
    font-size: 1.5rem;
            }
            .mantessa tr th{
                font-size: 1.2rem;
            }
            .judge-cell{
                border-top-width: 0px !important ;
                border-left-width: 0px !important ;
            }
            .no-top-cell{
                border-top-width: 0px !important ;
            }
            .no-right-cell{
                border-right-width: 0px !important ;
            }
            .no-left-cell{
                border-left-width: 0px !important ;
            }
            .st-corner-cell{
                border-top-width: 0px !important ;
                border-right-width: 0px !important ;
            }
            .corner-left-cell{
                border-bottom-width: 0px !important ;
                border-left-width: 0px !important ;
            }
            .corner-right-cell{
                border-bottom-width: 0px !important ;
                border-right-width: 0px !important ;
            }
            .no-bottom-cell{
                border-bottom-width: 0px !important ;
            }

            p.class-name {
                font-size: 2.3rem;
                color: orange;
                font-weight: bold;
            }
            p.horse-name {
                font-size: 1.6rem;
                font-weight: bold;
            }
            p.owner-name {
                font-size: 1.0rem;
            }
            span.owner-name-span {
                font-weight: bold;
            }


            p.total-marks {
                font-size: 1.6rem;
                font-weight: bold;
            }

            p.points {
                font-size: 2.3rem;
                font-weight: bold;
                color: #ffff00;
            }


            .filled {
                /* color: #49ff49; */
                /* border-color: #fff; */
                color: #49ff49;
                background: #003100;
                color: #ffffff;
                font-weight: bold;
            }
            p{
                margin: 0;
            }
            .judge-cell-td{
/*             font-weight: normal !important;
    font-size: 1rem !important;*/
            }
              .snb09{
                color: yellow;
                font-weight: bold;
                font-size: 0.9rem;
            }
        </style>



    </head>
    <body>

        <?php
        $lang = app()->getLocale();


            

       

     
                      $horseReg = \App\Models\HorseRegistration::find(Route::input('id')); 
                              if($horseReg) { 
                                  if($horseReg->total_points) { 
                    $c = App\Models\CompetitionGroup::find(  $horseReg->group_id);
                  
                    
            
                    
                    $horse = App\Models\Horse::find($horseReg->horse_id);

                    $categories = App\Models\EvaluateCategory::get();
               
                ?>   


                <div class="bg">

                    <table style="margin: 0 auto">
                        <tr>
                            <td>   <table class="swip">
                        <tr>
                            <td colspan="100%">
                                <table style="width: 100%">
                                    <tr>
                                        <td colspan="100%">
                                            <img src="{{url('assets/cover-full.jpeg')}}" style="width: 100%;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                      
                                        <td style="text-align: left">      
                                            <p  class="class-name" style="font-size: 1rem;">{{ $c->name_en}}  {{App\Models\HorseRegistration::select(['sectionLabel'])->where('group_id', $c ->id)->distinct()->count('sectionLabel') >  1  ? " Section " .$horseReg->sectionLabel : ""}}</p>
                                            <p  class="class-name" >{{ $horseReg->reg_number}} &nbsp; &nbsp; &nbsp;  {{$horse->name_en}}  </p>
                                            <p  class="horse-name">{{$c->title}}</p>
                                            <p  class="owner-name">Owner : <span class="owner-name-span">{{$horse->owner_name_en}}</span></p>
                                           <p  class="owner-name">Breeder : <span class="owner-name-span">{{$horse->breeder_name_en}}</span></p></td>

                                        <td style="text-align: center">     <img style="max-width: 80px;" src="https://dashboard.ejudge.ae/public/flags/{{$horse->owner_country_name_en}}.gif" /></td>


                                    </tr>
                                </table>  
                            </td>
                        </tr>

                        <tr>
                            <td>   
                            
                            
                                    <table class="mantessa">
                                        
                                        <tr>
                                            <td class="judge-cell"></td>
                                            
                                            <?php
                                                            foreach ( $categories as $c){
                                                                ?>
                                            <th class="no-top-cell">{{$c->name}}</th>
                                            <?php
                                                                
                                                            }
                                            ?>
                                            <th class="st-corner-cell"></th>
                                        </tr>
                                        
                                    
                                        <?php
                                        $data = json_decode($horseReg->results_json);
                                        $judge_index = 0;
                                        foreach ($data->lines as $line) {
                                            ?>
                                        <tr>
                                            <th class="no-left-cell"><?= \App\Models\User::find($line->judge_id)->name()?></th>
                                            <?php
                                                foreach ($line->cats as $c){
                                                    ?>
                                            <td class="no-right-cell" >{{$c->score}}</td>
                                            <?php
                                                    
                                                }
                                            ?>
                                            
                                            <th class="no-right-cell" style="font-size: 1.3rem;
    color: #c8ffca;">{{$data->judges_totals[ $judge_index]->total}}</th>
                                            
                                            
                                        </tr>   
                                            <?php
                                        $judge_index++;  }
                                        
                                        ?>
                                        <tr>
                                            <td class="corner-left-cell">
                                                
                                            </td> 
                                            <?php foreach ($data->cats_totals as $d){ ?>
                                            <th class="no-bottom-cell"  style="font-size: 1.3rem;
    color: #c8ffca;">{{$d->total}}</th>
                                            <?php } ?>
                                               <th class="corner-right-cell"></th>
                                        </tr>
                                        
                                    </table>     
                            
                            
                            
                            
                            
                            
                            </td>
                            <td>
                                
                                <h3 style="color: #ff6c6c;">Rank <br /><span style="  font-size: 2.3rem;" id="rr690"> ? </span></h3>
 <hr /> 
                                <h3>Total Marks</h3>
                                <p class="total-marks should-reset-cell" id="total_marks_p">{{$horseReg->total_marks}}</p>

                                <hr /> 
                                <h3>Points</h3>
                                <p class="points should-reset-cell" id="points_p">{{number_format($horseReg->total_points ,2)}}</p>
                            </td>

                        </tr>  


                    </table>    </td>
                            <td> <div  style="margin-top: 1rem;overflow-y: auto;max-height: 41rem;padding-right: 20px;" id="tops-container"></div></td>
                            
                        </tr>
                    </table>

                 
                    <hr />
                    <div style="text-align: center;padding: 1rem;" id="footer-pager">

<?php
                                    foreach (App\Models\HorseRegistration::where('group_id' ,$horseReg ->group_id)->
                                            where('sectionLabel' ,$horseReg ->sectionLabel)->where('total_points' ,'>' ,0)->get() as $cn){
                                        
                                        ?>
                        <a style="color: #fff;margin: 5px;" href="{{url('horse-results/'.$cn->id)}}">{{$cn->reg_number}}</a> | 
                    <?php
                                        
                                            }

?>
                    </div>

                </div>

        
        
        
        
        
        
        
               <script>








                
              
                
                    function refData() {
                   // console.log("refresh scoket..");
                    // Creating Our XMLHttpRequest object 
                    var xhr = new XMLHttpRequest();
                    // Making our connection  
                    var url = '{{url("api/v1/current-horse-eval/".$horseReg->id)}}';
                    xhr.open("GET", url, true);
                    // function execute after request is successful 
                    xhr.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                             let data = JSON.parse(this.responseText);
                        
                    
            
            let all = data.tops.data;
          
            var tops_str = "";
            var footer_str = "";
            for(var i=0;i<all.length;i++){
                let reg = all[i];
                
       tops_str+='<div class="line" ><table class="table table-borderless" style="    border-bottom: solid 1px #fff;"><tr><td style="text-align: center;'
       +' width: 5%; vertical-align: middle; color: #fff;">  Rank.<br /> <span style=" font-weight: bold; font-size: 2rem;"> '+reg.ranking 
       + '</span> </td><td style="color: #fff;font-weight: bold;text-align: left; width: 70%; vertical-align: middle;padding-left : 5px"><span style="color : orange"> <span style="color : pink">'+reg.reg_number+') &nbsp;&nbsp;  </span> '+reg.name+
       '</span><br /><span style="    font-size: 0.8rem;font-weight: normal;"> <b>Owner : </b>'+reg.owner+'</span> <br />  \n\
<span style="    font-size: 0.8rem;font-weight: normal;"> <b>Type: </b> <span class="snb09">'+reg.total_c1+'</span></span> &nbsp; &nbsp; &nbsp; \n\
<span style="    font-size: 0.8rem;font-weight: normal;"> <b>Movement: </b> <span class="snb09">'+reg.total_c2+'</span></span> </td><td style="text-align: right;vertical-align: middle">\n\
 <img style="height: 50px;" src="https://dashboard.ejudge.ae/public/flags/'+reg.country+'.gif" /></td>'
       +'<td style="text-align: center;vertical-align: middle" >   <p style="    color: #fff; "> '+
       ' <span>Points</span><br /> <span style="color: red;font-weight: bold;">'+reg.total_points+'</span></p></td></tr> </table> </div> ';         
             
             footer_str += "<a style=\"color: #fff;margin: 5px;\" href=\"{{url('horse-results')}}/"+reg.id+"\">"+reg.reg_number+"</a> |";
             if(reg.id == {{Route::input('id')}}) document.getElementById('rr690').innerHTML = reg.ranking;
             } 
              // console.log(tops_str);
            document.getElementById('tops-container').innerHTML = (tops_str);
            document.getElementById('footer-pager').innerHTML = (footer_str);
            
            
            
            
                    if (data.refresh == true)
                            setTimeout(refData, 7000);            else
                            console.log("Refresh stoped")
                    }
                    }
                    // Sending our request 
                    xhr.send();
                    }

                    refData();







                </script>
        
        
        
        
        
          
    <?php 
                              }else{
                                  ?>
                
                <h4>No results yet</h4>
                <?php
                                  
                              }
                              }
                                
    
    
   

?>  

    </body>
</html>
