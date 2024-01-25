<!DOCTYPE html>
<html lang="en">

@include('dashboard.shared.css')

{{-- add new category modal start --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="add_category_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-light">

                    <div class="my-2">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Name" required>
                    </div>
                    <div class="my-2">
                        <label for="code">Code</label>
                        <input type="text" name="code" class="form-control" placeholder="Code" required>
                    </div>
                    <div class="row">
                        <div class="col-lg">
                            <label for="min_score">Min score</label>
                            <input type="number" step="0.5" name="min_score" class="form-control"
                                placeholder="Min score" required>
                        </div>
                        <div class="col-lg">
                            <label for="max_score">Max score</label>
                            <input type="number" step="0.5" name="max_score" class="form-control"
                                placeholder="Max score" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg">
                            <label for="normal_min_score">Normal min score</label>
                            <input type="number" step="0.5" name="normal_min_score" class="form-control"
                                placeholder="Normal min score" required>
                        </div>
                        <div class="col-lg">
                            <label for="normal_max_score">Normal max score</label>
                            <input type="number" step="0.5" name="normal_max_score" class="form-control"
                                placeholder="Normal max score" required>
                        </div>
                    </div>
                    {{-- <div class="my-2">
                        <label for="normal_min_score">Normal min score</label><output
                            style="margin-left: 10px; color:blue; font-weight: bold;">12</output>
                        <input type="range" name="normal_min_score" value="12" class="form-range" min="12"
                            max="20" step="0.5" oninput="this.previousElementSibling.value = this.value"
                            required>
                    </div>
                    <div class="my-2">
                        <label for="normal_max_score">Normal max score</label><output
                            style="margin-left: 10px; color:blue; font-weight: bold;">12</output>
                        <input type="range" name="normal_max_score" value="12" class="form-range" min="12"
                            max="20" step="0.5" oninput="this.previousElementSibling.value = this.value"
                            required>
                    </div> --}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add_category_btn" class="btn btn-primary">Add Category</button>
                    <div id="add_spinner" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- add new category modal end --}}


{{-- edit category modal start --}}
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="edit-cat-title" class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="edit_category_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="cat_id" value="-1">
                <div class="modal-body p-4 bg-light">

                    <div class="my-2">
                        <label for="name">Name</label>
                        <input type="text" id="cat_name" name="name" class="form-control" placeholder="Name"
                            required>
                    </div>
                    <div class="my-2">
                        <label for="code">Code</label>
                        <input type="text" id="cat_code" name="code" class="form-control" placeholder="Code"
                            required>
                    </div>
                    <div class="row">
                        <div class="col-lg">
                            <label for="min_score">Min score</label>
                            <input type="number" step="0.5" id="cat_min_score" name="min_score"
                                class="form-control" placeholder="Min score" required>
                        </div>
                        <div class="col-lg">
                            <label for="max_score">Max score</label>
                            <input type="number" step="0.5" id="cat_max_score" name="max_score"
                                class="form-control" placeholder="Max score" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg">
                            <label for="normal_min_score">Normal min score</label>
                            <input type="number" step="0.5" id="cat_normal_min_score" name="normal_min_score"
                                class="form-control" placeholder="Normal min score" required>
                        </div>
                        <div class="col-lg">
                            <label for="normal_max_score">Normal max score</label>
                            <input type="number" step="0.5" id="cat_normal_max_score" name="normal_max_score"
                                class="form-control" placeholder="Normal max score" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit_category_btn" class="btn btn-success">Update Category</button>
                    <div id="edit_spinner" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- edit category modal end --}}


<body>

    <!-- ======= Header ======= -->
    @include('dashboard.shared.top-nav')
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('dashboard.shared.side-nav')
    <!-- End Sidebar-->

    <main id="main" class="main">

        <!--<div class="pagetitle">-->
        <!--    <h1>Evaluation Categories</h1>-->
        <!--    <nav>-->
        <!--        <ol class="breadcrumb">-->
        <!--            <li class="breadcrumb-item"><a href="{{ url('dashboard/home') }}">Home</a></li>-->
        <!--            <li class="breadcrumb-item active">Evaluation Categories</li>-->
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
                                    <h3>Manage Evaluation Categories</h3>
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        <i class="bi-plus-circle me-2"></i>
                                        Add New Category
                                    </button>
                                </div>
                                <div class="card-body" id="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Code</th>
                                                    <th style="text-align: center;">Normal Min Score</th>
                                                    <th style="text-align: center;">Normal Max Score</th>
                                                    <th style="text-align: center;">Actions</th>

                                                </tr>
                                            </thead>
                                            <tbody id="show_all_categories">

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

            // add new category ajax request
            $("#add_category_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#add_spinner").css("display", "block");
                $("#add_category_btn").text('Adding...');
                $("#add_category_btn").attr('disabled', true);
                $.ajax({
                    url: server + "categories/store",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllCategories();
                        }
                        $("#add_category_btn").text('Add Category');
                        $("#add_category_btn").attr('disabled', false);
                        $("#add_category_form")[0].reset();
                        $("#addCategoryModal .btn-close").click();
                        $("#add_spinner").css("display", "none");
                        $('#add-toast').toast('show');
                    }
                });
            });

            $("#edit_category_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#edit_spinner").css("display", "block");
                $("#edit_category_btn").text('Updating...');
                $("#edit_category_btn").attr('disabled', true);
                $.ajax({
                    url: server + "categories/update",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllCategories();
                        }
                        $("#edit_category_btn").text('Update Category');
                        $("#edit_category_btn").attr('disabled', false);
                        $("#edit_category_form")[0].reset();
                        $("#editCategoryModal").modal('hide');
                        $("#edit_spinner").css("display", "none");
                        $('#edit-toast').toast('show');
                    },
                    error: (res) => {

                        $("#edit_category_btn").text('Update');
                        $("#edit_category_btn").attr('disabled', false);
                    }
                });
            });



            // fetch all categories ajax request

            function fetchAllCategories() {
                $.ajax({
                    url: server + "categories/fetchall?page=" + current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;
                        console.log(response.length)
                        var str = "";
                        for (var i = 0; i < response.length; i++) {
                            str += "<tr><td>" + response[i].id + "</td><td>" + response[i].name +
                                "</td><td>" + response[i].code +
                                "</td><td style='text-align : center'>" + response[i].normal_min_score +
                                "</td><td style='text-align : center'>" + response[i].normal_max_score +
                                "</td><td style='text-align : center'><button data-id='" + response[i]
                                .id +
                                "'  data-name='" + response[i].name +
                                "'  data-code='" + response[i].code +
                                "'  data-min_score='" + response[i].min_score +
                                "'  data-max_score='" + response[i].max_score +
                                "'  data-normal_min_score='" + response[i].normal_min_score +
                                "'  data-normal_max_score='" + response[i].normal_max_score +
                                "'  class='btn btn-success btn-sm mt-1 edit-cat-btn'>Edit <i class=\"bi-pencil-square \"></i></button><button data-id='" + response[i]
                                .id +
                                "'  data-name='" + response[i].name +
                                "' class='btn btn-sm btn-danger mt-1 del-cat-btn' style='margin-left: 4px;'>Remove <i class=\"bi-trash-fill \"></i></button></td></tr>";
                        }

                        $("#show_all_categories").html(str);

                        //add click listner


                        $('.edit-cat-btn').click(function() {


                            let id = $(this).attr('data-id');



                            console.log('cliked : ' + id);
                            $('#cat_id').val(id);
                            $('#cat_name').val($(this).attr('data-name'));
                            $('#cat_code').val($(this).attr('data-code'));
                            $('#cat_min_score').val($(this).attr('data-min_score'));
                            $('#cat_max_score').val($(this).attr('data-max_score'));
                            $('#cat_normal_min_score').val($(this).attr('data-normal_min_score'));
                            $('#cat_normal_max_score').val($(this).attr('data-normal_max_score'));
                            $('#edit-cat-title').html("Edit " + $(this).attr('data-name'));
                            $('#editCategoryModal').modal('show');

                        });





                        $('.del-cat-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let csrf = '{{ csrf_token() }}';


                            console.log('cliked : ' + id);

                            if (confirm("Are you sure to remove " + $(this).attr('data-name') + "?")) {

                                // call ajax to delete this cat
                                $(this).html("Deleting...");
                                $(this).prop("disabled", true);

                                $.ajax({
                                    url: server + "categories/delete",
                                    method: 'delete',
                                    data: {
                                        id: id,
                                        _token: csrf
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        fetchAllCategories();
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

            function fetchAllCategoriesPagesOnly() {
                $.ajax({
                    url: server + "categories/fetchall?page=" + current_page,
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


                            fetchAllCategories();

                        })
                        fetchAllCategories();

                    },
                    error: function(response) {
                        console.log("err " + response)

                    }
                });
            }

            console.log("calling .. ")
            fetchAllCategoriesPagesOnly();



        });
    </script>



</body>

</html>
