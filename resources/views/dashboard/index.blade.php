@extends('dashboard.layouts.app')


@section('styles')

<link href="{{asset('assets/css/vendor/dataTables.bootstrap5.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/vendor/responsive.bootstrap5.css')}}" rel="stylesheet" type="text/css">

<style>

#dashboard-card .card-box {
    position: relative;
    color: #fff;
    padding: 20px 10px 40px;
    margin: 20px 0px;
}
#dashboard-card .card-box:hover {
    text-decoration: none;
    color: #f1f1f1;
}
#dashboard-card .card-box:hover .icon i {
    font-size: 100px;
    transition: 1s;
    -webkit-transition: 1s;
}
#dashboard-card .card-box .inner {
    padding: 5px 10px 0 10px;
}
#dashboard-card .card-box h3 {
    font-size: 27px;
    font-weight: bold;
    margin: 0 0 8px 0;
    white-space: nowrap;
    padding: 0;
    text-align: left;
}
#dashboard-card .card-box p {
    font-size: 15px;
}
#dashboard-card .card-box .icons {
    position: absolute;
    top: auto;
    bottom: 5px;
    right: 5px;
    z-index: 0;
    font-size: 72px;
    color: rgba(0, 0, 0, 0.15);
}
#dashboard-card .card-box .card-box-footer {
    position: absolute;
    left: 0px;
    bottom: 0px;
    text-align: center;
    padding: 3px 0;
    color: rgba(255, 255, 255, 0.8);
    background: rgba(0, 0, 0, 0.1);
    width: 100%;
    text-decoration: none;
}
#dashboard-card .card-box:hover .card-box-footer {
    background: rgba(0, 0, 0, 0.3);
}
.bg-blue {
    background-color: #00c0ef !important;
}
.bg-green {
    background-color: #00a65a !important;
}
.bg-orange {
    background-color: #f39c12 !important;
}
.bg-red {
    background-color: #d9534f !important;
}

.bg-gris {
    background-color: #34495e !important;
}

.bg-violet {
    background-color: #2980b9 !important;
}

.bg-primary {
    background-color: #007bff !important;
}


.bg-cornbleu {
    background-color: #5F9EA0 !important;
}

.bg-darkbleu {
    background-color: #FFDC00 !important;
}

.bg-ogn {
    background-color: #009688 !important;
}


</style>

@endsection

@section('content')

  <!-- start page title -->
  <div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Car service</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>

                </ol>
            </div>
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>
<!-- end page title -->





<!-- row -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2" id="dashboard-card">


                    <div class="col-md-4 col-sm-6">
                        <div class="card-box bg-blue">
                            <div class="inner">
                                <h3> {{$users->count()}} </h3>
                                <p> Clients </p>
                            </div>
                            <div class="icons">
                                <i class="fa fa-users" aria-hidden="true"></i>
                            </div>
                            <a href="{{route('dashboard.clients.index')}}"
                            class="card-box-footer"><i class="fa fa-eye" aria-hidden="true">
                                </i> See more <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="card-box bg-orange">
                            <div class="inner">
                                <h3> {{$vehicules->count()}} </h3>
                                <p> Cars </p>
                            </div>
                            <div class="icons">
                                <i class="fas fa-car"></i>
                            </div>
                            <a href="{{route('dashboard.cars.index')}}" class="card-box-footer"><i class="fa fa-eye" aria-hidden="true"></i> See more <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>


                    <div class="col-md-4 col-sm-6">
                        <div class="card-box bg-primary">
                            <div class="inner">
                                <h3>{{$mecaniciens->count() }} </h3>
                                <p> Mechanics </p>
                            </div>
                            <div class="icons">

                                <i class="fas fa-wrench" aria-hidden="true"></i>
                            </div>
                            <a href="{{route('dashboard.mechanics.index')}}" class="card-box-footer"><i class="fa fa-eye" aria-hidden="true"></i> See more <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>




                    <div class="col-md-4 col-sm-6">
                        <div class="card-box bg-gris">
                            <div class="inner">
                                <h3> {{$vidanges->count()}}  </h3>
                                <p>All Demandes</p>
                            </div>
                            <div class="icons">
                                <i class="fas fa-oil-can"></i>
                            </div>
                            <a href="{{route('dashboard.demandes.index')}}" class="card-box-footer"><i class="fa fa-eye" aria-hidden="true"></i> See more <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>


                    <div class="col-md-4 col-sm-6">
                        <div class="card-box bg-violet">
                            <div class="inner">
                                <h3>{{$demandeEnattents->count()}}</h3>
                                <p>Demandes in progress</p>
                            </div>
                            <div class="icons">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <a href="" class="card-box-footer"><i class="fa fa-eye" aria-hidden="true"></i> See more <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>


                    <div class="col-md-4 col-sm-6">
                        <div class="card-box bg-green">
                            <div class="inner">
                                <h3> {{$validees->count()}} </h3>
                                <p> Validated Demandes </p>
                            </div>
                            <div class="icons">
                                <i class="far fa-calendar-check"></i>
                            </div>
                            <a href="" class="card-box-footer"><i class="fa fa-eye" aria-hidden="true"></i> See more <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>



                        <div class="col-md-4 col-sm-6">
                            <div class="card-box bg-red">
                                <div class="inner">
                                    <h3> {{$refusees->count()}}</h3>
                                    <p> Refused Demandes  </p>
                                </div>
                                <div class="icons">
                                    <i class="fas fa-times"></i>
                                </div>
                                <a href="" class="card-box-footer"><i class="fa fa-eye" aria-hidden="true"></i> See more <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-6">
                            <div class="card-box bg-ogn">
                                <div class="inner">
                                    <h3>{{$products->count()}}</h3>
                                    <p> Products </p>
                                </div>
                                <div class="icons">
                                    <i class="fas fa-cubes"></i>
                                </div>
                                <a href="{{route('dashboard.products.index')}}" class="card-box-footer"><i class="fa fa-eye" aria-hidden="true"></i> See more <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>






                </div>





            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<!-- end row -->


@endsection



@section('scripts')
<!-- plugin js -->
<script src="{{asset('assets/js/vendor/dropzone.min.js')}}"></script>
<!-- init js -->
<script src="{{asset('assets/js/ui/component.fileupload.js')}}"></script>

     <script src="{{asset('assets/js/vendor/jquery.dataTables.min.js')}}"></script>
     <script src="{{asset('assets/js/vendor/dataTables.bootstrap5.js')}}"></script>
     <script src="{{asset('assets/js/vendor/dataTables.responsive.min.js')}}"></script>
     <script src="{{asset('assets/js/vendor/responsive.bootstrap5.min.js')}}"></script>

     <!-- third party js ends -->

     <!-- demo app -->
     <script src="{{asset('assets/js/pages/demo.datatable-init.js')}}"></script>


@endsection
