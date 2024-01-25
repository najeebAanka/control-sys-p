<!DOCTYPE html>
<html lang="en">

<?php
$project = App\Models\Competition::find(Route::input('id'));
$d = $project;
?>

@include('dashboard.shared.css')
<style>
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

{{-- add new group modal start --}}
<div class="modal fade" id="addGroupModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="add_group_form" enctype="multipart/form-data">
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
                    <div hidden class="my-2">
                        <label for="competition_id">Competition</label>
                        <select name="competition_id" class="form-control">
                            <option selected value="{{ $d->id }}">Competition</option>
                        </select>
                    </div>
                    <div class="my-2">
                        <label for="start_dob">Start DOB</label>
                        <input type="date" name="start_dob" class="form-control" placeholder="Start DOB" required>
                    </div>
                    <div class="my-2">
                        <label for="end_dob">End DOB</label>
                        <input type="date" name="end_dob" class="form-control" placeholder="End DOB" required>
                    </div>
                    <div class="my-2">
                        <label for="max_in_section">Max in section</label>
                        <input type="number" name="max_in_section" class="form-control" placeholder="Max in section"
                            required>
                    </div>
                    <div class="my-2">
                        <label for="gender">Gender</label>
                        <select name="gender" class="form-control" required>
                            <option value="male" selected>Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    {{-- <div class="my-2">
                        <label for="current_horse">Current Horse</label>
                        <select name="current_horse" class="form-control" required>
                            <option value="-1" selected style="display:none">Select Horse</option>
                            <?php
                            $data = \App\Models\HorseRegistration::get();
                            foreach ($data as $do) { ?>
                            <option value="<?php echo $do->id; ?>"><?php echo $do->name_en; ?> </option>
                            <?php } ?>
                        </select>
                    </div> 
                    <div class="my-2">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="1" selected>Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div> --}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add_group_btn" class="btn btn-primary">Add Class</button>
                    <div id="add_spinner" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- add new group modal end --}}


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
                    <div class="my-2">
                        <label for="start_dob">Start DOB</label>
                        <input id="group_start_dob" type="date" name="start_dob" class="form-control"
                            placeholder="Start DOB" required>
                    </div>
                    <div class="my-2">
                        <label for="end_dob">End DOB</label>
                        <input id="group_end_dob" type="date" name="end_dob" class="form-control"
                            placeholder="End DOB" required>
                    </div>
                    <div class="my-2">
                        <label for="max_in_section">Max in section</label>
                        <input id="group_max_in_section" type="number" name="max_in_section" class="form-control"
                            placeholder="Max in section" required>
                    </div>
                    <div class="my-2">
                        <label for="gender">Gender</label>
                        <select id="group_gender" name="gender" class="form-control" required>
                            <option value="male" selected>Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div hidden class="my-2">
                        <label for="competition_id">Competition</label>
                        <select id="group_competition_id" name="competition_id" class="form-control" required>
                            <option selected value="{{ $d->id }}">Competition</option>
                        </select>
                    </div>
                    {{-- <div class="my-2">
                        <label for="current_horse">Current Horse</label>
                        <select id="group_current_horse" name="current_horse" class="form-control" required>
                            <option value="-1" style="display:none">Select Horse</option>
                            <?php

                            $p = '<script>document.getElementById("group_id").value;</script>';
                           
                            $data = \App\Models\HorseRegistration::where('group_id', '=', 22)->get();
                            foreach ($data as $do) { ?>
                            <option value="<?php echo $do->horse_id; ?>"><?php $dat = \App\Models\Horse::where('id', '=', $do->horse_id)->first();
                            echo $dat->name_en; ?> </option>
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



{{-- edit group modal start --}}
<div class="modal fade" id="editGroupJudgesModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="edit-judge-group-title" class="modal-title">Edit Judges</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="edit_judge_group_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="judge_group_id" value="-1">
                <h3 style="font-size: 20px; padding: 1rem;" id="yyuu08"> </h3>
                <div class="modal-body p-4 bg-light">

                    <div class="my-2" id="cvu8">
                        
                        <label for="name_en">Select Section</label>
                        <select id="section_select_in" name="section" onchange="c4()" class="form-control"
                            placeholder="English Name" required>
                            {{-- <option selected disabled hidden>Select section</option> --}}

                            {{-- <option value="A">A</option>
                            <option value="B">B</option> --}}


                        </select>
                        <br />
                        
                    </div>

                    <div class="judges-list">

                        <table>
                            <tr>
                                <th class="th_cheqer">Select</th>
                                <th class="th_cheqer">Judge Name</th>
                            </tr>
                           
                        <?php foreach (\App\Models\User::where('user_type' ,0)->where('status' ,1)->orderBy('order_label')->get() as $c){ ?>
                            <tr>
                                <td class="td_check_box">
                        <input style="margin-left: 15px;" type="checkbox" name="judges[]" class="judge-box" id="box{{ $c->id }}"
                            value="{{ $c->id }}" /></td><td class="td_chexxx"> {{ $c->order_label }} - <?php if($c->gender == "male") echo "Mr. "; else echo"Mrs. ";  ?> {{ $c->name }}
                        {{-- <hr /> --}}
                    </td>
                </tr>
                        <?php } ?>
                    </table>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit_judges_group_btn" class="btn btn-success">Update judges
                        status</button>
                    <div id="edit_judge_spinner" class="spinner-border text-primary" role="status"
                        style="display: none;"></div>

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

        <!--<div class="pagetitle">-->
        <!--    <h1>Competition Classes</h1>-->
        <!--    <nav>-->
        <!--        <ol class="breadcrumb">-->
        <!--            <li class="breadcrumb-item"><a href="{{ url('dashboard/home') }}">Home</a></li>-->
        <!--            <li class="breadcrumb-item">Competitions Management</li>-->
        <!--            <li class="breadcrumb-item active">Competition Classes</li>-->
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
                                    <h3 id="compet_title">Manage Competition Classes</h3>
                                    <button class="btn btn-light" data-bs-toggle="modal"
                                        data-bs-target="#addGroupModal">
                                        <i class="bi-plus-circle me-2"></i>
                                        Add New Class
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
                                                    {{-- <th>Current Horse</th>
                                                    <th>Status</th> --}}
                                                    <th style='text-align : center'>Actions</th>

                                                </tr>
                                            </thead>
                                            <tbody id="show_all_groups">

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

            // add new group ajax request
            $("#add_group_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#add_spinner").css("display", "block");
                $("#add_group_btn").text('Adding...');
                $("#add_group_btn").attr('disabled', true);
                $.ajax({
                    url: server + "groups/store",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllGroups();
                        }
                        $("#add_group_btn").text('Add Class');
                        $("#add_group_btn").attr('disabled', false);
                        $("#add_group_form")[0].reset();
                        $("#addGroupModal .btn-close").click();
                        $("#add_spinner").css("display", "none");
                        $('#add-toast').toast('show');
                    }
                });
            });

            $("#edit_group_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#edit_spinner").css("display", "block");
                $("#edit_group_btn").text('Updating...');
                $("#edit_group_btn").attr('disabled', true);
                $.ajax({
                    url: server + "groups/update",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllGroups();
                        }
                        $("#edit_group_btn").text('Update Class');
                        $("#edit_group_btn").attr('disabled', false);
                        $("#edit_group_form")[0].reset();
                        $("#editGroupModal").modal('hide');
                        $("#edit_spinner").css("display", "none");
                        $('#edit-toast').toast('show');
                    },
                    // error: (res) => {

                    //     $("#edit_group_btn").text('Update');
                    //     $("#edit_group_btn").attr('disabled', false);
                    // }
                });
            });

            $("#edit_judge_group_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#edit_judge_spinner").css("display", "block");
                $("#edit_judges_group_btn").text('Updating...');
                $("#edit_judges_group_btn").attr('disabled', true);
                $.ajax({
                    url: server + "groups/judges/update",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        if (response.status == 200) {

                            $("#edit_judges_group_btn").text('Update judges status');
                            $("#edit_judges_group_btn").attr('disabled', false);
                            $("#editGroupJudgesModal").modal('hide');
                            $("#edit_judge_spinner").css("display", "none");
                            $('#edit-toast').toast('show');
                        }
                        //                        $("#edit_group_btn").text('Update Class');
                        //                        $("#edit_group_btn").attr('disabled', false);
                        //                        $("#edit_group_form")[0].reset();
                        //                        $("#editGroupModal").modal('hide');
                        //                        $("#edit_spinner").css("display", "none");
                        //                        $('#edit-toast').toast('show');
                    },
                    // error: (res) => {

                    //     $("#edit_group_btn").text('Update');
                    //     $("#edit_group_btn").attr('disabled', false);
                    // }
                });
            });

            // fetch all groups ajax request

            function fetchAllGroups() {
                $.ajax({
                    url: server + "groups/fetchallclasses/<?php echo $d->id; ?>?page=" + current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;
                        console.log(response.length)
                        $('#compet_title').text("<?php echo $d->name_en; ?>" + " Classes");
                        var str = "";
                        for (var i = 0; i < response.length; i++) {
                            str += "<tr><td>" + response[i].id + "</td><td>" + response[i].name_en +
                                "</td><td>" + response[i].start_dob +
                                "</td><td>" + response[i].end_dob +
                                "</td><td style='text-align : center'></button><button data-id='" +
                                response[i]
                                .id +
                                "'  data-name_en='" + response[i].name_en +
                                "'  class='btn btn-info btn-sm mt-1  ml-1 mr-1 edit-group-judges-btn'>Judges <i class=\"bi-pencil-square \"></i></button> \n\
                                                     <button  data-name_en='" + response[i].name_en +
                                "'  data-id='" + response[i]
                                .id +
                                "'  data-name_en='" + response[i].name_en +
                                "'  data-name_ar='" + response[i].name_ar +
                                "'  data-competition_id='" + response[i].competition_id +
                                "'  data-current_horse='" + response[i].current_horse +
                                "'  data-status='" + response[i].status +
                                "'  data-start_dob='" + response[i].start_dob +
                                "'  data-end_dob='" + response[i].end_dob +
                                "'  data-max_in_section='" + response[i].max_in_section +
                                "'  data-gender='" + response[i].gender +
                                "'  class='btn btn-success btn-sm mt-1 ml-1 mr-1 edit-group-btn'>Edit <i class=\"bi-pencil-square \"></i><button data-id='" +
                                response[i]
                                .id +
                                "'  data-name_en='" + response[i].name_en +
                                "' class='btn btn-sm btn-danger mt-1  ml-1 mr-1 del-group-btn' style='margin-left: 4px;'>Remove <i class=\"bi-trash-fill \"></i></button></td></tr>";
                        }

                        $("#show_all_groups").html(str);

                        //add click listner


                        $('.edit-group-btn').click(function() {


                            let id = $(this).attr('data-id');



                            console.log('cliked dd: ' + id);
                            $('#group_id').val(id);
                            $('#group_name_en').val($(this).attr('data-name_en'));
                            $('#group_name_ar').val($(this).attr('data-name_ar'));
                            $('#group_start_dob').val($(this).attr('data-start_dob'));
                            $('#group_end_dob').val($(this).attr('data-end_dob'));
                            $('#group_max_in_section').val($(this).attr('data-max_in_section'));
                            $('#group_gender').val($(this).attr('data-gender'));
                            $('#group_current_horse').val($(this).attr('data-current_horse'));
                            $('#group_competition_id').val($(this).attr('data-competition_id'));
                            $('#group_status').val($(this).attr('data-status'));
                            $('#edit-group-title').html("Edit " + $(this).attr('data-name_en'));
                            $('#editGroupModal').modal('show');

                        });
                        $('.edit-group-judges-btn').click(function() {

                            let id = $(this).attr('data-id');
                            let name = $(this).attr('data-name_en');
                            $('#cvu8').hide();
                            $('.judge-box').prop('checked', false);
                            $('.judge-box').attr('disabled', true);

                            console.log('cliked : ' + id);

                            $.ajax({
                                url: server + "groups/judges/" + id,
                                dataType: 'json',
                                method: 'get',
                                success: function(r) {

                                    $('.judge-box').attr('disabled', false);
                                    var str = "";
                                    let response = r.data;
                                    for (var i = 0; i < r.length; i++) {
                                        str += "<option value='" + r[i]
                                            .sectionLabel + "'>" + r[i]
                                            .sectionLabel + "</option>";
                                    }

                                    $("#section_select_in").html(str);

                                    $('#judge_group_id').val(id);
                                    $('#yyuu08').html(name);
                                    if(r.length > 1){

                                       $('#cvu8').show();
                                    }
                                    c4();


                                    $('#editGroupJudgesModal').modal('show');
                                }
                            });


                        });





                        $('.del-group-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let csrf = '{{ csrf_token() }}';


                            console.log('cliked : ' + id);

                            if (confirm("Are you sure to remove " + $(this).attr(
                                        'data-name_en') +
                                    "?")) {

                                // call ajax to delete this group
                                $(this).html("Deleting...");
                                $(this).prop("disabled", true);

                                $.ajax({
                                    url: server + "groups/delete",
                                    method: 'delete',
                                    data: {
                                        id: id,
                                        _token: csrf
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        fetchAllGroups();
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

            function fetchAllGroupsPagesOnly() {
                $.ajax({
                    // url: server + "groups/fetchall?page=" + current_page,
                    url: server + "groups/fetchallclasses/<?php echo $d->id; ?>?page=" + current_page,
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


                            fetchAllGroups();

                        })
                        fetchAllGroups();

                    },
                    error: function(response) {
                        console.log("err " + response)

                    }
                });
            }

            console.log("calling .. ")
            fetchAllGroupsPagesOnly();

            $('#check-judges-list-btn').click(function() {

                c4();


            });






        });


        function c4() {
            let id = $('#judge_group_id').val();
            let section = $('#section_select_in').val();
            
            $('.judge-box').attr('disabled', true);
            console.log(id + " " + section);
            $('#judges-list').hide();


            $.ajax({
                url: server + "groups/judges/" + id + "/" + section,
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
