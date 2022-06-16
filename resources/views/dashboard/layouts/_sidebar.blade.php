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
                <a data-bs-toggle="collapse" href="#sidebarEcommerce" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                    <i class="uil-store"></i>
                    <span> Demandes </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarEcommerce">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="">All Demandes</a>
                        </li>
                        <li>
                            <a href="">New Demandes</a>
                        </li>
                        <li>
                            <a href="">Historique</a>
                        </li>

                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarCars" aria-expanded="false" aria-controls="sidebarCars" class="side-nav-link">
                    <i class="uil-car"></i>
                    <span> Cars </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarCars">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="">All Cars</a>
                        </li>
                        <li>
                            <a href="">Cars for sell</a>
                        </li>
                        <li>
                            <a href="">Marks</a>
                        </li>
                        <li>
                            <a href="">Models</a>
                        </li>
                        <li>
                            <a href="">Carbirant</a>
                        </li>

                    </ul>
                </div>
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
