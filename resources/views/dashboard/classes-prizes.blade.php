<!DOCTYPE html>
<html lang="en">

<?php
$cmp_id = App\Models\Competition::find(Route::input('comp'));
$cls_id = \App\Models\ChampionClass::find(Route::input('class'));
?>

@include('dashboard.shared.css')

<body>

    <!-- ======= Header ======= -->
    @include('dashboard.shared.top-nav')
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('dashboard.shared.side-nav')
    <!-- End Sidebar-->

    <main id="main" class="main">

        <!--<div class="pagetitle">-->
        <!--    <h1>Class Prizes</h1>-->
        <!--    <nav>-->
        <!--        <ol class="breadcrumb">-->
        <!--            <li class="breadcrumb-item"><a href="{{ url('dashboard/home') }}">Home</a></li>-->
        <!--            <li class="breadcrumb-item active">Class Prizes</li>-->
        <!--        </ol>-->
        <!--    </nav>-->
        <!--</div>-->
        <!-- End Page Title -->
        @include('dashboard.shared.message')
        <section class="section dashboard">
            <div class="row">
                <div class="mb-2">
                    <a class="btn btn-warning" target="blank"
                        href="{{ url('dashboard/owners-champion-qualifiers/' . $cmp_id->id) }}">Open winner owners
                        list</a>

                </div>



                <div class="col-md-6">

                    <h3>Qualification classes prizes</h3>
                    <hr />


                    {{-- add new prize modal start --}}
                    <div class="modal fade" id="addPrizeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        data-bs-backdrop="static" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add New Prize To Top 10</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form method="post" id="add_prize_form" enctype="multipart/form-data"
                                    action="{{ url('backend/class-prizes/add') }}">
                                    @csrf
                                    <div class="modal-body p-4 bg-light">



                                        <input type="hidden" name="competition_id" value="<?php echo $cmp_id->id; ?>">
                                        <input type="hidden" name="class_id" value="-1">
                                        <input type="hidden" name="target_type" value="all-classes">

                                        <div class="my-2">
                                            <label for="rank">Rank</label>
                                            <select name="rank" class="form-control" required>

                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>

                                            </select>
                                        </div>
                                        <div class="my-2">
                                            <label for="amount">Amount</label>
                                            <input type="number" step="0.001" name="amount" class="form-control"
                                                placeholder="Amount" required>
                                        </div>

                                        <div class="my-2">
                                            <label for="discount_fee">Discount Fee</label>
                                            <input type="number" step="0.001" name="discount_fee"
                                                class="form-control" placeholder="Discount Fee" required>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" id="add_prize_btn" class="btn btn-primary">Add
                                            Prize</button>
                                        <div id="add_spinner" class="spinner-border text-primary" role="status"
                                            style="display: none;">
                                            <span class="visually-hidden"></span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- add new prize modal end --}}



                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3>Top 10</h3>
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addPrizeModal">
                                <i class="bi-plus-circle me-2"></i>
                                Add New Prize
                            </button>
                        </div>
                        <div class="card-body" id="">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            {{-- <th style="text-align: center">ID</th> --}}
                                            <th style="text-align: center;">Rank</th>
                                            <th style="text-align: center;">Amount</th>
                                            <th style="text-align: center;">Discount</th>

                                            <th style="text-align: center;">Actions</th>

                                        </tr>
                                    </thead>
                                    <tbody id="show_all_prizes">

                                        <?php $data = \App\Models\ClassPrize::where('class_id', -1)->where('competition_id', $cmp_id->id)->orderBy('rank','asc')->get();
                    foreach ($data as $d) {
                        
                    ?>
                                        <tr>
                                            {{-- <td style="text-align: center"><?php echo $d->id; ?></td> --}}
                                            <td style="text-align: center"><?php echo $d->rank; ?></td>
                                            <td style="text-align: center"><?php echo $d->amount; ?></td>
                                            <td style="text-align: center"><?php echo $d->discount_fee; ?></td>
                                            <td style="text-align: center">

                                                <button class="btn btn-success btn-sm m-r-5" data-bs-toggle="modal"
                                                    data-bs-target="#editPrizeModal<?php echo $d->id; ?>"
                                                    style="margin-bottom: 3px">
                                                    <i class="bi-pencil-square"></i></button>



                                                <form method="post"
                                                    action="{{ url('backend/class-prizes/delete') }}">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="id"
                                                        value="<?php echo $d->id; ?>" />
                                                    <button class="btn btn-danger btn-sm" type="submit"
                                                        onclick="return confirm('Are you sure to remove ' + 'Rank (<?php echo $d->rank; ?>) in Top 10' +' ?')"
                                                        data-toggle="tooltip" data-original-title="Delete">
                                                        <i class="bi-trash-fill"></i></button>
                                                </form>







                                                {{-- edit prize modal start --}}
                                                <div class="modal fade" id="editPrizeModal<?php echo $d->id; ?>"
                                                    tabindex="-1" aria-labelledby="exampleModalLabel"
                                                    data-bs-backdrop="static" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 id="edit-prize-title" class="modal-title">Edit
                                                                    Prize For Rank (<?php echo $d->rank; ?>) in Top 10
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ url('backend/class-prizes/update') }}"
                                                                method="POST" id="edit_prize_form"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" id="prize_id"
                                                                    value="<?php echo $d->id; ?>">
                                                                <div class="modal-body p-4 bg-light">

                                                                    <input type="hidden" name="competition_id"
                                                                        value="<?php echo $cmp_id->id; ?>">
                                                                    <input type="hidden" name="class_id"
                                                                        value="-1">
                                                                    <input type="hidden" name="target_type"
                                                                        value="all-classes">

                                                                    {{-- <div class="my-2">
                                                    <label for="rank" style="
                                                    float: left;
                                                    ">Rank</label>
                                                    <select id="prize_rank"
                                                        name="rank"
                                                        class="form-control" required>

                                                        <option value="1"
                                                            <?php if ($d->rank == 1) {
                                                                echo 'selected';
                                                            } ?>>Gold
                                                        </option>
                                                        <option value="2"
                                                            <?php if ($d->rank == 2) {
                                                                echo 'selected';
                                                            } ?>>Silver
                                                        </option>
                                                        <option value="3"
                                                            <?php if ($d->rank == 3) {
                                                                echo 'selected';
                                                            } ?>>Bronze
                                                        </option>

                                                    </select>
                                                </div> --}}
                                                                    <div class="my-2">
                                                                        <label for="amount"
                                                                            style="
                                                        float: left;
                                                    ">Amount</label>
                                                                        <input id="prize_amount" type="number"
                                                                            step="0.001" name="amount"
                                                                            value="<?php echo $d->amount; ?>"
                                                                            class="form-control" placeholder="Amount"
                                                                            required>
                                                                    </div>
                                                                    <div class="my-2">
                                                                        <label for="discount_fee"
                                                                            style="
                                                        float: left;
                                                    ">Discount
                                                                            Fee</label>
                                                                        <input id="prize_discount_fee" type="number"
                                                                            step="0.001" name="discount_fee"
                                                                            value="<?php echo $d->discount_fee; ?>"
                                                                            class="form-control"
                                                                            placeholder="Discount Fee" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" id="edit_prize_btn"
                                                                        class="btn btn-success">Update
                                                                        Prize</button>
                                                                    <div id="edit_spinner"
                                                                        class="spinner-border text-primary"
                                                                        role="status" style="display: none;">
                                                                        <span class="visually-hidden"></span>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- edit prize modal end --}}



                                            </td>
                                        </tr>
                                        <?php }?>

                                    </tbody>
                                </table>
                            </div>
                            <div id="pages-container"></div>

                        </div>
                    </div>


                
                








                    <div class="">
                        <h2>Foal classes prizes</h2>
                        <hr />
    
                        @foreach (\App\Models\CompetitionGroup::where('group_type', 'foal')->get() as $cls_id)
                            {{-- add new prize modal start --}}
                            <div class="modal fade" id="addPrizeModal<?php echo $cls_id->id; ?>" tabindex="-1"
                                aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add New Prize to
                                                <?php echo $cls_id->name_en; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="post" id="add_prize_form" enctype="multipart/form-data"
                                            action="{{ url('backend/class-prizes/add') }}">
                                            @csrf
                                            <div class="modal-body p-4 bg-light">
    
    
    
                                                <input type="hidden" name="competition_id" value="<?php echo $cmp_id->id; ?>">
                                                <input type="hidden" name="class_id" value="<?php echo $cls_id->id; ?>">
                                                <input type="hidden" name="target_type" value="foal-class">
    
                                                <div class="my-2">
                                                    <label for="rank">Rank</label>
                                                    <select name="rank" class="form-control" required>
    
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
    
                                                    </select>
                                                </div>
                                                <div class="my-2">
                                                    <label for="amount">Amount</label>
                                                    <input type="number" step="0.001" name="amount"
                                                        class="form-control" placeholder="Amount" required>
                                                </div>
    
                                                <div class="my-2">
                                                    <label for="discount_fee">Discount Fee</label>
                                                    <input type="number" step="0.001" name="discount_fee"
                                                        class="form-control" placeholder="Discount Fee" required>
                                                </div>
    
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" id="add_prize_btn" class="btn btn-primary">Add
                                                    Prize</button>
                                                <div id="add_spinner" class="spinner-border text-primary" role="status"
                                                    style="display: none;">
                                                    <span class="visually-hidden"></span>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- add new prize modal end --}}
    
    
    
    
                            <div class="card shadow">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3><?php echo $cls_id->name_en; ?></h3>
                                    <button class="btn btn-light" data-bs-toggle="modal"
                                        data-bs-target="#addPrizeModal<?php echo $cls_id->id; ?>">
                                        <i class="bi-plus-circle me-2"></i>
                                        Add New Prize
                                    </button>
                                </div>
                                <div class="card-body" id="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    {{-- <th style="text-align: center">ID</th> --}}
                                                    <th style="text-align: center;">Rank</th>
                                                    <th style="text-align: center;">Amount</th>
                                                    <th style="text-align: center;">Discount</th>
    
                                                    <th style="text-align: center;">Actions</th>
    
                                                </tr>
                                            </thead>
                                            <tbody id="show_all_prizes">
    
                                                <?php $data = \App\Models\ClassPrize::where('class_id', $cls_id->id)->where('competition_id', $cmp_id->id)->orderBy('rank','asc')->get();
                                                        foreach ($data as $d) {
                                                            
                                                        ?>
                                                <tr>
                                                    {{-- <td style="text-align: center"><?php echo $d->id; ?></td> --}}
                                                    <td style="text-align: center"><?php echo $d->rank; ?></td>
                                                    <td style="text-align: center"><?php echo $d->amount; ?></td>
                                                    <td style="text-align: center"><?php echo $d->discount_fee; ?></td>
                                                    <td style="text-align: center">
    
                                                        <button class="btn btn-success btn-sm m-r-5"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editPrizeModal<?php echo $d->id; ?>"
                                                            style="margin-bottom: 3px">
                                                            <i class="bi-pencil-square"></i></button>
    
    
    
                                                        <form method="post"
                                                            action="{{ url('backend/class-prizes/delete') }}">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="id"
                                                                value="<?php echo $d->id; ?>" />
                                                            <button class="btn btn-danger btn-sm" type="submit"
                                                                onclick="return confirm('Are you sure to remove Rank('+'<?php echo $d->rank; ?>) in <?php echo $cls_id->name_en; ?>' + ' ?')"
                                                                data-toggle="tooltip" data-original-title="Delete">
                                                                <i class="bi-trash-fill"></i></button>
                                                        </form>
    
    
    
    
                                                        {{-- edit prize modal start --}}
                                                        <div class="modal fade" id="editPrizeModal<?php echo $d->id; ?>"
                                                            tabindex="-1" aria-labelledby="exampleModalLabel"
                                                            data-bs-backdrop="static" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 id="edit-prize-title" class="modal-title">Edit
                                                                            Prize For Rank(<?php echo $d->rank; ?>) in
                                                                            <?php echo $cls_id->name_en; ?>
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <form action="{{ url('backend/class-prizes/update') }}"
                                                                        method="POST" id="edit_prize_form"
                                                                        enctype="multipart/form-data">
                                                                        @csrf
                                                                        <input type="hidden" name="id"
                                                                            id="prize_id" value="<?php echo $d->id; ?>">
                                                                        <div class="modal-body p-4 bg-light">
    
                                                                            <input type="hidden" name="competition_id"
                                                                                value="<?php echo $cmp_id->id; ?>">
                                                                            <input type="hidden" name="class_id"
                                                                                value="<?php echo $cls_id->id; ?>">
                                                                            <input type="hidden" name="target_type"
                                                                                value="foal-class">
    
                                                                        
                                                                            <div class="my-2">
                                                                                <label for="amount"
                                                                                    style="
                                                                                            float: left;
                                                                                        ">Amount</label>
                                                                                <input id="prize_amount" type="number"
                                                                                    step="0.001" name="amount"
                                                                                    value="<?php echo $d->amount; ?>"
                                                                                    class="form-control"
                                                                                    placeholder="Amount" required>
                                                                            </div>
                                                                            <div class="my-2">
                                                                                <label for="discount_fee"
                                                                                    style="
                                                                                            float: left;
                                                                                        ">Discount
                                                                                    Fee</label>
                                                                                <input id="prize_discount_fee"
                                                                                    type="number" step="0.001"
                                                                                    name="discount_fee"
                                                                                    value="<?php echo $d->discount_fee; ?>"
                                                                                    class="form-control"
                                                                                    placeholder="Discount Fee" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Close</button>
                                                                            <button type="submit" id="edit_prize_btn"
                                                                                class="btn btn-success">Update
                                                                                Prize</button>
                                                                            <div id="edit_spinner"
                                                                                class="spinner-border text-primary"
                                                                                role="status" style="display: none;">
                                                                                <span class="visually-hidden"></span>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- edit prize modal end --}}
    
    
    
                                                    </td>
                                                </tr>
                                                <?php }?>
    
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="pages-container"></div>
    
                                </div>
                            </div>
                        @endforeach
                    </div>











                
                
                
                
                
                </div>


                <div class="col-md-6">
                    <h3>Champion classes prizes</h3>
                    <hr />

                    @foreach (\App\Models\ChampionClass::get() as $cls_id)
                        {{-- add new prize modal start --}}
                        <div class="modal fade" id="addPrizeModal<?php echo $cls_id->id; ?>" tabindex="-1"
                            aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add New Prize to
                                            <?php echo $cls_id->name_en; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form method="post" id="add_prize_form" enctype="multipart/form-data"
                                        action="{{ url('backend/class-prizes/add') }}">
                                        @csrf
                                        <div class="modal-body p-4 bg-light">



                                            <input type="hidden" name="competition_id" value="<?php echo $cmp_id->id; ?>">
                                            <input type="hidden" name="class_id" value="<?php echo $cls_id->id; ?>">
                                            <input type="hidden" name="target_type" value="champion-class">

                                            <div class="my-2">
                                                <label for="rank">Rank</label>
                                                <select name="rank" class="form-control" required>

                                                    <option value="1">Gold</option>
                                                    <option value="2">Silver</option>
                                                    <option value="3">Bronze</option>

                                                </select>
                                            </div>
                                            <div class="my-2">
                                                <label for="amount">Amount</label>
                                                <input type="number" step="0.001" name="amount"
                                                    class="form-control" placeholder="Amount" required>
                                            </div>

                                            <div class="my-2">
                                                <label for="discount_fee">Discount Fee</label>
                                                <input type="number" step="0.001" name="discount_fee"
                                                    class="form-control" placeholder="Discount Fee" required>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" id="add_prize_btn" class="btn btn-primary">Add
                                                Prize</button>
                                            <div id="add_spinner" class="spinner-border text-primary" role="status"
                                                style="display: none;">
                                                <span class="visually-hidden"></span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- add new prize modal end --}}




                        <div class="card shadow">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3><?php echo $cls_id->name_en; ?></h3>
                                <button class="btn btn-light" data-bs-toggle="modal"
                                    data-bs-target="#addPrizeModal<?php echo $cls_id->id; ?>">
                                    <i class="bi-plus-circle me-2"></i>
                                    Add New Prize
                                </button>
                            </div>
                            <div class="card-body" id="">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                {{-- <th style="text-align: center">ID</th> --}}
                                                <th style="text-align: center;">Rank</th>
                                                <th style="text-align: center;">Amount</th>
                                                <th style="text-align: center;">Discount</th>

                                                <th style="text-align: center;">Actions</th>

                                            </tr>
                                        </thead>
                                        <tbody id="show_all_prizes">

                                            <?php $data = \App\Models\ClassPrize::where('class_id', $cls_id->id)->where('competition_id', $cmp_id->id)->orderBy('rank','asc')->get();
                                                    foreach ($data as $d) {
                                                        
                                                    ?>
                                            <tr>
                                                {{-- <td style="text-align: center"><?php echo $d->id; ?></td> --}}
                                                <td style="text-align: center"><?php if ($d->rank == 1) {
                                                    echo 'Gold';
                                                } elseif ($d->rank == 2) {
                                                    echo 'Silver';
                                                } elseif ($d->rank == 3) {
                                                    echo 'Bronze';
                                                } ?></td>
                                                <td style="text-align: center"><?php echo $d->amount; ?></td>
                                                <td style="text-align: center"><?php echo $d->discount_fee; ?></td>
                                                <td style="text-align: center">

                                                    <button class="btn btn-success btn-sm m-r-5"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editPrizeModal<?php echo $d->id; ?>"
                                                        style="margin-bottom: 3px">
                                                        <i class="bi-pencil-square"></i></button>



                                                    <form method="post"
                                                        action="{{ url('backend/class-prizes/delete') }}">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="id"
                                                            value="<?php echo $d->id; ?>" />
                                                        <button class="btn btn-danger btn-sm" type="submit"
                                                            onclick="return confirm('Are you sure to remove '+'<?php if ($d->rank == 1) {
                                                                echo 'Gold';
                                                            } elseif ($d->rank == 2) {
                                                                echo 'Silver';
                                                            } elseif ($d->rank == 3) {
                                                                echo 'Bronze';
                                                            } ?> Rank in <?php echo $cls_id->name_en; ?>' + ' ?')"
                                                            data-toggle="tooltip" data-original-title="Delete">
                                                            <i class="bi-trash-fill"></i></button>
                                                    </form>







                                                    {{-- edit prize modal start --}}
                                                    <div class="modal fade" id="editPrizeModal<?php echo $d->id; ?>"
                                                        tabindex="-1" aria-labelledby="exampleModalLabel"
                                                        data-bs-backdrop="static" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 id="edit-prize-title" class="modal-title">Edit
                                                                        Prize For <?php if ($d->rank == 1) {
                                                                            echo 'Gold';
                                                                        } elseif ($d->rank == 2) {
                                                                            echo 'Silver';
                                                                        } elseif ($d->rank == 3) {
                                                                            echo 'Bronze';
                                                                        } ?> Rank in
                                                                        <?php echo $cls_id->name_en; ?>
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ url('backend/class-prizes/update') }}"
                                                                    method="POST" id="edit_prize_form"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="hidden" name="id"
                                                                        id="prize_id" value="<?php echo $d->id; ?>">
                                                                    <div class="modal-body p-4 bg-light">

                                                                        <input type="hidden" name="competition_id"
                                                                            value="<?php echo $cmp_id->id; ?>">
                                                                        <input type="hidden" name="class_id"
                                                                            value="<?php echo $cls_id->id; ?>">
                                                                        <input type="hidden" name="target_type"
                                                                            value="champion-class">

                                                                        {{-- <div class="my-2">
                                                                                    <label for="rank" style="
                                                                                    float: left;
                                                                                    ">Rank</label>
                                                                                    <select id="prize_rank"
                                                                                        name="rank"
                                                                                        class="form-control" required>

                                                                                        <option value="1"
                                                                                            <?php if ($d->rank == 1) {
                                                                                                echo 'selected';
                                                                                            } ?>>Gold
                                                                                        </option>
                                                                                        <option value="2"
                                                                                            <?php if ($d->rank == 2) {
                                                                                                echo 'selected';
                                                                                            } ?>>Silver
                                                                                        </option>
                                                                                        <option value="3"
                                                                                            <?php if ($d->rank == 3) {
                                                                                                echo 'selected';
                                                                                            } ?>>Bronze
                                                                                        </option>

                                                                                    </select>
                                                                                </div> --}}
                                                                        <div class="my-2">
                                                                            <label for="amount"
                                                                                style="
                                                                                        float: left;
                                                                                    ">Amount</label>
                                                                            <input id="prize_amount" type="number"
                                                                                step="0.001" name="amount"
                                                                                value="<?php echo $d->amount; ?>"
                                                                                class="form-control"
                                                                                placeholder="Amount" required>
                                                                        </div>
                                                                        <div class="my-2">
                                                                            <label for="discount_fee"
                                                                                style="
                                                                                        float: left;
                                                                                    ">Discount
                                                                                Fee</label>
                                                                            <input id="prize_discount_fee"
                                                                                type="number" step="0.001"
                                                                                name="discount_fee"
                                                                                value="<?php echo $d->discount_fee; ?>"
                                                                                class="form-control"
                                                                                placeholder="Discount Fee" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                        <button type="submit" id="edit_prize_btn"
                                                                            class="btn btn-success">Update
                                                                            Prize</button>
                                                                        <div id="edit_spinner"
                                                                            class="spinner-border text-primary"
                                                                            role="status" style="display: none;">
                                                                            <span class="visually-hidden"></span>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- edit prize modal end --}}



                                                </td>
                                            </tr>
                                            <?php }?>

                                        </tbody>
                                    </table>
                                </div>
                                <div id="pages-container"></div>

                            </div>
                        </div>
                    @endforeach
                </div>




            </div>



        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    @include('dashboard.shared.footer')
    <!-- End Footer -->

    @include('dashboard.shared.js')

</body>

</html>
