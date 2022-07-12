@extends('dashboard.layouts.app')


@section('styles')

<link href="{{asset('assets/css/vendor/dataTables.bootstrap5.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/vendor/responsive.bootstrap5.css')}}" rel="stylesheet" type="text/css">



@endsection

@section('content')

  <!-- start page title -->
  <div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Car Details</li>
                </ol>
            </div>
            <h4 class="page-title">Car Details</h4>
        </div>
    </div>
</div>
<!-- end page title -->





<!-- row -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                <div class="row">
                    <div class="col-lg-5">
                        <!-- Product image -->
                        <a href="javascript: void(0);" class="text-center d-block mb-4">
                            <img src="{{asset('uploads/cars_logo/'.$car->marque->logo)}}" class="img-fluid" style="max-width: 280px;" alt="{{$car->marque->name}}">
                        </a>

                        <div class="d-lg-flex d-none mb-1">
                            <a href="javascript: void(0);">
                                <img src="{{asset('uploads/carte_grise/'.$car->carte_grise_front)}}"
                                class="img-fluid img-thumbnail p-2"
                                style="width: 400px;height: 250px;" alt="{{$car->marque->name}}">
                            </a>


                        </div>
                        <div class="d-lg-flex d-none ">

                            <a href="javascript: void(0);" class="ms-2">
                                <img src="{{asset('uploads/carte_grise/'.$car->carte_grise_back)}}"
                                 class="img-fluid img-thumbnail p-2" style="width: 400px;height: 250px;" alt="{{$car->marque->name}}">
                            </a>

                        </div>
                    </div> <!-- end col -->
                    <div class="col-lg-7">
                        <form class="ps-lg-4">
                            <!-- car title -->
                            <h3 class="mt-0">{{$car->marque->name.' '.$car->model->model}} </h3>
                            <p class="mb-1">Added Date: {{$car->created_at}}</p>



                            <!-- car information -->
                            <div class="mt-4">
                                <div class="row">
                                    <h4 class="font-14">Owner of the car:</h4>
                                    <div class="col-md-6">
                                       <img src="{{$car->user->image_path}}"  class="img-fluid img-thumbnail p-2" style="max-width: 200px;" alt="{{$car->user->first_name.' '.$car->user->last_name}}" srcset="">
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="font-14">Name:</h6>
                                        <p class="text-sm lh-150">{{$car->user->first_name.' '.$car->user->last_name}}</p>

                                        <h6 class="font-14">Email:</h6>
                                        <p class="text-sm lh-150">{{$car->user->email}}</p>

                                        <h6 class="font-14">Phone Number:</h6>
                                        <p class="text-sm lh-150">{{$car->user->email}}</p>

                                        <h6 class="font-14">Adress:</h6>
                                        <p class="text-sm lh-150">{{$car->user->adress}}</p>
                                    </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <h4 class="font-18">The car information :</h2>
                                    <div class="col-md-4">
                                        <h6 class="font-14">Year:</h6>
                                        <p class="text-sm lh-150">{{$car->year}}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="font-14">Carbirant:</h6>
                                        <p class="text-sm lh-150">{{$car->carbirant->type}}</p>
                                    </div>

                                </div>

                                @if ($car->for_sale)


                                <div class="row">

                                    <div class="col-md-4">
                                        <h6 class="font-14">Annonce title :</h6>
                                        <p class="text-sm lh-150">{{$car->title}}</p>
                                    </div>

                                    <div class="col-md-4">
                                        <h6 class="font-14">Kilometres:</h6>
                                        <p class="text-sm lh-150">{{$car->kilometre->kilometers}}</p>
                                    </div>

                                    <div class="col-md-4">
                                        <h6 class="font-14">Fiscal Power:</h6>
                                        <p class="text-sm lh-150">{{$car->gearbox}}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="font-14">doors:</h6>
                                        <p class="text-sm lh-150">{{$car->doors}}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="font-14">Origin:</h6>
                                        <p class="text-sm lh-150">{{$car->origin->origin}}</p>
                                    </div>

                                    <div class="col-md-4">
                                        <h6 class="font-14">First Hand:</h6>
                                        <p class="text-sm lh-150">{{($car->first_hand)? 'Yes': 'No'}}</p>
                                    </div>

                                    <div class="col-md-4">
                                        <h6 class="font-14">For Sale:</h6>
                                        <p class="text-sm lh-150">{{($car->for_sale)? 'Yes': 'No'}}</p>
                                    </div>
                                </div>


                                @endif
                            </div>

                        </form>
                    </div> <!-- end col -->
                </div> <!-- end row-->

                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Outlets</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ASOS Ridley Outlet - NYC</td>
                                <td>$139.58</td>
                                <td>
                                    <div class="progress-w-percent mb-0">
                                        <span class="progress-value">478 </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 56%;" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>$1,89,547</td>
                            </tr>
                            <tr>
                                <td>Marco Outlet - SRT</td>
                                <td>$149.99</td>
                                <td>
                                    <div class="progress-w-percent mb-0">
                                        <span class="progress-value">73 </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 16%;" aria-valuenow="16" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>$87,245</td>
                            </tr>
                            <tr>
                                <td>Chairtest Outlet - HY</td>
                                <td>$135.87</td>
                                <td>
                                    <div class="progress-w-percent mb-0">
                                        <span class="progress-value">781 </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>$5,87,478</td>
                            </tr>
                            <tr>
                                <td>Nworld Group - India</td>
                                <td>$159.89</td>
                                <td>
                                    <div class="progress-w-percent mb-0">
                                        <span class="progress-value">815 </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 89%;" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>$55,781</td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!-- end table-responsive-->



            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<!-- end row -->



@endsection



@section('scripts')

@endsection
