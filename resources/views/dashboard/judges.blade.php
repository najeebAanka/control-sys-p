<!DOCTYPE html>
<html lang="en">

@include('dashboard.shared.css')


{{-- add new user modal start --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Judge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="add_user_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-light">

                    <div class="my-2">
                        <label for="order_label">Order Label</label>
                        <select name="order_label" class="form-control">
                            @foreach (str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ') as $letter)
                                <option value="{{ $letter }}">{{ $letter }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="my-2">
                        <label for="gender">Gender</label>
                        <select name="gender" class="form-control" required>
                            <option value="male" selected>Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <div class="my-2">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Name" required>
                    </div>
                    <div class="my-2">
                        <label for="email">Email</label>
                        <input type="text" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="my-2">
                        <label for="country">Country</label>
                        @include('dashboard.shared.select-country', [
                            'select_id' => 'select-country-insert',
                        ])
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add_user_btn" class="btn btn-primary">Add Judge</button>
                    <div id="add_spinner" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- add new user modal end --}}


{{-- edit user modal start --}}
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="edit-user-title" class="modal-title">Edit Judge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="edit_user_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="user_id" value="-1">
                <div class="modal-body p-4 bg-light">

                    <div class="my-2">
                        <label for="order_label">Order Label</label>
                        <select id="user_order_label" name="order_label" class="form-control">
                            @foreach (str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ') as $letter)
                                <option value="{{ $letter }}">{{ $letter }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="my-2">
                        <label for="gender">Gender</label>
                        <select id="user_gender" name="gender" class="form-control" required>
                            <option value="male" selected>Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <div class="my-2">
                        <label for="name">Name</label>
                        <input type="text" id="user_name" name="name" class="form-control" placeholder="Name"
                            required>
                    </div>
                    <div class="my-2">
                        <label for="email">Email</label>
                        <input type="text" id="user_email" name="email" class="form-control"
                            placeholder="Email" required>
                    </div>
                    <div class="my-2">
                        <label for="password">Password</label>
                        <input type="password" id="user_password" name="password" class="form-control"
                            placeholder="Password" required>
                    </div>
                    <div class="my-2">
                        <label for="qr_code" style="margin-bottom: 4px">QR Code</label> <br />
                        <button type="button" id="new_qr_btn" onclick="make_qr(25)" class="btn btn-primary btn-sm"
                            style="margin: 10px;">Generate new QR</button>
                        <input type="hidden" id="user_qr_code" name="qr_code" class="form-control"
                            placeholder="QR Code" required>
                        <img id="qr-preview" style="margin: 10px;" />
                    </div>
                    <div class="my-2">
                        <label for="country">Country</label>
                        @include('dashboard.shared.select-country', [
                            'select_id' => 'select-country-edit',
                        ])
                    </div>
                    <div class="my-2">
                        <label for="status">Status</label>
                        <select id="user_status" name="status" class="form-control" required>
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit_user_btn" class="btn btn-success">Update Judge</button>
                    <div id="edit_spinner" class="spinner-border text-primary" role="status"
                        style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- edit user modal end --}}



<body>

    <!-- ======= Header ======= -->
    @include('dashboard.shared.top-nav')
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('dashboard.shared.side-nav')
    <!-- End Sidebar-->

    <main id="main" class="main">

        <!--<div class="pagetitle">-->
        <!--    <h1>Judges</h1>-->
        <!--    <nav>-->
        <!--        <ol class="breadcrumb">-->
        <!--            <li class="breadcrumb-item"><a href="{{ url('dashboard/home') }}">Home</a></li>-->
        <!--            <li class="breadcrumb-item active">Judges</li>-->
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
                                    <h3>Manage Judges</h3>
                                    <button class="btn btn-light" data-bs-toggle="modal"
                                        data-bs-target="#addUserModal">
                                        <i class="bi-plus-circle me-2"></i>
                                        Add New Judge
                                    </button>
                                </div>
                                <div class="card-body" id="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th style="text-align: center;">Status</th>
                                                    <th style="text-align: center;">QR Code</th>
                                                    <th style="text-align: center;">Country</th>
                                                    <th style="text-align: center;">Actions</th>

                                                </tr>
                                            </thead>
                                            <tbody id="show_all_users">

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
        function make_qr(length) {
            let result = '';
            const characters =
                'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            const charactersLength = characters.length;
            let counter = 0;
            while (counter < length) {
                result += characters.charAt(Math.floor(Math.random() *
                    charactersLength));
                counter += 1;
            }
            document.getElementById("user_qr_code").value = result;

            $('#qr-preview').attr('src',
                'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' +
                document.getElementById("user_qr_code").value);
        }
    </script>


    <script>
        var current_page = 1;

        $(function() {

            // add new user ajax request
            $("#add_user_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#add_spinner").css("display", "block");
                $("#add_user_btn").text('Adding...');
                $("#add_user_btn").attr('disabled', true);
                $.ajax({
                    url: server + "users/store",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllUsers();
                        }
                        $("#add_user_btn").text('Add Judge');
                        $("#add_user_btn").attr('disabled', false);
                        $("#add_user_form")[0].reset();
                        $("#addUserModal .btn-close").click();
                        $("#add_spinner").css("display", "none");
                        $('#add-toast').toast('show');
                    }
                });
            });

            $("#edit_user_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#edit_spinner").css("display", "block");
                $("#edit_user_btn").text('Updating...');
                $("#edit_user_btn").attr('disabled', true);
                $.ajax({
                    url: server + "users/update",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllUsers();
                        }
                        $("#edit_user_btn").text('Update Judge');
                        $("#edit_user_btn").attr('disabled', false);
                        $("#edit_user_form")[0].reset();
                        $("#editUserModal").modal('hide');
                        $("#edit_spinner").css("display", "none");
                        $('#edit-toast').toast('show');
                    },
                    error: (res) => {

                        $("#edit_user_btn").text('Update');
                        $("#edit_user_btn").attr('disabled', false);
                    }
                });
            });



            // fetch all users ajax request

            function fetchAllUsers() {
                $.ajax({
                    url: server + "users/fetchall?page=" + current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;
                        let res_gender = "";
                        let res_status = "";
                        
                        console.log(response.length)
                        var str = "";
                        for (var i = 0; i < response.length; i++) {

                            if(response[i].gender == "male") {res_gender = "Mr";} else if(response[i].gender == "female") {res_gender = "Mrs";}
                            
                            if(response[i].status == 1) {res_status = "Enabled";} else if(response[i].status == 0) {res_status = "Disabled";}

                            str += "<tr><td>" + response[i].id + "</td><td>" + response[i].order_label + "-" + res_gender + ". " + response[i].name +
                                "</td><td style='text-align : center'>" + res_status + "</td><td style='text-align : center'><a target=blank href='https://api.qrserver.com/v1/create-qr-code/?size=1024x1024&data=" +
                                response[i].qr_code +
                                "'><img src='https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=" +
                                response[i].qr_code +
                                "' /></a></td><td style='text-align : center'>" + response[i].country +
                                "</td><td style='text-align : center'><button data-id='" + response[i]
                                .id +
                                "'  data-name='" + response[i].name +
                                "'  data-email='" + response[i].email +
                                "'  data-gender='" + response[i].gender +
                                "'  data-order_label='" + response[i].order_label +
                                "'  data-password='" + response[i].password +
                                "'  data-qr_code='" + response[i].qr_code +
                                "'  data-country='" + response[i].country +
                                "'  data-status='" + response[i].status +
                                "'  class='btn btn-success btn-sm mt-1 edit-user-btn'>Edit <i class=\"bi-pencil-square \"></i></button><button data-id='" +
                                response[i]
                                .id +
                                "'  data-name='" + response[i].name +
                                "' class='btn btn-sm btn-danger mt-1 del-user-btn' style='margin-left: 4px;'>Remove <i class=\"bi-trash-fill \"></i></button></td></tr>";
                        }

                        $("#show_all_users").html(str);

                        //add click listner


                        $('.edit-user-btn').click(function() {


                            let id = $(this).attr('data-id');



                            console.log('cliked : ' + id);
                            $('#user_id').val(id);
                            $('#user_name').val($(this).attr('data-name'));
                            $('#user_email').val($(this).attr('data-email'));
                            $('#user_order_label').val($(this).attr('data-order_label'));
                            $('#user_gender').val($(this).attr('data-gender'));
                            $('#user_password').val($(this).attr('data-password'));
                            $('#user_qr_code').val($(this).attr('data-qr_code'));
                            $('#qr-preview').attr('src',
                                'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' +
                                $(this).attr('data-qr_code'));
                            $('#user_status').val($(this).attr('data-status'));
                            $('#edit-user-title').html("Edit " + $(this).attr('data-name'));
                            $('#select-country-edit').val($(this).attr('data-country'))
                            $('#editUserModal').modal('show');

                        });



                        $('.del-user-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let csrf = '{{ csrf_token() }}';


                            console.log('cliked : ' + id);

                            if (confirm("Are you sure to remove " + $(this).attr('data-name') +
                                    "?")) {

                                // call ajax to delete this user
                                $(this).html("Deleting...");
                                $(this).prop("disabled", true);

                                $.ajax({
                                    url: server + "users/delete",
                                    method: 'delete',
                                    data: {
                                        id: id,
                                        _token: csrf
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        fetchAllUsers();
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


            function fetchAllUsersPagesOnly() {
                $.ajax({
                    url: server + "users/fetchall?page=" + current_page,
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


                            fetchAllUsers();

                        })
                        fetchAllUsers();

                    },
                    error: function(response) {
                        console.log("err " + response)

                    }
                });
            }

            console.log("calling .. ")
            fetchAllUsersPagesOnly();



        });
    </script>



</body>

</html>
