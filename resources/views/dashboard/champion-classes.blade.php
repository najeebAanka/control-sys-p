        <!DOCTYPE html>
        <html lang="en">

        <?php
        $project = App\Models\Competition::find(Route::input('id'));
        $d = $project;
        ?>

        @include('dashboard.shared.css')
        <style>
        .sm-img {
        max-width: 100px;
        }

        img.blah {
        width: 100%;
        max-width: 300px;
        margin-top: 15px;
        margin-left: auto;
        margin-right: auto;
        background-color: #e1e1e1;
        min-height: 30px;
        }

        img.blah_ {
        width: 100%;
        max-width: 300px;
        margin-top: 15px;
        margin-left: auto;
        margin-right: auto;
        background-color: #e1e1e1;
        min-height: 30px;
        }


        .selected-row {
        background-color: #dcffdc;
        }
        .horse-status--1 {
        background-color: #ffcccc;
        }
        .horse-status--2{
        background-color: #ff6666;
        }
        </style>

        {{-- edit group modal start --}}
        <div class="modal fade" id="editGroupModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 id="edit-group-title" class="modal-title">Edit Class</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="#" method="POST" id="edit_group_form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="group_id" value="-1">
            <div class="modal-body p-4 bg-light">

                <div class="my-2">
                    <label for="name_en">Name (En)</label>
                    <input id="group_name_en" type="text" name="name_en" class="form-control"
                           placeholder="English Name" required>
                </div>
                <div class="my-2">
                    <label for="name_ar">Name (Ar)</label>
                    <input id="group_name_ar" type="text" name="name_ar" class="form-control"
                           placeholder="Arabic Name" required>
                </div>
                {{-- <div class="my-2">
                <label for="competition_id">Competition</label>
                <select id="group_competition_id" name="competition_id" class="form-control" required>
                <?php
                $data = \App\Models\Competition::get();
                foreach ($data as $do) {
                    ?>
                    <option value="<?php echo $do->id; ?>"><?php echo $do->name_en; ?> </option>
                    <?php } ?>
                </select>
            </div> --}}
                    {{-- <div class="my-2">
                <label for="current_horse">Current Horse</label>
                <select id="group_current_horse" name="current_horse" class="form-control" required>
                    <option value="-1" style="display:none">Select Horse</option>
                <?php
                $p = '<script>document.getElementById("group_id").value;</script>';

                $data = \App\Models\HorseRegistration::where('group_id', '=', 22)->get();
                foreach ($data as $do) {
                    ?>
                        <option value="<?php echo $do->horse_id; ?>"><?php $dat = \App\Models\Horse::where('id', '=', $do->horse_id)->first();
                        echo $dat->name_en;
                    ?> </option>
                    <?php } ?>
                </select>
            </div> --}}
                <div class="my-2">
                    <label for="status">Status</label>
                    <select id="group_status" name="status" class="form-control" required>
                <option value="1" selected>Published</option>
                <option value="0">Unpublished</option>
            </select>
        </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" id="edit_group_btn" class="btn btn-success">Update Class</button>
        <div id="edit_spinner" class="spinner-border text-primary" role="status"
         style="display: none;">
        <span class="visually-hidden"></span>
        </div>
        </div>
        </form>
        </div>
        </div>
        </div>
        {{-- edit group modal end --}}




        <body>

        <!-- ======= Header ======= -->
        @include('dashboard.shared.top-nav')
        <!-- End Header -->

        <!-- ======= Sidebar ======= -->
        @include('dashboard.shared.side-nav')
        <!-- End Sidebar-->

        <main id="main" class="main">

        {{-- <div class="pagetitle">
        <h1>Competitions</h1>
        <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard/home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('dashboard/competitions') }}">Competitions
                    Management</a></li>
            <li class="breadcrumb-item"><a href="{{ url('dashboard/competitions') }}">Champion</a></li>
            <li id="comp_title" class="breadcrumb-item active"></a></li>
        </ol>
        </nav>
        </div><!-- End Page Title --> --}}


        <section class="section dashboard">



                        <div class="row">


                            <div class=" col-4">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card shadow">
                                            <div
                                                class="card-header d-flex justify-content-between align-items-center">
                                                <h3>Champion Classes</h3>

                                            </div>
                                            <div class="card-body" id="">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>

                                                                <th>Name</th>
                                                                <th style="text-align: center;">Actions</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody id="show_all_groups">

        <?php foreach (App\Models\ChampionClass::where('status', 1)->get() as $c) { ?>

                                                            <tr class="c-class c-class-{{$c->id}} <?=($d->active_phase==2 && $d->active_class == $c->id  ? "selected-row" : "" )?>">

                                                                    <td>{{$c->name_en}}</td>
                                                                    <td style="text-align: center;"><button 
                                                                            class="btn btn-warning load-champion-class-horses"
                                                                            data-id='{{$c->id}}'  data-name='{{$c->name_en}}'>Open</button></td>

                                                                </tr>


        <?php } ?>

        <!--                                                                    <tr>
        <td colspan="100%"
        style="text-align: center;">
        <h4 class="mt-3">Loading..</h4>

        <div class="lds-spinner">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        </div>
        </td>
        </tr>-->

                                                        </tbody>
                                                    </table>
                                                </div>



                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div id="class_horses_div" class=" col-8" >
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card shadow">
                                            <div
                                                class="card-header d-flex justify-content-between align-items-center">
                                                <h3 id="ccn-name">Horses</h3>
                                                <div>

                                                    <button id="set_active_class" class="btn btn-primary" hidden>
                                                        Set active
                                                    </button>
                                                    
                                                    <button id="show_results_class" class="btn btn-success" hidden>
                                                       Results
                                                    </button>
                                                    
                                                    
                                                    <div id="cck-9087"></div>
                                                </div>
                                            </div>
                                            <div class="card-body" id="">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Name</th>
                                                                <th>Status</th>
                                                                <th>Section</th>
                                                                <th style="text-align: center;">Score</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody id="show_all_horses">

                                                            <tr>
                                                                <td colspan="100%"
                                                                    style="text-align: center;">
                                                                    <p class="mt-3">Choose a Class to show
                                                                        horses</p>

                                                                    {{-- <div class="lds-spinner">
                                                                        <div></div>
                                                                        <div></div>
                                                                    <div></div>
                                                                        <div></div>
                                                                            <div></div>
                                                                                <div></div>
                                                                                    <div></div>
                                                                                        <div></div>
                                                                                            <div></div>
                                                                    </div> --}}
                                                                </td>
                                                            </tr>

                   </tbody>
                                                    </table>
                                                </div>


                  {{-- <div id="pages-container"></div> --}}

                   </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

        <!--                       <div class="card-body" id="">






              </div>-->
        </section>

        </main><!-- End #main -->

        <!-- ======= Footer ======= -->
        @include('dashboard.shared.footer')
        <!-- End Footer -->

        @include('dashboard.shared.js')


                  <script>
                                                                        var current_page = 1;
                                                                        $(function() {






                                                                        $('.load-champion-class-horses').click(function(){


$('#ccn-name').html($(this).attr('data-name'));

                                                                        fetchClassHorses($(this).attr('data-id'));
                                                                        })









                                                                                // fetch all competition ajax request
                                                                                var active_class = {{$d->active_class}};
                                                                        var active_horse = - 1;
                                                                        var active_section = 'A';
                                                                        function fetchClassHorses(id) {


                                                                        let is_active = active_class == id ;
                                                                        $("#set_active_class").attr("data-id", id);
                                                                           $("#show_results_class").attr("data-id", id);
                                                                //                $('#cck-9087').html("<a class='btn btn-warning btn-sm mt-1' href='{{url('dashboard/class-ranking')}}/"+id+"/"+section+"' target='blank'>Standings</a>")

                                                                        $("#set_active_class").html(is_active ? "Current" : "Set Current")




                                                                                if (is_active) {
                                                                        $("#set_active_class").prop('disabled', true);
                                                                        } else {
                                                                        $("#set_active_class").prop('disabled', false);
                                                                        }


                                                                        $("#class_horses_div").attr("hidden", false);
                                                                        $("#set_active_class").attr("hidden", false);
                                                                        $("#show_results_class").attr("hidden", false);
                                                                        
                                                                        
                                                                        $("#show_all_horses").html(
                                                                                "<tr><td colspan='100%' style='text-align: center;'><div class='lds-spinner'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></tr></td>"
                                                                                );
                                                                        console.log('cliked : ' + id);
                                                                        $.ajax({
                                                                        url: server + "champion-class-horses/fetchForClass/" + id + "/" + {{$d->id}},
                                                                                dataType: 'json',
                                                                                method: 'get',
                                                                                success: function(r) {

                                                                                $("#set_active_class").show();
                                                                                console.log(r);
                                                                                let response = r.data;
                                                                //                        console.log(response.length)
                                                                                var str = "";
                                                                                if (response.length > 0) {

                                                                                for (var i = 0; i < response
                                                                                        .length; i++) {

                                str +=
                                                                                                    "<tr class='horse-row horse-status-"+response[i].present_in_championship+"'><td>" + response[i].reg_number +
                                                                                                    "</td><td>" + response[i].horse
                                                                                                    .name_en ;


//                                                                                                if((response[i].status == 1)){
//
//
//                                                                                                    str += "   <button data-id='" +
//                                                                                                    response[i].id +
//                                                                                                    "'  style='float : right' class='btn btn-danger btn-sm mt-1 set-absent-horse-btn'>Set Absent</button>";
//                                                                                        }else{
//                                                                                                str += "   <button data-id='" +
//                                                                                                    response[i].id +
//                                                                                                    "'  style='float : right' class='btn btn-info btn-sm mt-1 set-present-horse-btn'>Set Present</button>";
//                                                                                        }

                                                                                              str+=      "</td><td>" ;


                                                                   if(response[i].present_in_championship == 1) str += "Present" ;
                                                                   if(response[i].present_in_championship == -1) str += "Absent" ;
                                                                   if(response[i].present_in_championship == -2) str += "Lame" ;
                                                                                                        str +=
                                                                                                    "</td><td>" + response[i]
                                                                                                    .class +
                                                                                                    "</td><td>" + response[i]
                                                                                                    .total_points +
                                                                                                    "</td></tr>";
                                                                                            }

                                                                                            $("#show_all_horses").html(str);


                                                                                } else {
                                                                                str =
                                                                                        "<p style='margin-top:20px;margin-left:30px;'>No horses has qualified to this class</p>";
                                                                                $("#show_all_horses").html(str);
                                                                                }

                                                                //                            for (var i = 0; i < response
                                                                //                                .length; i++) {

                                                                //
                                                                //
                                                                //                            $('.current-horse-btn').off('click');
                                                                //
                                                                //
                                                                //                            let clicked_class = id;
                                                                //                            $('.current-horse-btn').click(function() {
                                                                //
                                                                //                                let id = $(this).attr('data-id');
                                                                //                                console.log('cliked : ' + id);
                                                                //                                $('.current-horse-btn').prop('disabled', true);
                                                                //                                let class_id = $("#set_active_class").attr('data-id');
                                                                //
                                                                //                                let comp_id = {{ $d->id }};
                                                                //
                                                                //                                $.ajax({
                                                                //                                    url: server + "competitions/update-current-horse/" +
                                                                //                                        class_id + "/" + id + "/" + comp_id,
                                                                //                                    dataType: 'json',
                                                                //                                    method: 'get',
                                                                //                                    success: function(r) {
                                                                //
                                                                //                                        // fetchAllGroups();
                                                                //                                        fetchClassHorses(clicked_class ,section);
                                                                //
                                                                //                                    }
                                                                //                                });
                                                                //
                                                                //  $.ajax({
                                                                //                                    url: server + "competitions/notifyJudges/" +
                                                                //                                        class_id ,
                                                                //                                    dataType: 'json',
                                                                //                                    method: 'get',
                                                                //                                    success: function(r) {
                                                                //
                                                                //
                                                                //                                    }
                                                                //                                });
                                                                //
                                                                //
                                                                //
                                                                //                            });
                                                                //
                                                                //
                                                                ////----------------------------------------------
                                                                //
                                                                //   $('.set-absent-horse-btn').off('click');
                                                                //
                                                                //
                                                                //                     
                                                                //                          $('.set-absent-horse-btn').click(function() {
                                                                //
                                                                //                                let id = $(this).attr('data-id');
                                                                //                                console.log('cliked : ' + id);
                                                                //                                $(this).prop('disabled', true);
                                                                //
                                                                //                                $.ajax({
                                                                //                                    url: server + "horses/set-absent/" +
                                                                //                                        id ,
                                                                //                                    dataType: 'json',
                                                                //                                    method: 'get',
                                                                //                                    success: function(r) {
                                                                //
                                                                //                                        fetchClassHorses(clicked_class ,section);
                                                                //
                                                                //                                    }
                                                                //                                });
                                                                //
                                                                ////  $.ajax({
                                                                ////                                    url: server + "competitions/notifyJudges/" +
                                                                ////                                        class_id ,
                                                                ////                                    dataType: 'json',
                                                                ////                                    method: 'get',
                                                                ////                                    success: function(r) {
                                                                ////
                                                                ////
                                                                ////                                    }
                                                                ////                                });
                                                                //
                                                                //
                                                                //
                                                                //                            });
                                                                //
                                                                //   $('.set-present-horse-btn').off('click');
                                                                //
                                                                //
                                                                //                     
                                                                //                          $('.set-present-horse-btn').click(function() {
                                                                //
                                                                //                                let id = $(this).attr('data-id');
                                                                //                                console.log('cliked : ' + id);
                                                                //                                $(this).prop('disabled', true);
                                                                //
                                                                //                                $.ajax({
                                                                //                                    url: server + "horses/set-present/" +
                                                                //                                        id ,
                                                                //                                    dataType: 'json',
                                                                //                                    method: 'get',
                                                                //                                    success: function(r) {
                                                                //
                                                                //                                        fetchClassHorses(clicked_class ,section);
                                                                //
                                                                //                                    }
                                                                //                                });
                                                                //
                                                                ////  $.ajax({
                                                                ////                                    url: server + "competitions/notifyJudges/" +
                                                                ////                                        class_id ,
                                                                ////                                    dataType: 'json',
                                                                ////                                    method: 'get',
                                                                ////                                    success: function(r) {
                                                                ////
                                                                ////
                                                                ////                                    }
                                                                ////                                });
                                                                //
                                                                //
                                                                //
                                                                //                            });
                                                                //




                                                                                }
                                                                        });
                                                                        }

$('#show_results_class').click(function(){

       let id = $(this).attr('data-id');
       
      window.open("{{url('dashboard/champion-class-results')}}/" + id + "/" + {{$d->id}}, '_blank');


});
                $('#set_active_class').click(function() {

                let id = $(this).attr('data-id');
            
                $('#set_active_class').html('Loading..')
                        $('#set_active_class').prop('disabled', true)

                $.ajax({
                url: server + "competitions/update-current-champion-class/" +
                        <?=$d->id?> + "/" + id ,
                        dataType: 'json',
                        method: 'get',
                        success: function(r) {
                        active_class = id;
                       $('.c-class').removeClass('selected-row');
                       $('.c-class-' + id).addClass('selected-row');
                       $('#set_active_class').html('Current Class');
                       
//                         $.ajax({
//                                    url: server + "competitions/notifyJudges/" +
//                                        class_id ,
//                                    dataType: 'json',
//                                    method: 'get',
//                                    success: function(r) {
//
//
//                                    }
//                                });
                       
                       
//                        fetchAllGroups();
//                        fetchClassHorses(id, section);
                        }
                });
                });
        //                                                                                function fetchAllCompetitionsPagesOnly() {
        //                                                                                $.ajax({
        //                                                                                url: server + "competitions/fetchall?page=" + current_page,
        //                                                                                        dataType: 'json',
        //                                                                                        method: 'get',
        //                                                                                        success: function(r) {
        //                                                                                        let response = r.data;
        //                                                                                        console.log(response.length)
        //
        //
        //                                                                                                //add click listner
        //
        //                                                                                                // handle pages :
        //
        //                                                                                                var pgstr = "";
        //                                                                                        for (var i = 1; i <= Math.ceil(r.total / 25); i++) {
        //                                                                                        pgstr += "<button class='page-cliker " + (i == 1 ? "current-page" :
        //                                                                                                "") +
        //                                                                                                " ' id='cliker-" + i + "' data-link='" + i + "' >" + i +
        //                                                                                                "</button>  ";
        //                                                                                        }
        //                                                                                        $('#pages-container').html(pgstr);
        //                                                                                        $('.page-cliker').click(function() {
        //                                                                                        current_page = $(this).attr('data-link');
        //                                                                                        console.log("going to page : " + $(this).attr('data-link'))
        //
        //
        //                                                                                                fetchAllCompetitions();
        //                                                                                        })
        //                                                                                                fetchAllCompetitions();
        //                                                                                        },
        //                                                                                        error: function(response) {
        //                                                                                        console.log("err " + response)
        //
        //                                                                                        }
        //                                                                                });
        //                                                                                }


                                                                                //   fetchAllCompetitionsPagesOnly();
                                                                                //  fetchAllGroups();



                                                                        });
                                                                        </script>





                                                                        </body>

                                                                        </html>
