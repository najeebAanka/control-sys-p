<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">


        <li class="nav-item basic-nav-item">
            <p>
                <i class="bi bi-grid" style="margin-left: 20px;
                font-size: 20px;"></i>
                <span style="margin-left: 10px;
                font-size: 20px;">Dashboard</span>
            </p>
        </li><!-- End Dashboard Nav -->
        <hr>

        <!--<li class="nav-item basic-nav-item">-->
        <!--    <a class="nav-link collapsed" href="{{ url('dashboard/home') }}">-->
        <!--        <i class="bi bi-grid"></i>-->
        <!--        <span>Dashboard</span>-->
        <!--    </a>-->
        <!--</li>--><!-- End Dashboard Nav -->
        <!--<hr>-->

        {{-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Horses Management</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ url('dashboard/horses') }}" class="sub-nav-link">
                        <i class="bi bi-circle"></i><span>Horses</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('dashboard/registrations') }}" class="sub-nav-link">
                        <i class="bi bi-circle"></i><span>Horses Registrations</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('dashboard/points') }}" class="sub-nav-link">
                        <i class="bi bi-circle"></i><span>Horses Points</span>
                    </a>
                </li>

            </ul>
        </li><!-- End Components Nav --> --}}

        {{-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Competitions Management</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ url('dashboard/competitions') }}" class="sub-nav-link">
                        <i class="bi bi-circle"></i><span>Competitions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('dashboard/groups') }}" class="sub-nav-link">
                        <i class="bi bi-circle"></i><span>Competition Classes</span>
                    </a>
                </li>

            </ul>
        </li><!-- End Forms Nav --> --}}
        
        {{-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#champions-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Champions Management</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="champions-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ url('dashboard/champions-classes-management') }}" class="sub-nav-link">
                        <i class="bi bi-circle"></i><span>Champion Classes</span>
                    </a>
                </li>
                

            </ul>
        </li><!-- End Forms Nav --> --}}

        <li class="nav-heading">Pages</li>

        <li class="nav-item basic-nav-item">
            <a href="{{ url('dashboard/competitions') }}" class="nav-link collapsed">
                <i class="bi bi-journal-text"></i><span>Competitions</span>
            </a>
        </li>

        <li class="nav-item basic-nav-item">
            <a href="{{ url('dashboard/champions-classes-management') }}" class="nav-link collapsed">
                <i class="bi bi-journal"></i><span>Champion Classes</span>
            </a>
        </li>

        <li class="nav-item basic-nav-item">
            <a class="nav-link collapsed" href="{{ url('dashboard/categories') }}">
                <i class="bi bi-question-circle"></i>
                <span>Evaluation Categories</span>
            </a>
        </li><!-- End Profile Page Nav -->

        <li class="nav-item basic-nav-item">
            <a class="nav-link collapsed" href="{{ url('dashboard/judges') }}">
                <i class="bi bi-person"></i>
                <span>Judges</span>
            </a>
        </li><!-- End F.A.Q Page Nav -->



    </ul>


    

</aside>

<div style="position: relative;">
    <div data-autohide="false" style="position: fixed; top: 85%; left: 5%; right: 1; z-index:1000;"
        class="toast alert alert-success bg-success text-light border-0 " role="alert" aria-live="polite"
        aria-atomic="true" data-delay="3000" id="add-toast">
        <div role="alert" aria-live="assertive" aria-atomic="true">Added Successfully!</div>
    </div>
</div>
<div style="position: relative;">
    <div data-autohide="false" style="position: fixed; top: 85%; left: 5%; right: 1; z-index:1000;"
        class="toast alert alert-success bg-success text-light border-0 " role="alert" aria-live="polite"
        aria-atomic="true" data-delay="3000" id="delete-toast">
        <div role="alert" aria-live="assertive" aria-atomic="true">Deleted Successfully!</div>
    </div>
</div>
<div style="position: relative;">
    <div data-autohide="false" style="position: fixed; top: 85%; left: 5%; right: 1; z-index:1000;"
        class="toast alert alert-success bg-success text-light border-0 " role="alert" aria-live="polite"
        aria-atomic="true" data-delay="3000" id="edit-toast">
        <div role="alert" aria-live="assertive" aria-atomic="true">Updated Successfully!</div>
    </div>
</div>
