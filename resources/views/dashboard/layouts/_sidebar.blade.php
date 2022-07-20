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
                <a  href="{{route('dashboard.clients.index')}}"   class="side-nav-link">
                    <i class="uil-users-alt"></i>
                    <span> Clients </span>
                </a>

            </li>

            <li class="side-nav-item">
                <a  href="{{route('dashboard.services.index')}}"  class="side-nav-link">
                    <i class="mdi mdi-shape-plus"></i>
                    <span> Services </span>
                </a>

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
                            <a href="{{route('dashboard.cars.index')}}">All Cars</a>
                        </li>
                        <li>
                            <a href="{{route('dashboard.carForSale')}}">Cars for sell</a>
                        </li>


                    </ul>
                </div>
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
                            <a href="{{route('dashboard.new.demandes.index')}}">New Demandes</a>
                        </li>
                        <li>
                            <a href="{{route('dashboard.all.demandes')}}">All Demandes</a>
                        </li>
                        <li>

                        </li>

                    </ul>
                </div>
            </li>


            <li class="side-nav-item">
                <a  href="{{route('dashboard.mechanics.index')}}"  aria-controls="sidebarDashboards" class="side-nav-link">
                    <i class="dripicons-gear "></i>
                    <span> Mechanics </span>
                </a>

            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarProducts" aria-expanded="false" aria-controls="sidebarCars" class="side-nav-link">
                    <i class="uil uil-cart"></i>
                    <span> Products </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarProducts">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{route('dashboard.products.index')}}">Products</a>
                        </li>
                        <li>
                            <a href="{{route('dashboard.products.trashed')}}">Product out of stock</a>
                        </li>


                    </ul>
                </div>
            </li>





            <li class="side-nav-item">
                <a href="{{route('dashboard.order.index')}}" class="side-nav-link">
                    <i class="uil uil-bill"></i>
                    <span> Orders </span>
                </a>

            </li>















        </ul>


        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>

<!-- Left Sidebar End -->
