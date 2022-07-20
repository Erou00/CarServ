@extends('layouts.app')
@section('styles')
<script src="https://kit.fontawesome.com/39e20eb3b4.js" crossorigin="anonymous"></script>
<style>


</style>
@endsection
@section('content')
<div class="container mt-5 car" >
    <div class="main-body">

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
                                            <label class="mt-2"><Strong>{{$demande->car->marque->name}}</Strong></label><br>
                                            <label class="mt-2"><Strong>{{$demande->car->model->model}}</Strong></label><br>

                                        </div>
                                    </div>

                                  <label class="mt-2"><Strong>Client : </Strong>{{$demande->user->first_name.' '.$demande->user->last_name}}</label><br>
                                    <label class="mt-2"><Strong>Carbirant : </Strong>{{$demande->car->carbirant->type}}</label><br>

                                    <label class="mt-2"><Strong><i class="fas fa-map-marker-alt"></i>Adresse du demande :
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

                                    <label class="mt-2"><Strong><i class="fas fa-funnel-dollar"></i>Amount: </Strong> {{$head}}Dh</label><br>
                                    <label class="mt-2"><Strong><i class="fas fa-calendar-day"></i> Date  : </Strong>{{date('d/m/Y', strtotime($demande->date))}}<i class="fas fa-clock ml-3"></i>{{date('H:m', strtotime($demande->date))}}</label><br>
                                    <label class="mt-2"><Strong><i class="fas fa-comments"></i>Commente: </Strong>{{$demande->commentaire}}</label>



                                    @if ($demande->etat != 'Completed')
                                         <form action="{{route('mechanic.changeEtat',$demande->id)}}" method="post" class="mt-5">
                                        @csrf
                                        <input type="hidden" name="etat" value="{{ $demande->etat == 'Affected' ? 'Handling' : 'Completed' }}">
                                        <button type="submit" class="btn btn-primary">{{ $demande->etat == 'Affected' ? 'Handling' : 'Completed' }}</button>
                                    </form>
                                    @endif

                                </div>
                            </div>





                    </div>



                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>




    </div>
</div>
@endsection

@section('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

@endsection
