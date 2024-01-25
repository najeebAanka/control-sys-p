<!DOCTYPE html>
<html lang="en">

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
</style>
{{-- add new competition modal start --}}
<div class="modal fade" id="addCompetitionModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Competition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="add_competition_form" enctype="multipart/form-data">
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
                        <label for="description_en">Description (En)</label>
                        <textarea name="description_en" class="form-control" placeholder="English Description" rows="5" required></textarea>
                    </div>
                    <div class="my-2">
                        <label for="description_ar">Description (Ar)</label>
                        <textarea name="description_ar" class="form-control" placeholder="Arabic Description" rows="5" required></textarea>
                    </div>
                    <div class="my-2">
                        <label for="score_calc_type">Score Calculation Type</label>
                        <select name="score_calc_type" class="form-control" required>
                            <option value="flat" selected>Flat</option>
                            <option value="remove-high-low">Remove-High-Low</option>
                        </select>
                    </div>
                    
                    <div class="my-2">
                        <label for="logo">Logo</label>
                        <input type="file" name="logo" class="form-control" onchange="readURL(this)" required>
                        <img id="add_logo" class="blah" src="" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add_competition_btn" class="btn btn-primary">Add Competition</button>
                    <div id="add_spinner" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- add new competition modal end --}}


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
                    <div class="my-2">
                        <label for="active_phase">Active Phase</label>
                        <select id="select_active_phase" name="active_phase" class="form-control" required>
                            <option value="1" selected>Phase 1</option>
                            <option value="2">Phase 2</option>
                            <option value="3">Phase 3</option>
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
                        <input type="file" name="logo" class="form-control" onchange="readURL_(this)" >
                        <img id="edit_logo" class="blah_"  src=""/>
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

        <!--<div class="pagetitle">-->
        <!--    <h1>Competitions</h1>-->
        <!--    <nav>-->
        <!--        <ol class="breadcrumb">-->
        <!--            <li class="breadcrumb-item"><a href="{{ url('dashboard/home') }}">Home</a></li>-->
        <!--            <li class="breadcrumb-item"><a href="{{ url('dashboard/competitions') }}">Competitions Management</a></li>-->
        <!--            <li class="breadcrumb-item active">Competitions</li>-->
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
                                    <h3>Manage Competitions</h3>
                                    <button class="btn btn-light" data-bs-toggle="modal"
                                        data-bs-target="#addCompetitionModal">
                                        <i class="bi-plus-circle me-2"></i>
                                        Add New Competition
                                    </button>
                                </div>
                                <div class="card-body" id="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th style="text-align: center;">Logo</th>
                                                    <th style="text-align: center;">Actions</th>

                                                </tr>
                                            </thead>
                                            <tbody id="show_all_competitions">

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

            // add new competition ajax request
            $("#add_competition_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#add_spinner").css("display", "block");
                $("#add_competition_btn").text('Adding...');
                $("#add_competition_btn").attr('disabled', true);
                $.ajax({
                    url: server + "competitions/store",
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
                        $("#add_competition_btn").text('Add Competition');
                        $("#add_competition_btn").attr('disabled', false);
                        $("#add_competition_form")[0].reset();
                        $("#addCompetitionModal .btn-close").click();
                        $("#add_spinner").css("display", "none");
                        $('#add_logo').removeAttr('src');
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
                        $("#edit_competition_btn").text('Update Competition');
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

            function fetchAllCompetitions() {
                $.ajax({
                    url: server + "competitions/fetchall?page=" + current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;
                        console.log(response.length)
                        var str = "";
                        var c_type_str = "";
                        for (var i = 0; i < response.length; i++) {
                            if(response[i].c_type == "normal"){c_type_str = 'single-competition/';} else{c_type_str = 'foals-champions/';}
                            str += "<tr><td>" + response[i].id + "</td><td>" + response[i].name_en +
                                "</td><td>" + response[i].description_en +
                                "</td><td style='text-align : center'><img class='sm-img' src='" +
                                response[i].logo +
                                "' /></td><td style='text-align : center'><a href='" + c_type_str  + response[i].id + "'  data-id='" + response[i].id +
                                "'  data-name_en='" + response[i].name_en +
                                "'  data-name_ar='" + response[i].name_ar +
                                "'  data-description_en='" + response[i].description_en +
                                "'  data-description_ar='" + response[i].description_ar +
                                "'  data-score_calc_type='" + response[i].score_calc_type +
                                "'  data-active_phase='" + response[i].active_phase +
                                "'  data-prize_report_header='" + response[i].prize_report_header +
                                "'  data-prize_report_footer='" + response[i].prize_report_footer +
                                "'  data-prize_owner_name='" + response[i].prize_owner_name +
                                "'  data-prize_owner_description='" + response[i].prize_owner_description +
                                "'  data-prize_currency='" + response[i].prize_currency +
                                "'  data-logo='" + response[i].logo +
                                "'  data-c_type='" + response[i].c_type +
                                "'  class='btn btn-secondary btn-sm mt-1 view-competition-btn'>View <i class=\"bi-eye \"></i></a><a style='margin-left: 4px;' href='" +  'single-competition-classes/' + response[i].id + "'  data-id='" + response[i].id +
                                "'  data-name_en='" + response[i].name_en +
                                "'  data-name_ar='" + response[i].name_ar +
                                "'  data-description_en='" + response[i].description_en +
                                "'  data-description_ar='" + response[i].description_ar +
                                "'  data-score_calc_type='" + response[i].score_calc_type +
                                "'  data-active_phase='" + response[i].active_phase +
                                "'  data-prize_report_header='" + response[i].prize_report_header +
                                "'  data-prize_report_footer='" + response[i].prize_report_footer +
                                "'  data-prize_owner_name='" + response[i].prize_owner_name +
                                "'  data-prize_owner_description='" + response[i].prize_owner_description +
                                "'  data-prize_currency='" + response[i].prize_currency +
                                "'  data-logo='" + response[i].logo +
                                "'  class='btn btn-primary btn-sm mt-1 classes-competition-btn'>Classes <i class=\"bi-link \"></i></a>\n\
                    \n\
<a href='classes-prizes/" + response[i].id + "'   class='btn btn-warning btn-sm mt-1'>Prizes <i class=\"bi-link \"></i></a>\n\
<button data-id='" + response[
                                    i]
                                .id +
                                "'  data-name_en='" + response[i].name_en +
                                "'  data-name_ar='" + response[i].name_ar +
                                "'  data-description_en='" + response[i].description_en +
                                "'  data-description_ar='" + response[i].description_ar +
                                "'  data-score_calc_type='" + response[i].score_calc_type +
                                "'  data-active_phase='" + response[i].active_phase +
                                "'  data-prize_report_header='" + response[i].prize_report_header +
                                "'  data-prize_report_footer='" + response[i].prize_report_footer +
                                "'  data-prize_owner_name='" + response[i].prize_owner_name +
                                "'  data-prize_owner_description='" + response[i].prize_owner_description +
                                "'  data-prize_currency='" + response[i].prize_currency +
                                "'  data-logo='" + response[i].logo +
                                "'  class='btn btn-success btn-sm mt-1 edit-competition-btn' style='margin-left: 4px;'>Edit <i class=\"bi-pencil-square \"></i></button><button data-id='" +
                                response[i]
                                .id +
                                "'  data-name='" + response[i].name_en +
                                "' class='btn btn-sm btn-danger mt-1 del-competition-btn' style='margin-left: 4px;'>Remove <i class=\"bi-trash-fill \"></i></button></td></tr>";
                        }

                        $("#show_all_competitions").html(str);

                        //add click listner

                        $('.edit-competition-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let img_source = $(this).attr('data-logo');

                            console.log('cliked : ' + id);
                            $('#competition_id').val(id);
                            $('#competition_name_en').val($(this).attr('data-name_en'));
                            $('#competition_name_ar').val($(this).attr('data-name_ar'));
                            $('#competition_description_en').val($(this).attr('data-description_en'));
                            $('#competition_description_ar').val($(this).attr('data-description_ar'));
                            $('#select_score_type').val($(this).attr('data-score_calc_type'));
                            $('#select_active_phase').val($(this).attr('data-active_phase'));


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
                            $('#competition_prize_report_header').summernote('code', $(this).attr('data-prize_report_header'));



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
                            $('#competition_prize_report_footer').summernote('code', $(this).attr('data-prize_report_footer'));
                            

                            // tinymce.get('competition_prize_report_header').setContent($(this).attr('data-prize_report_header'));
                            // tinymce.get('competition_prize_report_footer').setContent($(this).attr('data-prize_report_footer'));

                            // $('#competition_prize_report_header').val($(this).attr('data-prize_report_header'));
                            // $('#competition_prize_report_footer').val($(this).attr('data-prize_report_footer'));
                            $('#competition_prize_owner_name').val($(this).attr('data-prize_owner_name'));
                            $('#competition_prize_owner_description').val($(this).attr('data-prize_owner_description'));
                            $('#competition_prize_currency').val($(this).attr('data-prize_currency'));
                            $('#edit_logo').attr("src", img_source );
                            $('#edit-competition-title').html("Edit " + $(this).attr('data-name_en'));
                            $('#editCompetitionModal').modal('show');

                        });





                        $('.del-competition-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let csrf = '{{ csrf_token() }}';


                            console.log('cliked : ' + id);

                            if (confirm("Are you sure to remove " + $(this).attr('data-name') +
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

                        // var previous = "<button class='page-cliker " +
                        //         " ' id='cliker-" + "previous" + "' data-link='" + parseInt(current_page-1) + "' >" + "Prev" + "</button>  ";

                        // var next = "<button class='page-cliker " +
                        //         " ' id='cliker-" + "next" + "' data-link='" + parseInt(current_page+1) + "' >" + "Next" + "</button>  ";

                        var pgstr = "";
                        for (var i = 1; i <= Math.ceil(r.total / 25); i++) {
                            pgstr += "<button class='page-cliker " + (i == 1 ? "current-page" : "") +
                                " ' id='cliker-" + i + "' data-link='" + i + "' >" + i + "</button>  ";
                        }
                        // if(pgstr.split("</botton>").length == 1){
                        //     var pages = [5];
                            
                        //     pages[1] = "1";
                        //     pages[2] = "2";
                        //     pages[3] = "3";
                        //     pages[4] = "4";
                        //     pages[5] = "5";
                        
                        //     pgstr = pages[1]+pages[2]+pages[3]+pages[4]+pages[5];
                        //     $('#pages-container').html(previous + pgstr + next);
                        // }else{
                        // $('#pages-container').html(pgstr);
                        // }
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
