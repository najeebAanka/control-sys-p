
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
        min-width: 60px;

            }
            
            .icon-px{
                width: 20px;
                border-radius: 50%;
                border-color: #fff;
                border: solid 2px #fff;
                    
            }
             .icon-px{
/*              background-color: #dcdcdc;*/
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
font-weight: normal!important;
    min-width: 17rem!important;
            }
            
            .bokka tr td{
                text-align: center;
                padding: 5px;
                min-width: 65px;
            }
            .td-score-cell{
                color: yellow;
    font-weight: normal !important;
    font-size: 1.2rem;
            }
        </style>



    </head>
    <body>

        <?php
        $lang = app()->getLocale();

        if (\App\Models\Competition::where('status', 1)->count() == 0) {
            
        } else {
            $controller = new App\Http\Controllers\Api\CompetitionsController();

            $comp = \App\Models\Competition::select(['id', 'name_' . $lang . ' as title', 'description_' . $lang . ' as description'
                        , 'logo', 'active_class', 'active_phase as phase' ,'active_section'])->where('status', 1)->orderBy('id', 'desc')->first();

            if ($comp->phase == 1) {


                $comp->logo = $comp->buildStorageBase() . "/" . $comp->logo;

                if ($comp->active_class != -1) {

                    $c = App\Models\CompetitionGroup::find($comp->active_class);
                    $horseReg = \App\Models\HorseRegistration::find($c->current_horse);
                    
                    if($horseReg) { 
                    
                    $horse = App\Models\Horse::find($horseReg->horse_id);

                    $categories = App\Models\EvaluateCategory::get();
                    
                      

                    
               
                ?>   


                <div class="bg">



                    <table class="swip">
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
                                            <p  class="class-name" style="font-size: 1rem;">{{ $c->name_en}} {{App\Models\HorseRegistration::select(['sectionLabel'])->where('group_id', $comp->active_class)->distinct()->count('sectionLabel') >  1  ? " Section " .$horseReg->sectionLabel : ""}} </p>
                                            <p  class="class-name" >{{ $horseReg->reg_number}} &nbsp; &nbsp; &nbsp;  {{$horse->name_en}}  </p>
                                            <p  class="horse-name">{{$c->title}}</p>
                                            <p  class="owner-name">Owner : <span class="owner-name-span">{{$horse->owner_name_en}}</span></p>
                                           <p  class="owner-name">Breeder : <span class="owner-name-span">{{$horse->breeder_name_en}}</span></p></td>

                                        <td style="text-align: right">     <img style="height: 50px;" src="https://dashboard.ejudge.ae/public/flags/{{$horse->owner_country_name_en}}.gif" /></td>


                                    </tr>
                                </table>  
                            </td>
                        </tr>

                        <tr>
                            <td>     <table class="mantessa">
                                    <tr>
                                        <th class="judge-cell">Judges</th>
        <?php foreach ($categories as $cat) { ?>
                                            <th class="no-top-cell">{{$cat->name}}</th>
                        <!--                    <th class="no-top-cell">H/N</th>
                                            <th class="no-top-cell">Body</th>
                                            <th class="no-top-cell">Legs</th>
                                            <th class="no-top-cell">Mnts</th>-->
        <?php } ?>
                                        <th class="st-corner-cell"></th>
                                    </tr>

        <?php
        
   
        foreach (App\Models\ClassJudge::join('users' ,'users.id' ,'class_judges.judge_id')->
                select(['class_judges.*'])->where('class_judges.class_id', $c->id)
                ->where('class_judges.section', $comp->active_section)->orderby('users.order_label' ,'ASC')->get() as $link) {
            $judge = \App\Models\User::find($link->judge_id);
            ?>   
                                        <tr>

                                            <td class="no-left-cell judge-cell-td" style="text-align: left">{{ $judge->name()}}</td> 
                                            <?php foreach ($categories as $cat) { ?>
                                                <td id="cell_{{$judge->id}}_{{$cat->id}}" class="cell_cat_{{$cat->id}} cell_judge_{{$judge->id}}">-/-</td>  
            <?php } ?>


                                            <td  class="no-right-cell should-reset-cell"  id="cell_j{{$judge->id}}_score" style="    font-size: 1.3rem;color: #c8ffca;">0.00</td>  

                                        </tr>
        <?php } ?>

                                    <tr>
                                        <td class="corner-left-cell"></td>  
                                        <?php foreach ($categories as $cat) { ?>
                                            <td class="no-bottom-cell should-reset-cell" id="cell_c{{$cat->id}}_score" style="color: #c8ffca;  font-size: 1.3rem;">-/-</td>  
        <?php } ?>


                                        <td class="corner-right-cell"></td>  

                                    </tr>

                                </table></td>
                            <td>
                                
                                <h3 style="color: #ff6c6c;">Rank <br /><span style="  font-size: 2.5rem;" id="rank-sc">-/-</span></h3>
 <hr /> 
                                <h3>Total Marks</h3>
                                <p class="total-marks should-reset-cell" id="total_marks_p">-</p>

                                <hr /> 
                                <h3>Points</h3>
                                <p class="points should-reset-cell" id="points_p">-</p>
                            </td>

                        </tr>  


                    </table>    




                </div>

                <script>








                    let count_judges = {{App\Models\ClassJudge::where('class_id', $c -> id) -> count() }};
                    let count_categories = {{ count($categories) }};
                    let categories = <?= json_encode($categories) ?>;
                    function refData() {
                    console.log("refresh scoket..");
                    // Creating Our XMLHttpRequest object 
                    var xhr = new XMLHttpRequest();
                    // Making our connection  
                    var url = '{{url("api/v1/current-horse-eval/".$horseReg->id)}}';
                    xhr.open("GET", url, true);
                    // function execute after request is successful 
                    xhr.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                             let data = JSON.parse(this.responseText);
                        
                        if(data.active_class != <?=$comp->active_class ?> || data.active_horse != <?=$c->current_horse ?>){
                            
                           location.reload() ;
                          return;  
                        }
                        
                        
                        
                    let resets = document.getElementsByClassName('should-reset-cell');
                    for (var i = 0; i < resets.length; i++){
                    resets[i].innerHTML = "0.0";
                    }

               
                    console.log(data);
                    for (var i = 0; i < data.evals.length; i++){
                    document.getElementById('cell_' + data.evals[i].judge_id + "_" + data.evals[i].category_id).innerHTML = data.evals[i].score;
        //                 document.getElementById('cell_' +data.evals[i].judge_id+"_" + data.evals[i].category_id ).parentNode.classList.add("filled");
        //                 document.getElementById('cell_j' +data.evals[i].judge_id+"_score").innerHTML = 
        //                         parseFloat(data.evals[i].score) +  parseFloat(document.getElementById('cell_j' +data.evals[i].judge_id+"_score").innerHTML);

                    }


                    for (var i = 0; i < data.cat_total_scores.length; i++){

                    document.getElementById('cell_c' + data.cat_total_scores[i].id + '_score').innerHTML = data.cat_total_scores[i].total;
                    }

                    for (var i = 0; i < data.judge_total_scores.length; i++){

                    document.getElementById('cell_j' + data.judge_total_scores[i].judge_id + '_score').innerHTML = data.judge_total_scores[i].total;
                    }


      document.getElementById('rank-sc').innerHTML = (data.rank);

                    if (data.reg_full.total_marks != "" && data.reg_full.total_marks != null)document.getElementById('total_marks_p').innerHTML = 
                         data.reg_full.total_marks 
                    if (data.reg_full.total_points != "" && data.reg_full.total_points != null)document.getElementById('points_p').innerHTML = 
                          
                    (Math.round( parseFloat(data.reg_full.total_points) * 100) / 100).toFixed(2);
                    if (data.refresh == true)
                            setTimeout(refData, 6000);            else
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
           <div style="text-align: center;">
                    
                    <img  style="width: 100%;max-width: 28rem" src='{{url('assets/3D AHSR BLACK.png')}}' />
                </div>
                
                <?php 
                        
                        
                    }
    
                                        }else {
        
        ?>
                
                <h2>No horses live now</h2>
                <?php
        
    }
    
    
     } else if ($comp->phase == 2){
         
         $c = App\Models\ChampionClass::find($comp->active_class);
         if($c){
        ?>
            
                
                
                
                
      <?php 
      
      
      
      
      ?>          
            
                <table style="margin: 0 auto">
                    
                    <tr>
                        
                        <td>    <table class="swip" style="max-width: 61rem;">
                        <tr>
                            <td colspan="100%">
                                <table style="width: 100%">
                                    <tr>
                                        <td colspan="100%">
                                            <img src="{{url('assets/cover-full.jpeg')}}" style="width: 100%;"/>
                                        </td>
                                    </tr>
                                
                                        
                                             <tr>
                                        <td colspan="100%">
                                         
                                            
                                       
                                            
                                            
                                            
                                                   <div style="">
                    
                        
                        
                        
                        <div style="    text-align: center;
    font-size: 1.3rem;">
                            
                          <b>  {{$c->name_en}} </b>  
                        </div> 
                            <br />

                            <table class="mantessa">
                                <tr><td style="text-align: left;"><span style="color: orange">Horse Number</span></td>
    <?php
      $three = [];
    foreach (\App\Models\CompetitionGroup::where('start_dob', '>=', $c->start_dob)
            ->where('competition_id' ,$comp->id)
            ->where('end_dob', '<=', $c->end_dob)->where('gender', $c->gender)->get() as $class) {

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
                                
                                
                                <?php foreach (\App\Models\User::where('user_type' ,0)
                                        ->where('status' ,1)
                                        ->whereIn('id' ,
                                        \App\Models\ChampionJudge::where('champion_id' ,$c->id)
                                        ->pluck('judge_id')
                                        
                                        )
                                        ->orderBy('order_label')->get() as $judge){ ?>
                                
                                <tr>
                                    <td style="text-align: left;
    font-size: 15px;
    font-weight: normal;
    min-width: 12rem;">{{$judge->name()}}</td>
                                    
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
                                
                                   <tr>
                                    <td colspan="100%" style="text-align: center;border: none">
                                           <div style="height: 1rem"></div>
                                      
                                    </td>
                                </tr>
                                
                                  <tr>
                                    <td>  Number of gold</td>
                                     <?php foreach (  $three as $reg){ ?>
                                    <td id="cell_totalg_{{$reg->id}}" style="color: orange"></td>
                                    <?php } ?>
                                </tr>
                                
                                

                                            </table>
                                       




                     
                          
                                
                                

   </div>
                                            
                                            
                                            
                                            
                                            
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="100%" id="winners-td">
                                           
                                        </td>
                                    </tr>
                                </table>  
                            </td>
                        </tr>
                  </table></td>
                  <td style="padding: 1rem;">
                        
                           
                      <table class="bokka" style="width: 28rem;">
                                <tr>
                                    
                                    <td colspan="100%"> <h2 style="color: orange">{{$c->name_en}}</h2></td>
                                </tr>
                                <tr>
                                    <td><img style="width: 80px" src="{{url('assets/DIACH GOLD.gif')}}" /></td>
                                    <td id="golden-name">-/-</td>
                                    <td id="golden-flag"></td>
                                    
                                </tr>
                         <tr><td colspan="100%"><div style="height: 1px;background-color: #ccc"></div></td></tr>
                                
                                  <tr>
                                    <td><img style="width: 80px" src="{{url('assets/DIACH Silver.gif')}}" /></td>
                                    <td id="silver-name">-/-</td>
                                    <td id="silver-flag"></td>
                                    
                                </tr>
                                <tr><td colspan="100%"><div style="height: 1px;background-color: #ccc"></div></td></tr>
                                  <tr>
                                    <td><img style="width: 80px" src="{{url('assets/DIACH Bronz.gif')}}" /></td>
                                    <td id="bronze-name">-/-</td>
                                    <td id="bronze-flag"></td>
                                    
                                </tr>
                                
                            </table>    
                        
                        </td>
                    </tr>
                    
                    
                </table>   
            
                
             
                
                
    
        
               <script>








                
              
                
                    function refData() {
                   // console.log("refresh scoket..");
                    // Creating Our XMLHttpRequest object 
                    var xhr = new XMLHttpRequest();
                    // Making our connection  
                    var url = '{{url("get-horse-champoint-rating/".$c->id)}}';
                    xhr.open("GET", url, true);
                    // function execute after request is successful 
                    xhr.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                             let data = JSON.parse(this.responseText);
                        
                    
           let points = data.data;
           for(var i=0;i<points.length;i++){
          
             document.getElementById('cell_' + points[i].gold_id+'_'+points[i].judge_id ).innerHTML = '<img class="icon-px" style="background-color: #fff5a0;" src="{{url('assets/Gold.png')}}" />';  
             document.getElementById('cell_' + points[i].silver_id+'_'+points[i].judge_id ).innerHTML = '<img class="icon-px"  style="background-color: #d5d5d5;" src="{{url('assets/Silver.png')}}" />';  
             document.getElementById('cell_' + points[i].bronze_id+'_'+points[i].judge_id ).innerHTML = '<img class="icon-px"  style="background-color: #ffa69f;" src="{{url('assets/bronz.png')}}" />';  
           }
            for(var i=0;i<data.totals.length;i++){
          
             document.getElementById('cell_total_' + data.totals[i].horse_id).innerHTML = data.totals[i].total_score;  
             document.getElementById('cell_totalg_' + data.totals[i].horse_id).innerHTML = data.totals[i].count_gold;  
          
           }
            
            
            
                    if (data.refresh == false)
                    
                        {  console.log("Winner is here ..") 
                        
//                        document.getElementById('winners-td').innerHTML = "Gold : " + data.gold.id + "<br />"
//                                + "Silver : " + data.silver.id + "<br />" + "Bronze : " + data.bronze.id + "<br />";
//                          
    document.getElementById('golden-name').innerHTML =
                                    
            
         "<b>" +  data.gold.reg_number +") "+  data.gold.horse.name_en + 
          "</b><br />" + data.gold.horse.owner_name_en ;
                            document.getElementById('golden-flag').innerHTML =
                                    
            "<img src='https://dashboard.ejudge.ae/public/flags/"
    +data.gold.horse.owner_country_name_en  + ".gif' style='    width: 90px;' />";
    
    //================================
    
        document.getElementById('silver-name').innerHTML =
                                    
            
         "<b>" +  data.silver.reg_number +") "+  data.silver.horse.name_en + 
          "</b><br />" + data.silver.horse.owner_name_en ;
                            document.getElementById('silver-flag').innerHTML =
                                    
            "<img src='https://dashboard.ejudge.ae/public/flags/"
    +data.silver.horse.owner_country_name_en  + ".gif' style='    width: 90px;' />";
    
        //================================
    
        document.getElementById('bronze-name').innerHTML =
                                    
            
         "<b>" +  data.bronze.reg_number +") "+  data.bronze.horse.name_en + 
          "</b><br />" + data.bronze.horse.owner_name_en ;
                            document.getElementById('bronze-flag').innerHTML =
                                    
            "<img src='https://dashboard.ejudge.ae/public/flags/"
    +data.bronze.horse.owner_country_name_en  + ".gif' style='    width: 90px;' />";
    
    
                        }
                        if(data.current_class == {{$c->id}})
                         setTimeout(refData, 7200);
                     else
                         location.reload();
                        
                    }
                    }
                    // Sending our request 
                    xhr.send();
                    }

                    refData();







                </script>
        
        
                       
                
                
                
                
                
         <?php }else{ ?> No classes Are active !   <?php } ?>            
                
                
                
                
                
                
                
    <?php }else{
        // Foals competition
        ?>
         
     
         
                 <table style="margin: 0 auto">
                    
                    <tr>
                        
                        <td>    <table class="swip" style="max-width: 61rem;">
                        <tr>
                            <td colspan="100%">
                                <table style="width: 100%">
                                    <tr>
                                        <td colspan="100%">
                                            <img src="{{url('assets/cover-full.jpeg')}}" style="width: 100%;"/>
                                        </td>
                                    </tr>
                                
                                        
                                             <tr>
                                        <td colspan="100%">
                                         
                                            
                                       
                                            
                                            
                                            
                                                   <div style="">
                    
                        
                        
                        
                        <div style="    text-align: center;
    font-size: 1.3rem;">
                            
                            <b>  <?= App\Models\CompetitionGroup::find($comp->active_class)->name_en  ?> </b>  
                        </div> 
                            <br />

                            <table class="mantessa">
                                <tr><td style="text-align: left;"><span style="color: orange">Judges</span></td>
                                    <td>1st</td>
                                    <td>2nd</td>
                                    <td>3rd</td>
                                    <td>4th</td>
                                    <td>5th</td>
                                      
                                
                                </tr>
                                
                                
                                <?php foreach (\App\Models\User::where('user_type' ,0)->orderBy('order_label')->where('status' ,1)->get() as $judge){ ?>
                                
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
                                       




                     
                          
                                
                                

   </div>
                                            
                                            
                                            
                                            
                                            
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="100%" id="winners-td">
                                            <b>  Score caluclation method </b> <br />
                                           1st : 31 points , 
                                           2nd : 15 points , 
                                           3rd : 7 points , 
                                           4th : 2 points , 
                                           5th : 1 points , 
                                        </td>
                                    </tr>
                                </table>  
                            </td>
                        </tr>
                  </table></td>
                  <td style="padding: 1rem;">
                        
                           
                      <table class="bokka" style="width: 40rem;">
                                <tr>
                                    
                                    <td colspan="100%"> <h2 style="color: orange">Final Results</h2></td>
                                </tr>

                                <tr>
                                    
                                    <td colspan="100%">
                                        
                                               <div  style="margin-top: 1rem;overflow-y: auto;max-height: 41rem;padding-right: 20px;" id="tops-container"></div>
                                        
                                    </td>
                                </tr>
                                
                            </table>    
                        
                        </td>
                    </tr>
                    
                    
                </table>   
            
                
             
                
                
    
        
               <script>








                
              
                
                    function refData() {
                   // console.log("refresh scoket..");
                    // Creating Our XMLHttpRequest object 
                    var xhr = new XMLHttpRequest();
                    // Making our connection  
                    var url = '{{url("get-foal-champoint-rating/".$comp->active_class)}}';
                    xhr.open("GET", url, true);
                    // function execute after request is successful 
                    xhr.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                             let data = JSON.parse(this.responseText);
                        
                    
           let points = data.data;
           for(var i=0;i<points.length;i++){
          console.log("setting "+points[i].first_id+" in " + 'cell_' +points[i].judge_id +"_first")
             document.getElementById('cell_' +points[i].judge_id +"_first").innerHTML = '<span class="td-score-cell">'+ points[i].first_id + '</span>'; 
             document.getElementById('cell_' +points[i].judge_id +"_second").innerHTML ='<span class="td-score-cell">'+ points[i].second_id+ '</span>'; ; 
             document.getElementById('cell_' +points[i].judge_id +"_third").innerHTML = '<span class="td-score-cell">'+points[i].third_id+ '</span>'; ; 
             document.getElementById('cell_' +points[i].judge_id +"_fourth").innerHTML = '<span class="td-score-cell">'+points[i].fourth_id+ '</span>'; ; 
             document.getElementById('cell_' +points[i].judge_id +"_fifth").innerHTML = '<span class="td-score-cell">'+points[i].fifth_id+ '</span>'; ; 


           }
         
            
            
            
                    if (data.refresh == false)
                    
                        {  console.log("Winner is here ..") 
                            
                            
                            
                                      var tops_str = "";
       let lst = [];
       lst.push(data.first);
       lst.push(data.second);
       lst.push(data.third);
       lst.push(data.fourth);
       lst.push(data.fifth);
       
       for(var i=0;i<lst.length;i++){
        let reg = lst[i];
        let horse = lst[i].horse;
        
       tops_str+='<div class="line" ><table class="table table-borderless" style="    border-bottom: solid 1px #fff;"><tr><td style="text-align: center;'
       +' width: 5%; vertical-align: middle; color: #fff;">  Rank.<br /> <span style=" font-weight: bold; font-size: 2rem;"> '
       +(i+1)+'</span> </td><td style="color: #fff;font-weight: bold;text-align: left; width: 70%; vertical-align: middle;padding-left : 5px;"><span style="color : orange"> <span style="color : pink">'+reg.reg_number+') &nbsp;&nbsp; </span> '+horse.name_en+
       '</span> <br /><span style="    font-size: 0.8rem;font-weight: normal;"> <b>Owner : </b>'+horse.owner_name_en+'</span> <br />  \n\
 </td><td style="text-align: right;vertical-align: middle">\n\
 <img style="height: 50px;" src="https://dashboard.ejudge.ae/public/flags/'+horse.owner_country_name_en+'.gif" /></td>'
       +'<td style="text-align: center;vertical-align: middle" >   <p style="    color: #fff; "> '+
       ' <span>Points</span><br /> <span style="color: red;font-weight: bold;">'+reg.foal_marks+'</span></p></td></tr> </table> </div> ';    
       
}   

       
       
       
            
              // console.log(tops_str);
            document.getElementById('tops-container').innerHTML = (tops_str);
                            
                        
//                        document.getElementById('winners-td').innerHTML = "Gold : " + data.gold.id + "<br />"
//                                + "Silver : " + data.silver.id + "<br />" + "Bronze : " + data.bronze.id + "<br />";
//                          
//    document.getElementById('golden-name').innerHTML =
//                                    
//            
//         "<b>" +  data.gold.id +") "+  data.gold.horse.name_en + 
//          "</b><br />" + data.gold.horse.owner_name_en ;
//                            document.getElementById('golden-flag').innerHTML =
//                                    
//            "<img src='https://dashboard.ejudge.ae/public/flags/"
//    +data.gold.horse.owner_country_name_en  + ".gif' style='    width: 90px;' />";
//    
//    //================================
//    
//        document.getElementById('silver-name').innerHTML =
//                                    
//            
//         "<b>" +  data.silver.id +") "+  data.silver.horse.name_en + 
//          "</b><br />" + data.silver.horse.owner_name_en ;
//                            document.getElementById('silver-flag').innerHTML =
//                                    
//            "<img src='https://dashboard.ejudge.ae/public/flags/"
//    +data.silver.horse.owner_country_name_en  + ".gif' style='    width: 90px;' />";
//    
//        //================================
//    
//        document.getElementById('bronze-name').innerHTML =
//                                    
//            
//         "<b>" +  data.bronze.id +") "+  data.bronze.horse.name_en + 
//          "</b><br />" + data.bronze.horse.owner_name_en ;
//                            document.getElementById('bronze-flag').innerHTML =
//                                    
//            "<img src='https://dashboard.ejudge.ae/public/flags/"
//    +data.bronze.horse.owner_country_name_en  + ".gif' style='    width: 90px;' />";
//    
    
                        }
                        
                       // if( data.current_class == {{$comp->active_class}})
                        if(data.refresh)
                         setTimeout(refData, 7200);
//                     else
//                         location.reload();
                        
                    }
                    }
                    // Sending our request 
                    xhr.send();
                    }

                    refData();







                </script>
        
         
         
         
         
         
         
         <?php
        
        
        
    }
}
?>  

    </body>
</html>
