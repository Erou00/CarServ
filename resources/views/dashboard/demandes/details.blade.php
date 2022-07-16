@extends('dashboard.layouts.app')


@section('styles')

<link href="{{asset('assets/css/vendor/dataTables.bootstrap5.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/vendor/responsive.bootstrap5.css')}}" rel="stylesheet" type="text/css">

<style>
    label{
        color: black;
        margin: 10px;
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
                    <li class="breadcrumb-item active">Demandes</li>
                </ol>
            </div>
            <h4 class="page-title">Demande Details</h4>
        </div>
    </div>
</div>
<!-- end page title -->





<!-- row -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card" >
            <div class="card-body">


                <div class="container ">


                    <div class="row m-5">
                        <div class="col-md-6 demandeDetails">

                            <div class="d-flex">
                                <div>
                                    <img src="{{asset('uploads/cars_logo/'.$demande->car->marque->logo)}}" alt="{{$demande->car->marque->name}}" srcset="" width="120" height="120">
                                </div>
                                <div>
                                    <label><Strong>{{$demande->car->marque->name}}</Strong></label><br>
                                    <label><Strong>{{$demande->car->model->model}}</Strong></label><br>

                                </div>
                            </div>

                          <label><Strong>Client : </Strong>{{$demande->user->first_name.' '.$demande->user->last_name}}</label><br>
                            <label><Strong>Carbirant : </Strong>{{$demande->car->carbirant->type}}</label><br>

                            <label><Strong><i class="fas fa-map-marker-alt"></i>Adresse du demande :
                            </Strong>
                            {{$demande->address}}

                        </label><br>



                        </div>
                        <div class="col-md-6 demandeDetails">

                            <label><Strong><i class="fas fa-boxes"></i>Services: </Strong></label><br>

                            @foreach ($demande->services as $item)
                            <label><i class="fa fa-check mr-3" aria-hidden="true" style="color: #00a65a;margin-right: 4px"></i><Strong>{{$item->name}}</Strong></label><br>
                            @endforeach



                            @php
                            $head = 0
                            @endphp

                            @foreach ($demande->services as $p)
                            @php
                            $head += $p->price
                            @endphp

                            @endforeach

                            <label><Strong><i class="fas fa-funnel-dollar"></i>Amount: </Strong> {{$head}}Dh</label><br>
                            <label><Strong><i class="fas fa-calendar-day"></i> Date  : </Strong>{{date('d/m/Y', strtotime($demande->date))}}<i class="fas fa-clock ml-3"></i>{{date('H:m', strtotime($demande->date))}}</label><br>
                            <label><Strong><i class="fas fa-comments"></i>Commente: </Strong>{{$demande->commentaire}}</label>
                        </div>
                    </div>


                    <h1 >Validation </h1>
                    <hr class="rounded">
                    <form action="{{route('dashboard.confirm.demande',$demande->id)}}" method="post">
                    @csrf
                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group row" style="">


                                    <label for="" class="col-md-12 col-form-label">
                                        <strong>Mechanics : </strong></label>

                                    <div class="col-md-9">
                                        <select class="form-select form-control" name="mechanic_id" id="mecanicien"
                                        data-dependent="model" aria-label="Default select example">
                                        <option option value="" >Choisissez</option>
                                        @foreach ($mechanics as $m)
                                        <option  value="{{$m->id}}">{{$m->first_name.' '.$m->last_name}}</option>
                                        @endforeach
                                        </select>
                                    </div>

                                    <div class="form-check mt-3 mx-3">

                                        <input type="radio" class="form-check-input"
                                        name="etat" value="Validate" id="validated"
                                        style="margin-top:10px;">

                                        <label class="form-check-label" for="validated">Validate</label>
                                    </div>

                                        <div class="form-check mx-3">
                                        <input type="radio" class="form-check-input"
                                        name="etat" value="Refuse" id="exampleCheck1"
                                        style="margin-top:10px;">

                                        <label class="form-check-label" for="exampleCheck1">Refuse</label>


                                </div>

                                </div>

                                <div class="form-group row" style="margin-top: 3%">




                                </div>
                            </div>
                            <div class="col-md-6 ">



                                <label for="Email" class="col-md-12 col-form-label">
                                    <strong>Commente: </strong></label>

                                <div class="col-md-9">
                                    <textarea class="form-control  mb-2" id="exampleFormControlTextarea1" rows="4" cols="18" name="commentaire"></textarea>

                                </div>






                        </div>

                        <div class="row">
                            <div class="col-md-6 offset-md-6 ">
                                <button type="submit" class="btn btn-dark btn-lg mb-2">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                    </form>


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
