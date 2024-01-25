<!DOCTYPE html>
<html lang="en">
<? ?>
@include('dashboard.shared.css')

<style>
    .status_cell_0 {
        color: red;
        background: antiquewhite !important;
    }

    .status_cell_1 {
        color: green;
        background: aliceblue !important;
    }
    .td_check_box{
        transform: scale(1.6);
    padding-right: 25px;
    padding-left: 10px;
    padding-bottom: 5PX;
    padding-top: 5px;
    border: 1px solid #c8c5c5;
    }
    .td_chexxx{
    padding-right: 25px;
    padding-left: 10px;
    padding-bottom: 5PX;
    padding-top: 5px;
    font-size: 18px!important;
    border: 1px solid #c8c5c5;
    }
    .th_cheqer{
        border: 1px solid #c8c5c5;
        padding-left: 8px;
        padding-bottom: 10px;
        padding-top: 10px;
    }
</style>

{{-- add new champion modal start --}}
<div class="modal fade" id="addChampionModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Champion Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="add_champion_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-light">

                    <div class="my-2">
                        <label for="name_en">Name (En)</label>
                        <input type="text" name="name_en" class="form-control" placeholder="English Name" required>
                    </div>
                    <div class="my-2">
                        <label for="name_ar">Name (Ar)</label>
                        <input type="text" name="name_ar" class="form-control" placeholder="Arabic Name" required>
                    </div>
                    {{-- <div class="my-2">
                        <label for="competition_id">Competition</label>
                        <select name="competition_id" class="form-control" required>
                            <option value="" disabled selected style="display:none">Select Competition</option>
                            <?php
                            $data = \App\Models\Competition::get();
                            foreach ($data as $do) { ?>
                            <option value="<?php echo $do->id; ?>"><?php echo $do->name_en; ?> </option>
                            <?php } ?>
                        </select>
                    </div> --}}
                    <div class="my-2">
                        <label for="start_dob">Start DOB</label>
                        <input type="date" name="start_dob" class="form-control" placeholder="Start DOB" required>
                    </div>
                    <div class="my-2">
                        <label for="end_dob">End DOB</label>
                        <input type="date" name="end_dob" class="form-control" placeholder="End DOB" required>
                    </div>

                    <div class="my-2">
                        <label for="age_info">Age Info</label>
                        <textarea name="age_info" class="form-control" placeholder="Age Info" rows="5" required></textarea>
                    </div>

                    <div class="my-2">
                        <label for="gender">Gender</label>
                        <select name="gender" class="form-control" required>
                            <option value="male" selected>Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <div class="my-2">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="1" selected>Active</option>
                            <option value="0">Not active</option>
                        </select>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add_champion_btn" class="btn btn-primary">Add Champion Class</button>
                    <div id="add_spinner" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- add new champion class modal end --}}


{{-- edit champion class modal start --}}
<div class="modal fade" id="editChampionModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="edit-champion-title" class="modal-title">Edit Champion Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="edit_champion_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="champion_id" value="-1">
                <div class="modal-body p-4 bg-light">

                    <div class="my-2">
                        <label for="name_en">Name (En)</label>
                        <input id="champion_name_en" type="text" name="name_en" class="form-control"
                            placeholder="English Name" required>
                    </div>
                    <div class="my-2">
                        <label for="name_ar">Name (Ar)</label>
                        <input id="champion_name_ar" type="text" name="name_ar" class="form-control"
                            placeholder="Arabic Name" required>
                    </div>
                    {{-- <div class="my-2">
                        <label for="competition_id">Competition</label>
                        <select id="champion_competition_id" name="competition_id" class="form-control" required>
                            <?php
                            $data = \App\Models\Competition::get();
                            foreach ($data as $do) { ?>
                            <option value="<?php echo $do->id; ?>"><?php echo $do->name_en; ?> </option>
                            <?php } ?>
                        </select>
                    </div> --}}
                    <div class="my-2">
                        <label for="start_dob">Start DOB</label>
                        <input id="champion_start_dob" type="date" name="start_dob" class="form-control"
                            placeholder="Start DOB" required>
                    </div>
                    <div class="my-2">
                        <label for="end_dob">End DOB</label>
                        <input id="champion_end_dob" type="date" name="end_dob" class="form-control"
                            placeholder="End DOB" required>
                    </div>

                    <div class="my-2">
                        <label for="age_info">Age Info</label>
                        <textarea id="champion_age_info" name="age_info" class="form-control" placeholder="Age Info" rows="5"
                            required></textarea>
                    </div>

                    <div class="my-2">
                        <label for="gender">Gender</label>
                        <select id="champion_gender" name="gender" class="form-control" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <div class="my-2">
                        <label for="status">Status</label>
                        <select id="champion_status" name="status" class="form-control" required>
                            <option value="1">Active</option>
                            <option value="0">Not active</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit_champion_btn" class="btn btn-success">Update Champion
                        Class</button>
                    <div id="edit_spinner" class="spinner-border text-primary" role="status"
                        style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- edit champion class modal end --}}


{{-- edit judge modal start --}}
<div class="modal fade" id="editChampionJudgesModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="edit-judge-champion-title" class="modal-title">Edit Judges</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="edit_judge_champion_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="judge_champion_id" value="-1">
                <h3 style="font-size: 20px; padding: 1rem;" id="yyuu08"> </h3>
                <div class="modal-body p-4 bg-light">

                    

                    <div class="judges-list">
<table>
    <tr>
        <th class="th_cheqer">Select</th>
        <th class="th_cheqer">Judge Name</th>
    </tr>
                        <?php foreach (\App\Models\User::orderBy('order_label' ,'asc')->where('user_type' ,0)->where('status' ,1)->get() as $c){ ?>
                            <tr>
                                <td class="td_check_box">
                        <input style="margin-left: 15px;" type="checkbox" name="judges[]" class="judge-box" id="box{{ $c->id }}"
                            value="{{ $c->id }}" /></td><td class="td_chexxx"> {{ $c->order_label }} - <?php if ($c->gender == 'male') {
                                echo 'Mr. ';
                            } else {
                                echo 'Mrs. ';
                            } ?>
                        {{ $c->name }}
                        </td>
                        </tr>
                        {{-- <hr /> --}}
                        <?php } ?>
</table>

                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit_judges_champion_btn" class="btn btn-success">Update judges
                        status</button>
                    <div id="edit_judge_spinner" class="spinner-border text-primary" role="status"
                        style="display: none;"></div>

                </div>
            </form>
        </div>
    </div>
</div>
{{-- edit judge modal end --}}





<body>

    <!-- ======= Header ======= -->
    @include('dashboard.shared.top-nav')
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('dashboard.shared.side-nav')
    <!-- End Sidebar-->

    <main id="main" class="main">

        <!--<div class="pagetitle">-->
        <!--    <h1>Champions Classes</h1>-->
        <!--    <nav>-->
        <!--        <ol class="breadcrumb">-->
        <!--            <li class="breadcrumb-item"><a href="{{ url('dashboard/home') }}">Home</a></li>-->
        <!--            <li class="breadcrumb-item">Champions Classes Management</li>-->
        <!--            <li class="breadcrumb-item active">Champions Classes</li>-->
        <!--        </ol>-->
        <!--    </nav>-->
        <!--</div>--><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">

                <div class="container">
                    <!--<div class="row my-5">-->
                    <div class="row ">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3>Manage Champions Classes</h3>
                                    <button class="btn btn-light" data-bs-toggle="modal"
                                        data-bs-target="#addChampionModal">
                                        <i class="bi-plus-circle me-2"></i>
                                        Add New Champion Class
                                    </button>
                                </div>
                                <div class="card-body" id="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Start DOB</th>
                                                    <th>End DOB</th>
                                                    <th>Status</th>
                                                    <th style='text-align : center'>Actions</th>

                                                </tr>
                                            </thead>
                                            <tbody id="show_all_champions">

                                                <tr>
                                                    <td colspan="100%" style="text-align: center;">
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
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="pages-container"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    @include('dashboard.shared.footer')
    <!-- End Footer -->

    @include('dashboard.shared.js')



    <script>
        var current_page = 1;

        $(function() {

            // add new champion class ajax request
            $("#add_champion_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#add_spinner").css("display", "block");
                $("#add_champion_btn").text('Adding...');
                $("#add_champion_btn").attr('disabled', true);
                $.ajax({
                    url: server + "champions-classes/store",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllChampions();
                        }
                        $("#add_champion_btn").text('Add Champion Class');
                        $("#add_champion_btn").attr('disabled', false);
                        $("#add_champion_form")[0].reset();
                        $("#addChampionModal .btn-close").click();
                        $("#add_spinner").css("display", "none");
                        $('#add-toast').toast('show');
                    }
                });
            });

            $("#edit_champion_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#edit_spinner").css("display", "block");
                $("#edit_champion_btn").text('Updating...');
                $("#edit_champion_btn").attr('disabled', true);
                $.ajax({
                    url: server + "champions-classes/update",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllChampions();
                        }
                        $("#edit_champion_btn").text('Update Champion Class');
                        $("#edit_champion_btn").attr('disabled', false);
                        $("#edit_champion_form")[0].reset();
                        $("#editChampionModal").modal('hide');
                        $("#edit_spinner").css("display", "none");
                        $('#edit-toast').toast('show');
                    },

                });
            });


            $("#edit_judge_champion_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#edit_judge_spinner").css("display", "block");
                $("#edit_judges_champion_btn").text('Updating...');
                $("#edit_judges_champion_btn").attr('disabled', true);
                $.ajax({
                    url: server + "champion/judges/update",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        if (response.status == 200) {

                            $("#edit_judges_champion_btn").text('Update judges status');
                            $("#edit_judges_champion_btn").attr('disabled', false);
                            $("#editChampionJudgesModal").modal('hide');
                            $("#edit_judge_spinner").css("display", "none");
                            $('#edit-toast').toast('show');
                        }
                        
                    },
                    
                });
            });


            // fetch all champion classes ajax request

            function fetchAllChampions() {
                $.ajax({
                    url: server + "champions-classes/fetchall?page=" + current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;
                        console.log(response.length)
                        var res_status = "";
                        var str = "";
                        for (var i = 0; i < response.length; i++) {

                            if (response[i].status == 1) res_status = "Active";
                            else if (response[i].status == 0) res_status = "Not active";

                            str += "<tr><td>" + response[i].id + "</td><td>" + response[i].name_en +
                                "</td><td>" + response[i].start_dob +
                                "</td><td>" + response[i].end_dob +
                                "</td><td class='status_cell_" + response[i].status + "'>" +
                                res_status +
                                "</td><td style='text-align : center'></button><button data-id='" +
                                response[i]
                                .id +
                                "'  data-name_en='" + response[i].name_en +
                                "'  class='btn btn-info btn-sm mt-1  ml-1 mr-1 edit-champion-judges-btn'>Judges <i class='bi-pencil-square'></i></button>\n\<button  data-id='" +
                                response[i].id +
                                "'  data-name_en='" + response[i].name_en +
                                "'  data-name_ar='" + response[i].name_ar +
                                // "'  data-competition_id='" + response[i].competition_id +
                                "'  data-status='" + response[i].status +
                                "'  data-start_dob='" + response[i].start_dob +
                                "'  data-end_dob='" + response[i].end_dob +
                                "'  data-gender='" + response[i].gender +
                                "'  data-age_info='" + response[i].age_info +
                                "'  class='btn btn-success btn-sm mt-1 ml-1 mr-1 edit-champion-btn'>Edit <i class=\"bi-pencil-square \"></i><button data-id='" +
                                response[i]
                                .id +
                                "'  data-name_en='" + response[i].name_en +
                                "' class='btn btn-sm btn-danger mt-1  ml-1 mr-1 del-champion-btn' style='margin-left: 4px;'>Remove <i class=\"bi-trash-fill \"></i></button></td></tr>";
                        }

                        $("#show_all_champions").html(str);

                        //add click listner


                        $('.edit-champion-btn').click(function() {


                            let id = $(this).attr('data-id');



                            console.log('cliked : ' + id);
                            $('#champion_id').val(id);
                            $('#champion_name_en').val($(this).attr('data-name_en'));
                            $('#champion_name_ar').val($(this).attr('data-name_ar'));
                            $('#champion_start_dob').val($(this).attr('data-start_dob'));
                            $('#champion_end_dob').val($(this).attr('data-end_dob'));
                            $('#champion_gender').val($(this).attr('data-gender'));
                            $('#champion_age_info').val($(this).attr('data-age_info'));
                            // $('#champion_competition_id').val($(this).attr(
                            //     'data-competition_id'));
                            $('#champion_status').val($(this).attr('data-status'));
                            $('#edit-champion-title').html("Edit " + $(this).attr(
                                'data-name_en'));
                            $('#editChampionModal').modal('show');

                        });


                        $('.edit-champion-judges-btn').click(function() {

                            let id = $(this).attr('data-id');
                            let name = $(this).attr('data-name_en');

                            $('.judge-box').prop('checked', false);
                            $('.judge-box').attr('disabled', true);

                            console.log('cliked : ' + id);

                            $('.judge-box').attr('disabled', false);
                            $('#judge_champion_id').val(id);
                            $('#yyuu08').html(name);
                            c4();
                            $('#editChampionJudgesModal').modal('show');




                        });



                        $('.del-champion-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let csrf = '{{ csrf_token() }}';


                            console.log('cliked : ' + id);

                            if (confirm("Are you sure to remove " + $(this).attr(
                                        'data-name_en') +
                                    "?")) {

                                // call ajax to delete this champion class
                                $(this).html("Deleting...");
                                $(this).prop("disabled", true);

                                $.ajax({
                                    url: server + "champions-classes/delete",
                                    method: 'delete',
                                    data: {
                                        id: id,
                                        _token: csrf
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        fetchAllChampions();
                                        $('#delete-toast').toast('show');
                                    }
                                });

                            }



                        });

                        $('.page-cliker').removeClass('current-page');
                        $('#cliker-' + r.current_page).addClass('current-page');


                    },
                    error: function(response) {
                        console.log("err " + response)

                    }
                });
            }

            function fetchAllChampionsPagesOnly() {
                $.ajax({
                    url: server + "champions-classes/fetchall?page=" + current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;
                        console.log(response.length)


                        //add click listner

                        // handle pages :

                        var pgstr = "";
                        for (var i = 1; i <= Math.ceil(r.total / 25); i++) {
                            pgstr += "<button class='page-cliker " + (i == 1 ? "current-page" : "") +
                                " ' id='cliker-" + i + "' data-link='" + i + "' >" + i + "</button>  ";
                        }
                        $('#pages-container').html(pgstr);



                        $('.page-cliker').click(function() {
                            current_page = $(this).attr('data-link');
                            console.log("going to page : " + $(this).attr('data-link'))


                            fetchAllChampions();

                        })
                        fetchAllChampions();

                    },
                    error: function(response) {
                        console.log("err " + response)

                    }
                });
            }

            console.log("calling .. ")
            fetchAllChampionsPagesOnly();

            $('#check-judges-list-btn').click(function() {

                c4();

            });

        });


        function c4() {
            let id = $('#judge_champion_id').val();

            $('.judge-box').attr('disabled', true);
            console.log(id);
            $('#judges-list').hide();


            $.ajax({
                url: server + "champion/judges/" + id,
                dataType: 'json',
                method: 'get',
                success: function(r) {
                    let response = r;
                    console.log(response)
                    $('#judges-list').show();

                    $('.judge-box').attr('disabled', false);
                    //class="judge-box" id="box{{ $c->id }}"

                    $('.judge-box').prop('checked', false);

                    for (var i = 0; i < response.length; i++) {
                        $('#box' + response[i].judge_id).prop('checked', true);
                    }
                    $('#judges-list').show();

                },
                error: function(response) {
                    console.log(response)

                }
            });
        }
    </script>



</body>

</html>
