<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Dashboard | Hyper - Responsive Bootstrap 5 Admin Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
        <meta content="Coderthemes" name="author">
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- third party css -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

        <link href="{{asset('assets/css/vendor/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css">
        <!-- third party css end -->

        <!-- App css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="light-style">
        {{-- <link href="{{asset('assets/css/app-dark.min.css')}}" rel="stylesheet" type="text/css" id="dark-style"> --}}



        @yield('styles')

        <style>
            /*****************************/
/* MESSAGE BOX RELATED CLASS */
/*          (START)          */
/*****************************/
.msgbox-area {
  max-height: 100%;
  position: fixed;
  bottom: 15px;
  left: 20px;
  right: 20px;
}
.msgbox-area .msgbox-box {
  font-size: inherit;
  color: #ffffff;
  background-color: rgba(0, 0, 0, 0.8);
  padding: 18px 20px;
  margin: 0 0 15px;
  display: flex;
  align-items: center;
  position: relative;
  border-radius: 12px;
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.65);
  transition: opacity 300ms ease-in;
}
.msgbox-area .msgbox-box.msgbox-box-hide {
  opacity: 0;
}
.msgbox-area .msgbox-box:last-child {
  margin: 60px 0;
}
.msgbox-area .msgbox-content {
  flex-shrink: 1;
}
.msgbox-area .msgbox-close {
  color: #ffffff;
  font-weight: bold;
  text-decoration: none;
  margin: 0 0 0 20px;
  flex-grow: 0;
  flex-shrink: 0;
  position: relative;
  transition: text-shadow 225ms ease-out;
}
.msgbox-area .msgbox-close:hover {
  text-shadow: 0 0 3px #efefef;
}

@media (min-width: 481px) and (max-width: 767px) {
  .msgbox-area {
    left: 80px;
    right: 80px;
  }
}
@media (min-width: 768px) {
  .msgbox-area {
    width: 480px;
    height: 0;
    top: 15px;
    left: auto;
    right: 15px;
  }
}
/*****************************/
/* MESSAGE BOX RELATED CLASS */
/*           (END)           */
/*****************************/


.msgbox-area {
  font-size: 16px;
}
        </style>
    </head>

    <body class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
        <!-- Begin page -->
        <div class="wrapper"  >
            <!-- ========== Left Sidebar Start ========== -->
             @include('dashboard.layouts._sidebar')
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content" id="dashboard">
                    <!-- Topbar Start -->

                        @include('dashboard.layouts._topbar')

                    <!-- end Topbar -->

                    <!-- Start Content-->
                    <div class="container-fluid">

                        @yield('content')

                    </div>

                    <!-- container -->
                 <demande-notification :user="{{Auth::user()}}"></demande-notification>
                </div>
                <!-- content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <script>document.write(new Date().getFullYear())</script> © Hyper - Coderthemes.com
                            </div>
                            <div class="col-md-6">
                                <div class="text-md-end footer-links d-none d-md-block">
                                    <a href="javascript: void(0);">About</a>
                                    <a href="javascript: void(0);">Support</a>
                                    <a href="javascript: void(0);">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->



        <div class="rightbar-overlay"></div>
        <!-- /End-bar -->

        <!-- bundle -->
        <script src="{{asset('assets/js/vendor.min.js')}}"></script>
        <script src="{{asset('assets/js/app.min.js')}}"></script>

        <!-- third party js -->
        <script src="{{asset('assets/js/vendor/apexcharts.min.js')}}"></script>
        <script src="{{asset('assets/js/vendor/jquery-jvectormap-1.2.2.min.js')}}"></script>
        <script src="{{asset('assets/js/vendor/jquery-jvectormap-world-mill-en.js')}}"></script>
        <!-- third party js ends -->

        <!-- demo app -->
        <script src="{{asset('assets/js/pages/demo.dashboard.js')}}"></script>
        <!-- end demo js-->

            <!-- bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

        <script src="{{asset('js/app.js')}}"></script>

        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

           @yield('scripts')




    </body>

    @jquery
@toastr_js
@toastr_render
</html>
