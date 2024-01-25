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
    .horse-status--2  {
        background-color: #ffe5e5;
    }
      .horse-status--3{
          background-color: #ffd7d7;
    }
    .completed-horse{
        background-color: #fffde3;
    }
    .h6trte{
        background-color: #eff1f4; 
        border-radius: 5px; 
        text-align: center; 
        margin: 3px; 
        padding: 5px;
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
                    <div class="my-2">

                        <input type="number" name="competition_id" value="<?php echo $d->id; ?>" class="form-control"
                            hidden>

                    </div>
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
                    {{-- <div class="my-2">
                        <label for="competition_id">Competition</label>
                        <select id="group_competition_id" name="competition_id" class="form-control" required>
                            <?php
                            $data = \App\Models\Competition::get();
                            foreach ($data as $do) { ?>
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


{{-- edit competition modal start --}}
<div class="modal fade" id="editCompetitionModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="edit-competition-title" class="modal-title">Edit Competition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="edit_competition_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="competition_id" value="-1">
                <div class="modal-body p-4 bg-light">

                    <div class="my-2">
                        <label for="active_phase">Active Phase</label>
                        <select id="select_active_phase" name="active_phase" class="form-control" required>
                            <option value="1" selected>Phase 1</option>
                            <option value="2">Phase 2</option>
                            <option value="3">Phase 3</option>
                        </select>
                    </div>

                    <div class="my-2">
                        <label for="name_en">Name (En)</label>
                        <input type="text" id="competition_name_en" name="name_en" class="form-control"
                            placeholder="English Name" required>
                    </div>
                    <div class="my-2">
                        <label for="name_ar">Name (Ar)</label>
                        <input type="text" id="competition_name_ar" name="name_ar" class="form-control"
                            placeholder="Arabic Name" required>
                    </div>
                    <div class="my-2">
                        <label for="description_en">Description (En)</label>
                        <textarea id="competition_description_en" name="description_en" class="form-control"
                            placeholder="English Description" rows="5" required></textarea>
                    </div>
                    <div class="my-2">
                        <label for="description_ar">Description (Ar)</label>
                        <textarea id="competition_description_ar" name="description_ar" class="form-control"
                            placeholder="Arabic Description" rows="5" required></textarea>
                    </div>
                    <div class="my-2">
                        <label for="score_calc_type">Score Calculation Type</label>
                        <select id="select_score_type" name="score_calc_type" class="form-control" required>
                            <option value="flat" selected>Flat</option>
                            <option value="remove-high-low">Remove-High-Low</option>
                        </select>
                    </div>

                    {{-- <div class="my-2">
                        <label for="prize_report_header">Prize Report Header</label>
            
                          <!-- TinyMCE Editor -->
                          <textarea id="competition_prize_report_header" name="prize_report_header" class="tinymce-editor form-control" placeholder="Prize Report Header" rows="5" required>
                            
                          </textarea><!-- End TinyMCE Editor -->
            
                    </div>
                    <div class="my-2">
                        <label for="prize_report_footer">Prize Report Footer</label>
            
                          <!-- TinyMCE Editor -->
                          <textarea id="competition_prize_report_footer" name="prize_report_footer" class="tinymce-editor form-control" placeholder="Prize Report Footer" rows="5" required>
                            
                          </textarea><!-- End TinyMCE Editor -->
            
                    </div> --}}


                    <div class="my-2">
                        <label for="prize_report_header">Prize Report Header</label>
                        <textarea id="competition_prize_report_header" name="prize_report_header" class="form-control" placeholder="Prize Report Header" rows="5" required></textarea>
                    </div>
                    <div class="my-2">
                        <label for="report_footer">Prize Report Footer</label>
                        <textarea id="competition_prize_report_footer" name="prize_report_footer" class="form-control" placeholder="Prize Report Footer" rows="5" required></textarea>
                    </div>
                    <div class="my-2">
                        <label for="owner_name">Prize Owner Name</label>
                        <input id="competition_prize_owner_name" type="text" name="prize_owner_name" class="form-control" placeholder="Prize Owner Name" required>
                    </div>
                    <div class="my-2">
                        <label for="owner_description">Prize Owner Description</label>
                        <input id="competition_prize_owner_description" type="text" name="prize_owner_description" class="form-control" placeholder="Prize Owner Description" required>
                    </div>
                    <div class="my-2">
                        <label for="prize_currency">Prize Currency</label>
                        <input id="competition_prize_currency" type="text" name="prize_currency" class="form-control" placeholder="Prize Currency" required>
                    </div>

                    <div class="my-2">
                        <label for="logo">Logo</label>
                        <input type="file" name="logo" class="form-control" onchange="readURL_(this)">
                        <img id="edit_logo" class="blah_" src="" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit_competition_btn" class="btn btn-success">Update
                        Competition</button>
                    <div id="edit_spinner" class="spinner-border text-primary" role="status"
                        style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- edit competition modal end --}}


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
                    <li class="breadcrumb-item"><a href="{{ url('dashboard/competitions') }}">Competitions</a></li>
                    <li id="comp_title" class="breadcrumb-item active"></a></li>
                </ol>
            </nav>
        </div><!-- End Page Title --> --}}


        <section class="section dashboard">
            <div class="row">

                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    
                                   
                                    <h3 id="compet_title"></h3>
                                    <h6 class="h6trte"><a href="{{url('dashboard/single-competition-classes/'.$d->id)}}" >Manage Competition Classes</a></h6>
                                    <h6 class="h6trte"><a href="{{url('dashboard/champion-classes/'.$d->id)}}">Open Champion Classes</a></h6>
                                    <h6 class="h6trte"><a href="{{url('dashboard/foals-champions/'.$d->id)}}">Open Foal Classes</a></h6>
                                    <div id="edit_card_div" class="card-header"></div>
                                </div>

                                <div class="row">

                                    {{-- <div id="edit_div" class=" col-4">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card shadow">
                                                    <div
                                                        class="card-header d-flex justify-content-between align-items-center">
                                                        <h3>Details</h3>
                                                    </div>
                                                    <div class="card-body" id="">

                                                        <form action="#" method="POST"
                                                            id="edit_competition_form" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="id" id="competition_id"
                                                                value="-1">
                                                            <div class="modal-body p-4 ">

                                                                <div class="my-2">
                                                                    <label for="name_en">Name (En)</label>
                                                                    <input type="text" id="competition_name_en"
                                                                        name="name_en" class="form-control"
                                                                        placeholder="English Name" required>
                                                                </div>
                                                                <div class="my-2">
                                                                    <label for="name_ar">Name (Ar)</label>
                                                                    <input type="text" id="competition_name_ar"
                                                                        name="name_ar" class="form-control"
                                                                        placeholder="Arabic Name" required>
                                                                </div>
                                                                <div class="my-2">
                                                                    <label for="description_en">Description
                                                                        (En)</label>
                                                                    <textarea id="competition_description_en" name="description_en" class="form-control"
                                                                        placeholder="English Description" rows="5" required></textarea>
                                                                </div>
                                                                <div class="my-2">
                                                                    <label for="description_ar">Description
                                                                        (Ar)</label>
                                                                    <textarea id="competition_description_ar" name="description_ar" class="form-control"
                                                                        placeholder="Arabic Description" rows="5" required></textarea>
                                                                </div>
                                                                <div class="my-2">
                                                                    <label for="score_calc_type">Score Calculation
                                                                        Type</label>
                                                                    <select id="select_score_type"
                                                                        name="score_calc_type" class="form-control"
                                                                        required>
                                                                        <option value="flat" selected>Flat</option>
                                                                        <option value="remove-high-low">Remove-High-Low
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                                <div class="my-2">
                                                                    <label for="logo">Logo</label>
                                                                    <input type="file" name="logo"
                                                                        class="form-control"
                                                                        onchange="readURL_(this)">
                                                                    <img id="edit_logo" class="blah_"
                                                                        src="" />
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" id="edit_competition_btn"
                                                                    class="btn btn-success">Update
                                                                </button>
                                                                <div id="edit_spinner"
                                                                    class="spinner-border text-primary" role="status"
                                                                    style="display: none;">
                                                                    <span class="visually-hidden"></span>
                                                                </div>
                                                            </div>
                                                        </form>



                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class=" col-4">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card shadow">
                                                    <div
                                                        class="card-header d-flex justify-content-between align-items-center">
                                                        <h3>Classes</h3>
                                                        <div>

 <a style="font-size: 14px; margin-left: 5px;" class="btn btn-warning" target="blank"  href="{{url('dashboard/champion-qualifiers/'.$d->id)}}" >Qualified Horses</a>
<!--                                                            <button class="btn btn-secondary" data-bs-toggle="modal"
                                                                data-bs-target="#addGroupModal">

                                                                <i class="bi-plus"></i>
                                                            </button>-->
                                                            {{-- <button class="btn btn-warning" data-bs-toggle="modal"
                                                                data-bs-target="#addGroupModal">

                                                                <i class="bi-arrow-down"></i>
                                                            </button> --}}
                                                        </div>
                                                    </div>
                                                    <div class="card-body" id="">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>

                                                                        <th>Name</th>
                                                                        <th style="text-align: center;">Sections</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody id="show_all_groups">

                                                                    <tr>
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
                                                                    </tr>

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
                                                        <h3>Horses</h3>
                                                        <div>
                                                            {{-- <button class="btn btn-secondary" data-bs-toggle="modal"
                                                                data-bs-target="#addGroupModal">

                                                                <i class="bi-plus"></i>
                                                            </button>
                                                            <button class="btn btn-warning" data-bs-toggle="modal"
                                                                data-bs-target="#addGroupModal">

                                                                <i class="bi-arrow-down"></i>
                                                            </button> --}}
                                                            <button id="set_active_class" class="btn btn-primary" hidden>
                                                                Set active
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
                                                                        <th style="text-align: center;">Actions</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody id="show_all_horses">

                                                                    <tr>
                                                                        <td colspan="100%"
                                                                            style="text-align: center;">
                                                                            <p class="mt-3">Choose a class section to show
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

                                <div class="card-body" id="">






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

            // add new class ajax request
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
                            fetchAllCompetitions();
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

            $("#edit_competition_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#edit_spinner").css("display", "block");
                $("#edit_competition_btn").text('Updating...');
                $("#edit_competition_btn").attr('disabled', true);
                $.ajax({
                    url: server + "competitions/update",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllCompetitions();
                        }
                        $("#edit_competition_btn").text('Update');
                        $("#edit_competition_btn").attr('disabled', false);
                        $("#edit_competition_form")[0].reset();
                        $("#editCompetitionModal").modal('hide');
                        $("#edit_spinner").css("display", "none");
                        $('#edit_logo').removeAttr('src');
                        $('#edit-toast').toast('show');
                    },
                    // error: (res) => {

                    //     $("#edit_competition_btn").text('Update');
                    //     $("#edit_competition_btn").attr('disabled', false);
                    // }
                });
            });


            // fetch all competition ajax request
            var active_class = -1;
            var active_horse = -1;
                var active_section = 'A';

            function fetchAllCompetitions() {
                $.ajax({
                    url: server + "competitions/single/<?php echo $d->id; ?>",
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r;
                        console.log(response)
                        // var str = "";

                        active_class = response.active_class;
                        active_section = response.active_section;
                        //  active_horse = response.active_horse;
                        console.log("a c " + active_class + " "+active_section)
                        $('#comp_title').text(response.name_en);
                        $('#compet_title').text(response.name_en);
                        $("#edit_card_div").replaceWith("<button data-id='" + response.id +
                            "'  data-name_en='" + response.name_en +
                            "'  data-name_ar='" + response.name_ar +
                            "'  data-description_en='" + response.description_en +
                            "'  data-description_ar='" + response.description_ar +
                            "'  data-score_calc_type='" + response.score_calc_type +
                            "'  data-active_phase='" + response.active_phase +
                            "'  data-prize_report_header='" + response.prize_report_header +
                            "'  data-prize_report_footer='" + response.prize_report_footer +
                            "'  data-prize_owner_name='" + response.prize_owner_name +
                            "'  data-prize_owner_description='" + response.prize_owner_description +
                            "'  data-prize_currency='" + response.prize_currency +
                            "'  data-logo='" + response.logo +
                            "'  class='btn btn-secondary btn-md mt-1 edit-competition-btn' style='margin-left: 4px;'><i class=\"bi-pencil-square \"></i></button>"
                        );
                        // $("#edit_card_div").replaceWith("");

                        $('#competition_id').val(response.id);
                        $('#competition_name_en').val(response.name_en);
                        $('#competition_name_ar').val(response.name_ar);
                        $('#competition_description_en').val(response.description_en);
                        $('#competition_description_ar').val(response.description_ar);
                        $('#select_score_type').val(response.score_calc_type);
                        $('#select_active_phase').val(response.active_phase);


                        $('#competition_prize_report_header').summernote({
                                                                 placeholder: 'Enter Report Header',
                                                                 tabsize: 2,
                                                                 height: 120,
                                                                 toolbar: [
                                                                             ['style', ['style']],
                                                                             ['color', ['color']],
                                                                             ['fontname', ['fontname']],
                                                                             ['fontsize', ['fontsize']],
                                                                             ['font', ['bold', 'underline','italic', 'clear']],
                                                                             ['para', ['ul', 'ol', 'paragraph']],
                                                                             //  ['table', ['table']],
                                                                             // ['insert', ['link', 'picture', 'video']],
                                                                             // ['view', ['fullscreen', 'codeview', 'help']]
                                                                          ],
                                                                });
                        $('#competition_prize_report_header').summernote('code', response.prize_report_header);



                        $('#competition_prize_report_footer').summernote({
                                                                 placeholder: 'Enter Report Footer',
                                                                 tabsize: 2,
                                                                 height: 120,
                                                                 toolbar: [
                                                                             ['style', ['style']],
                                                                             ['color', ['color']],
                                                                             ['fontname', ['fontname']],
                                                                             ['fontsize', ['fontsize']],
                                                                             ['font', ['bold', 'underline','italic', 'clear']],
                                                                             ['para', ['ul', 'ol', 'paragraph']],
                                                                             //  ['table', ['table']],
                                                                             // ['insert', ['link', 'picture', 'video']],
                                                                             // ['view', ['fullscreen', 'codeview', 'help']]
                                                                          ],
                                                                });
                        $('#competition_prize_report_footer').summernote('code', response.prize_report_footer);
                            

                        // tinymce.get('competition_prize_report_header').setContent(response.prize_report_header);
                        // tinymce.get('competition_prize_report_footer').setContent(response.prize_report_footer);


                        // $('#competition_prize_report_header').val(response.prize_report_header);
                        // $('#competition_prize_report_footer').val(response.prize_report_footer);
                        $('#competition_prize_owner_name').val(response.prize_owner_name);
                        $('#competition_prize_owner_description').val(response.prize_owner_description);
                        $('#competition_prize_currency').val(response.prize_currency);
                        $('#edit_logo').attr("src", response.logo);
                        $('#edit-competition-title').html("Edit " + response.name_en);


                        // str += "";



                        // $("#show_all_groups").html(str);

                        //add click listner

                        $('.edit-competition-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let img_source = $(this).attr('data-logo');

                            console.log('cliked : ' + id);
                            // $('#competition_id').val(id);
                            // $('#competition_name_en').val($(this).attr('data-name_en'));
                            // $('#competition_name_ar').val($(this).attr('data-name_ar'));
                            // $('#competition_description_en').val($(this).attr(
                            //     'data-description_en'));
                            // $('#competition_description_ar').val($(this).attr(
                            //     'data-description_ar'));
                            // $('#select_score_type').val($(this).attr('data-score_calc_type'));
                            // $('#edit_logo').attr("src", img_source);
                            // $('#edit-competition-title').html("Edit " + $(this).attr(
                            //     'data-name_en'));

                            $('#editCompetitionModal').modal('show');

                        });


                        $('.del-competition-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let csrf = '{{ csrf_token() }}';


                            console.log('cliked : ' + id);

                            if (confirm("Are you sure to remove " + $(this).attr(
                                        'data-name') +
                                    "?")) {

                                // call ajax to delete this competition
                                $(this).html("Deleting...");
                                $(this).prop("disabled", true);

                                $.ajax({
                                    url: server + "competitions/delete",
                                    method: 'delete',
                                    data: {
                                        id: id,
                                        _token: csrf
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        fetchAllCompetitions();
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


            function fetchAllGroups() {
                $.ajax({
                    url: server + "groups/fetchall/<?php echo $d->id; ?>",
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data.data;
                        console.log(r.competition);
                        let selected_class_index = r.competition.active_class;
                        let selected_section_index = r.competition.active_section;
                        
                        
                        console.log("dd- > " + selected_class_index + " >> " + selected_section_index)
                        
                        console.log(response.length)
                        var str = "";
                        if (response.length > 0) {
                            for (var i = 0; i < response.length; i++) {
                                str += "<tr class='group-row " + (selected_class_index == response[i]
                                        .id ? "selected-row" : "") +
                                    "' ><td>" + response[i].name_en + "</td>" + "' ><td style='text-align: center;'>";
                            
    for(var j=0;j<response[i].sections.length;j++){
        str+=" <a style='border: 1px solid #a39797;' href='#' class=' btn btn-sm m-1 "+( 
               ( selected_class_index == response[i] .id   &&  selected_section_index == response[i].sections[j].sectionLabel )
        ? " btn-success " : " btn-light " )+"class-link-click' data-id='" +
                                    response[i]
                                    .id + "'  data-section='"+
             response[i].sections[j].sectionLabel                 
                +"'>"+response[i].sections[j].sectionLabel+"</a>";
    }
    
   str+="</tr>";
                            }

                            $("#show_all_groups").html(str);

                            $('.class-link-click').off('click');
                            $('.class-link-click').click(function() {

                                // $(this).parent().parent().css({

                                //     'background-color': '#b8d7bb'
                                // });


                                let id = $(this).attr('data-id');
                                let section = $(this).attr('data-section');
                                fetchClassHorses(id  ,section);

                            })







                        } else {
                            str =
                                "<p style='margin-top:20px;margin-left:70px;'>No classes yet</p><p style='margin-left:70px;'>Start Adding</p>";
                            $("#show_all_groups").html(str);
                        }


                        $('.edit-group-btn').click(function() {


                            let id = $(this).attr('data-id');



                            console.log('cliked : ' + id);
                            $('#group_id').val(id);
                            $('#group_name_en').val($(this).attr('data-name_en'));
                            $('#group_name_ar').val($(this).attr('data-name_ar'));
                            $('#group_current_horse').val($(this).attr('data-current_horse'));
                            $('#group_competition_id').val($(this).attr('data-competition_id'));
                            $('#group_status').val($(this).attr('data-status'));
                            $('#edit-group-title').html("Edit " + $(this).attr('data-name_en'));
                            $('#editGroupModal').modal('show');

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
                                        fetchAllCompetitions();
                                        $('#delete-toast').toast('show');
                                    }
                                });

                            }



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

                    }

                });
            }

            function fetchClassHorses(id ,section) {


                let is_active = active_class == id && active_section == section;
                $("#set_active_class").attr("data-id", id);
                $("#set_active_class").attr("data-section", section);
                $('#cck-9087').html("<a class='btn btn-warning btn-sm mt-1' href='{{url('dashboard/class-ranking')}}/"+id+"/"+section+"' target='blank'>Standings</a>")
                
                $("#set_active_class").html(is_active ? "Current" : "Set Current")




                if (is_active) {
                    $("#set_active_class").prop('disabled', true);

                } else {
                    $("#set_active_class").prop('disabled', false);
                }


                $("#class_horses_div").attr("hidden", false);
                $("#set_active_class").attr("hidden", false);

                $("#show_all_horses").html(
                    "<tr><td colspan='100%' style='text-align: center;'><div class='lds-spinner'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></tr></td>"
                );


                console.log('cliked : ' + id);

                $.ajax({
                    url: server + "class-horses/fetchForClass/" + id + "/" + section,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        active_horse = r.current_class_object.current_horse;
                        console.log(r.current_class_object);
                        let response = r.data.data;
                        console.log(response.length)
                        var str = "";
                        if (response.length > 0) {
                            for (var i = 0; i < response
                                .length; i++) {
                                str +=
                                    "<tr class='horse-row   horse-status-"+response[i].status+" " + (
                                        active_horse == response[i]
                                        .id ? "selected-row" : "") +
                                    "'><td>" + response[i].reg_number +
                                    " </td><td>" + response[i].horse
                                    .name_en ;
                            
                            
                       
                            
                              str+=      "</td><td>" ;
                            
    
   if(response[i].status == 1) str += "Present" ;
   if(response[i].status == -1) str += "Absent" ;
   if(response[i].status == -2) str += "Lame" ;
    if(response[i].status == -3) str += "Disqualified" ;
                                        str +=
                                    "</td><td>" + response[i]
                                    .sectionLabel +
                                    "</td><td style='text-align : center' class='"+(parseFloat(response[i].total_points) > 0 ? "completed-horse" : "")+"'>";
                            
                            
                         let is_evaluated = parseFloat(response[i].total_points) > 0;  
                            if(!is_evaluated)
                            {
                    
                    
                            
                               
      if((response[i].status != -1)){
                                    str += "   <button  data-id='" +
                                    response[i].id +
                                    "'   class='btn btn-danger btn-sm mt-1 set-absent-horse-btn' data-action='-1'>Absent</button>";
                        }
                   if((response[i].status != -2)){           
            str += "   \n\
                                <button data-id='" +
                                    response[i].id +
                                    "'   class='btn btn-danger btn-sm mt-1 set-absent-horse-btn' data-action='-2'>Lame</button>";
                        }
                        
              if((response[i].status != -3)){
            str += "    \n\
                                <button data-id='" +
                                    response[i].id +
                                    "'   class='btn btn-danger btn-sm mt-1 set-absent-horse-btn' data-action='-3'>Disqualified</button>";
                        }
//                              if (is_active)

                            
                            
                      if((response[i].status != 1)){
                                str += "   <button data-id='" +
                                    response[i].id +
                                    "'   class='btn btn-info btn-sm mt-1 set-present-horse-btn'>Set Present</button>";
                      
        }
        
        
         if((response[i].status == 1)){
                                    str += "   <button data-id='" +
                                    response[i].id +
                                    "'  class='btn btn-success btn-block btn-sm mt-1 current-horse-btn' "+(
                                        active_horse == response[i]
                                        .id ? "disabled" : "")+">"+(
                                        active_horse == response[i]
                                        .id ? "Current" : "Set Current")+"</button>";
 }
                    
                    
                    }else{
                             str+= "<span style='color : green'>Evaluated<br /><b>"+parseFloat(response[i].total_points).toFixed(2) + "</b></span>";
                         }




                                str += "  </td></tr>";
                            }

                            $("#show_all_horses").html(str);


                            $('.current-horse-btn').off('click');


                            let clicked_class = id;
                            $('.current-horse-btn').click(function() {

                                let id = $(this).attr('data-id');
                                console.log('cliked : ' + id);
                                $('.current-horse-btn').prop('disabled', true);
                                let class_id = $("#set_active_class").attr('data-id');

                                let comp_id = {{ Route::input('id') }};

                                $.ajax({
                                    url: server + "competitions/update-current-horse/" +
                                        class_id + "/" + id + "/" + comp_id,
                                    dataType: 'json',
                                    method: 'get',
                                    success: function(r) {

                                        // fetchAllGroups();
                                        fetchClassHorses(clicked_class ,section);

                                    }
                                });

  $.ajax({
                                    url: server + "competitions/notifyJudges/" +
                                        class_id ,
                                    dataType: 'json',
                                    method: 'get',
                                    success: function(r) {


                                    }
                                });



                            });


//----------------------------------------------

   $('.set-absent-horse-btn').off('click');


                     
                          $('.set-absent-horse-btn').click(function() {

                                let id = $(this).attr('data-id');
                                let action =$(this).attr('data-action');
                                console.log('cliked : ' + id);
                                $(this).prop('disabled', true);

                                $.ajax({
                                    url: server + "horses/set-absent/" +
                                        id  + "/" +action ,
                                    dataType: 'json',
                                    method: 'get',
                                    success: function(r) {

                                        fetchClassHorses(clicked_class ,section);

                                    }
                                });

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



                            });

   $('.set-present-horse-btn').off('click');


                     
                          $('.set-present-horse-btn').click(function() {

                                let id = $(this).attr('data-id');
                                console.log('cliked : ' + id);
                                $(this).prop('disabled', true);

                                $.ajax({
                                    url: server + "horses/set-present/" +
                                        id ,
                                    dataType: 'json',
                                    method: 'get',
                                    success: function(r) {

                                        fetchClassHorses(clicked_class ,section);

                                    }
                                });

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



                            });




                        } else {
                            str =
                                "<p style='margin-top:20px;margin-left:30px;'>No horses in this class</p>";
                            $("#show_all_horses").html(str);
                        }
                    }
                });


            }


            $('#set_active_class').click(function() {

                let id = $(this).attr('data-id');
                let section = $(this).attr('data-section');
                  $('#set_active_class').html('Loading..')
                  $('#set_active_class').prop('disabled' ,true)
                let comp_id = {{ Route::input('id') }};

                $.ajax({
                    url: server + "competitions/update-current-class/" +
                        comp_id + "/" + id + "/" + section,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        active_class = id;
                         active_section = section;
                         
                         
                        fetchAllGroups();
                        fetchClassHorses(id ,section);


                    }
                });


            });

            function fetchAllCompetitionsPagesOnly() {
                $.ajax({
                    url: server + "competitions/fetchall?page=" + current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;
                        console.log(response.length)


                        //add click listner

                        // handle pages :

                        var pgstr = "";
                        for (var i = 1; i <= Math.ceil(r.total / 25); i++) {
                            pgstr += "<button class='page-cliker " + (i == 1 ? "current-page" :
                                    "") +
                                " ' id='cliker-" + i + "' data-link='" + i + "' >" + i +
                                "</button>  ";
                        }
                        $('#pages-container').html(pgstr);



                        $('.page-cliker').click(function() {
                            current_page = $(this).attr('data-link');
                            console.log("going to page : " + $(this).attr('data-link'))


                            fetchAllCompetitions();

                        })
                        fetchAllCompetitions();

                    },
                    error: function(response) {
                        console.log("err " + response)

                    }
                });
            }

            console.log("calling .. ")
            fetchAllCompetitionsPagesOnly();
            fetchAllGroups();



        });
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.blah').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        function readURL_(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.blah_').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>


</body>

</html>
