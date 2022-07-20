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

                        <h3>Carte grise :</h3>
                            <hr>
                        <div class="d-lg-flex d-none mb-1">


                            <a href="javascript: void(0);">
                                <img src="{{asset('uploads/carte_grise/'.$car->carte_grise_front)}}"
                                class="img-fluid img-thumbnail p-2"
                                style="width: 200px;height: 200px;" alt="{{$car->marque->name}}">
                            </a>

                            <a href="javascript: void(0);" class="ms-2">
                                <img src="{{asset('uploads/carte_grise/'.$car->carte_grise_back)}}"
                                 class="img-fluid img-thumbnail p-2" style="width: 200px;height: 200px;" alt="{{$car->marque->name}}">
                            </a>


                        </div>



                        @if ($car->for_sale)



                        <div class="row mt-3">
                            <h3>Images :</h3>
                            <hr>
                            <div class="col-sd-5">


                                <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach (json_decode($car->images) as $key=>$item)

                                        <div class="carousel-item {{($key == 0 ? 'active':'')}}">
                                            <img  src="{{asset('uploads/cars_images/'.$item)}}" alt="Image" class="img-responsive"
                                            style="width: 380px" >
                                        </div>



                                        @endforeach
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel"
                                        data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#header-carousel"
                                        data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>






                            </div>
                    </div>
                    @endif

                    </div> <!-- end col -->
                    <div class="col-lg-7">
                        <div class="ps-lg-4">
                            <!-- car title -->
                            <h3 class="mt-0">{{$car->marque->name.' '.$car->model->model}}
                                @if ($car->for_sale)

                                <form action="{{route('dashboard.validateCar',$car->id)}}" method="post">
                                    @csrf
                                    <input type="hidden" name="validate" value="{{ $car->validate == false ? 'validate' : 'non-validate' }}">
                                    <button  class="btn btn-success">
                                        {{ $car->validate == false ? 'Validate' : 'Refuse' }}
                                    </button>
                                </form>

                                @endif

                            </h3>
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

                                    <div class="col-md-4">
                                        <h6 class="font-14">Demande number:</h6>
                                        <p class="text-sm lh-150">{{$car->demandes->count()}}</p>
                                    </div>
                                </div>


                                @endif
                            </div>

                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row-->

                @if ($car->for_sale)

                  <div class="container">

                    <div class="row ">
                        <div class="col-12">
                            <h3>Description</h3>
                            <hr>
                            {!!  $car->description !!}
                        </div>
                     </div>
                  </div>

               @endif


            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<!-- end row -->



@endsection



@section('scripts')

@endsection
