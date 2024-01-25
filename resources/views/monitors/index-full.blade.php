
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

                    <table style="margin: 0 auto">
                        <tr>
                            <td>  <table class="swip">
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
                                            <p  class="class-name" style="font-size: 1rem;">{{ $c->name_en}}  {{App\Models\HorseRegistration::select(['sectionLabel'])->where('group_id', $comp->active_class)->distinct()->count('sectionLabel') >  1  ? " Section " .$horseReg->sectionLabel : ""}}</p>
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


                    </table>  </td>
                            <td>
                          
                                
                                <div  style="margin-top: 1rem;overflow-y: auto;max-height: 41rem;padding-right: 20px;" id="tops-container"></div>
                                
                                
                            </td>
                            
                        </tr>
                        
                        
                    </table>

                    




                </div>

                <script>








                    let count_judges = {{App\Models\ClassJudge::where('class_id', $c -> id) -> count() }};
                    let count_categories = {{ count($categories) }};
                    let categories = <?= json_encode($categories) ?>;
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
                        
                        if(data.active_class != <?=$comp->active_class ?> || data.active_horse != <?=$c->current_horse ?>){
                            
                           location.reload() ;
                          return;  
                        }
                        
                        
                        
                    let resets = document.getElementsByClassName('should-reset-cell');
                    for (var i = 0; i < resets.length; i++){
                    resets[i].innerHTML = "0.0";
                    }

               
                   // console.log(data);
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
            
            
            let all = data.tops.data;
          
            var tops_str = "";
            for(var i=0;i<all.length;i++){
                let reg = all[i];
                
       tops_str+='<div class="line" ><table class="table table-borderless" style="    border-bottom: solid 1px #fff;"><tr><td style="text-align: center;'
       +' width: 5%; vertical-align: middle; color: #fff;">  Rank.<br /> <span style=" font-weight: bold; font-size: 2rem;"> '+reg.ranking 
       + '</span> </td><td style="color: #fff;font-weight: bold;text-align: left; width: 70%; vertical-align: middle;padding-left : 5px;"><span style="color : orange"> <span style="color : pink">'+reg.reg_number+') &nbsp;&nbsp; </span> '+reg.name+
       '</span> <br /><span style="    font-size: 0.8rem;font-weight: normal;"> <b>Owner : </b>'+reg.owner+'</span> <br />  \n\
<span style="    font-size: 0.8rem;font-weight: normal;"> <b>Type: </b> <span class="snb09">'+reg.total_c1+'</span></span> &nbsp; &nbsp; &nbsp; \n\
<span style="    font-size: 0.8rem;font-weight: normal;"> <b>Movement: </b> <span class="snb09">'+reg.total_c2+'</span></span> </td><td style="text-align: right;vertical-align: middle">\n\
 <img style="height: 50px;" src="https://dashboard.ejudge.ae/public/flags/'+reg.country+'.gif" /></td>'
       +'<td style="text-align: center;vertical-align: middle" >   <p style="    color: #fff; "> '+
       ' <span>Points</span><br /> <span style="color: red;font-weight: bold;">'+reg.total_points+'</span></p></td></tr> </table> </div> ';         
               } 
              // console.log(tops_str);
            document.getElementById('tops-container').innerHTML = (tops_str);
            
            
            
            
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
                
                <div>
                    
                    <img style="width: 100%;max-width: 1000px;" src='{{url('assets/3D AHSR BLACK.png')}}' />
                </div>
                <?php
        
    }
    
    
     }else {
        ?>
                <h1>Url changed , please <a href="{{url('get-monitor/323213')}}">Click here ..</a></h1>
    <?php }
}
?>  

    </body>
</html>
