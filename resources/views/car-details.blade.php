@extends('layouts.app')
@section('styles')
    <style>
#header-carousel .img-responsive {
    display: block;
    width: 100%;
    height: 430px;
}

.img-responsive {
    display: block;
    width: 100%;
    height: 250px;
}
.fa {  margin-bottom: 10px;margin-right: 10px;}

small {
display: block;
line-height: 1.428571429;
color: #999;
}
    </style>
@endsection
@section('content')

<div class="container mt-5">
    <div class="main-body">


        <div class="jumbotron">
            <div class="container">



                <div class="row">
                    <div class="col-md-8">


                        <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach (json_decode($car->images) as $key=>$item)

                                <div class="carousel-item {{($key == 0 ? 'active':'')}}">
                                    <img  src="{{asset('uploads/cars_images/'.$item)}}" alt="Image" class="img-responsive" >
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
                    <div class="col-md-4">
                        <h1>{{$car->title}}</h1>
                        <h2>{{ $car->fiscal_power  }} Horses - {{ $car->doors }} doors</h2>
                        <div class="text-primary" style="font-size: 4rem; font-weight: bold;">{{ $car->price }} MAD</div>

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                            Contact the owner
                        </button>



                    </div>

        </div>

        <div class="container mt-4">


            <div class="row">
                <div class="col-md-8">
                    <h2>Features</h2>
                    <table class="table table-striped">
                        <tr>
                            <td>Mark</td>
                            <td>{{ $car->marque->name }} </td>
                        </tr>
                        <tr>
                            <td>Model</td>
                            <td>{{ $car->model->model }}</td>
                        </tr>
                        <tr>
                            <td>Carbirant</td>
                            <td>{{ $car->carbirant->type }}</td>
                        </tr>
                        <tr>
                            <td>Origin</td>
                            <td>{{ $car->origin->origin }}</td>
                        </tr>
                        <tr>
                            <td>kilometres</td>
                            <td>{{ $car->kilometre->kilometers  }}</td>
                        </tr>

                        <tr>
                            <td>First Hand</td>
                            <td>{{ $car->first_hand ? 'Yes' : 'No' }}</td>
                        </tr>
                        <tr>
                            <td>Year</td>
                            <td>{{ $car->year }}</td>
                        </tr>
                        <tr>
                            <td>Gear box</td>
                            <td>{{ $car->gearbox}}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    {{-- <h2>Spécificités</h2>
                    <ul class="list-group">
                        {{-- {% for option in property.options %}
                            <li class="list-group-item">{{ option.name }}</li>
                        {% endfor %}
                    </ul> --}}
                </div>

            <nav class="nav nav-tabs" id="tab"></nav>
        </div>

        <h2 class="mt-3">Description</h2>
        <p>
            {!! $car->description  !!}
        </p>







            </div>

        </div>
        </div>




    </div>
</div>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">

        <div class="modal-body">

            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="well well-sm">
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <img src="{{ isset($car->user->image) ? asset($car->user->image_path) : asset('img/user.jpg')}}" alt="" class="img-rounded img-responsive" />
                                </div>
                                <div class="col-sm-6 col-md-8">
                                    <h4>
                                        {{$car->user->first_name.' '.$car->user->last_name}}</h4>
                                    <strong><cite title="San Francisco, USA">
                                         <i class="fa fa-map-marker" aria-hidden="true">
                                    </i>{{$car->user->adress}}</cite></strong>
                                    <p>
                                        <i class="fa fa-envelope" aria-hidden="true">
                                           </i>{{$car->user->email}}
                                        <br />
                                        <i class="fa fa-phone-square" aria-hidden="true"></i>
                                        <a href="tel:{{$car->user->phone_number}}">{{$car->user->phone_number}}</a>
                                        <br />

                                    <!-- Split button -->

                                        <a href="" class="btn btn-primary mt-3">
                                            Send Message</a>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

      </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>

<script>


</script>
@endsection
