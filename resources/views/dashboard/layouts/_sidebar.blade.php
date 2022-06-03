<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">

    <!-- LOGO -->
    <a href="index.html" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="{{asset('assets/images/logo.png')}}" alt="" height="16">
        </span>
        <span class="logo-sm">
            <img src="{{asset('assets/images/logo_sm.png')}}" alt="" height="16">
        </span>
    </a>



    <div class="h-100" id="leftside-menu-container" data-simplebar="">

        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title side-nav-item">Navigation</li>

            <li class="side-nav-item">
                <a href="{{url('/dashboard')}}"  aria-controls="sidebarDashboards" class="side-nav-link">
                    <i class="uil-home-alt"></i>
                    <span> Dashboards </span>
                </a>

            </li>


            <li class="side-nav-item">
                <a  href="{{route('clients.index')}}"   class="side-nav-link">
                    <i class="uil-users-alt"></i>
                    <span> Clients </span>
                </a>

            </li>

            <li class="side-nav-item">
                <a  href="{{route('services.index')}}"  class="side-nav-link">
                    <i class="mdi mdi-shape-plus"></i>
                    <span> Services </span>
                </a>

            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarDashboards" aria-expanded="false" aria-controls="sidebarDashboards" class="side-nav-link">
                    <i class="uil uil-file-plus"></i>
                    <span> Demandes </span>
                </a>

            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarDashboards" aria-expanded="false" aria-controls="sidebarDashboards" class="side-nav-link">
                    <i class="uil uil-car-sideview"></i>
                    <span> Cars </span>
                </a>

            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarDashboards" aria-expanded="false" aria-controls="sidebarDashboards" class="side-nav-link">
                    <i class="uil uil-car-sideview"></i>
                    <span> Cars for sell </span>
                </a>

            </li>


            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarDashboards" aria-expanded="false" aria-controls="sidebarDashboards" class="side-nav-link">
                    <i class="uil uil-cart"></i>
                    <span> Products </span>
                </a>

            </li>


            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarDashboards" aria-expanded="false" aria-controls="sidebarDashboards" class="side-nav-link">
                    <i class="uil uil-bill"></i>
                    <span> Orders </span>
                </a>

            </li>



            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarDashboards" aria-expanded="false" aria-controls="sidebarDashboards" class="side-nav-link">
                    <i class="dripicons-gear "></i>
                    <span> mechanic </span>
                </a>

            </li>


            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarDashboards" aria-expanded="false" aria-controls="sidebarDashboards" class="side-nav-link">
                    <i class="uil uil-tag-alt"></i>
                    <span> Stock </span>
                </a>

            </li>








        </ul>


        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>

<!-- Left Sidebar End -->
