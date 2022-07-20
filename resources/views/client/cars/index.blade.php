@extends('layouts.app')
@section('styles')
<script src="https://kit.fontawesome.com/39e20eb3b4.js" crossorigin="anonymous"></script>
<style>

@media (max-width: 768px){
.w3-modal {
    padding-top: 50px;
}
}

.w3-modal {
    z-index: 3;
    display: none;
    padding-top: 100px;
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
}

.w3-xlarge {
    font-size: 24px!important;
}

.w3-display-topright {
    position: absolute;
    right: 0;
    top: 0;
}
.w3-btn, .w3-button {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
.w3-btn, .w3-button {
    border: none;
    display: inline-block;
    padding: 8px 16px;
    vertical-align: middle;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    background-color: inherit;
    text-align: center;
    cursor: pointer;
    white-space: nowrap;
}


.w3-animate-zoom {
    animation: animatezoom 0.6s;
}

@media (max-width: 768px){
    .w3-modal-content {
    width: 500px;
}
}

.w3-modal-content {
    margin: auto;
    background-color: #fff;
    position: relative;
    padding: 0;
    outline: 0;
    width: 600px;
}

.cart-grise .image{
    position: relative;
    width: 100px;
    height: 100px;
}


.cart-grise .image img{
    position: absolute;
    width: 100%;
    height: 100%;

    top: 0;
    bottom: 0;
    left: 0;
    right: 0;

}
.car #accordion .panel-title a.collapsed {
    box-shadow: 0 0 10px rgb(0 0 0 / 40%);
    /* color: #676767; */
}

.car #accordion .panel-title a {
    display: block;
    padding: 12px 4px;
    background: #fff;
    font-size: 18px;
    font-weight: bold;
    color: #000;
    /* border: 1px solid #ececec; */
    box-shadow: 0 0 10px rgb(0 0 0 / 40%);
    position: relative;
    transition: all 0.5s ease 0s;
    margin-left: 3%;
}
.car #accordion .panel-body {
    padding: 15px 10px 10px;
    border: none;
    font-size: 15px;
    color: #000000;
    line-height: 12px;
    margin-left: 11px;
    background-color: #f8fafc;
    border-radius: 0 0 12px 12px;
    box-shadow: 0 0 10px rgb(0 0 0 / 40%);
}

.car #accordion .panel-body div {
    text-align: center;
}
.car #accordion .panel-body a.btnV {
    border-radius: 50%;
    background-color: #39597d;
    height: 50px;
    width: 50px;
    padding: 0.5rem 0rem;
}

.car #accordion .panel-body a.btn {
    border-radius: 50%;
    /* border: 1px solid; */
    background-color: #b98b03;
    height: 50px;
    width: 50px;
    padding: 0.5rem;
}
.car #accordion .panel-body form button {
    border-radius: 50%;
    /* border: 1px solid; */
    background-color: #e21d38;
    height: 50px;
    width: 50px;
    padding: 0.5rem;
}
.car #accordion .panel-body a.btnV {
    border-radius: 50%;
    background-color: #39597d;
    height: 50px;
    width: 50px;
    padding: 0.5rem 0rem;
}
.car #accordion .panel-body .fas {
    font-size: 26px;
    color: white;
}
    </style>
@endsection
@section('content')
<div class="container mt-5 car">
    <div class="main-body">
        <div class="row">

            <div class="">

                <a href="{{route('cars.create')}}" class="btn btn-primary py-3 px-5 mb-4" style="float: right;"><i class="fa fa-plus ms-3 mx-2"></i>Add Car</a>
            </div>



            @php
            $head = 0
        @endphp

            @foreach ($cars as $car)
            @php
            $head++
        @endphp
        <div class="col-lg-4 col-md-6 col-sm-12 p-0">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                <div class="panel panel-default">



                    <div class="panel-heading" role="tab" id="heading{{$head}}">
                        <h4 class="panel-title text-center">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$head}}" aria-expanded="true" aria-controls="collapseOne">
                                <img src="{{asset('uploads/cars_logo/'.$car->marque->logo)}}" alt="" srcset="" width="200" height="144">
                            </a>
                        </h4>
                    </div>
                    <div id="collapse{{$head}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading{{$head}}">
                        <div class="panel-body">
                            <p><strong>Mark : </strong>{{' '.$car->marque->name}}</p>
                            <p><strong>Model : </strong>{{' '.$car->model->model}}</p>
                            <p><strong>Year : </strong>{{' '.$car->year}}</p>
                            <p><strong>Carbirant : </strong>{{' '.$car->carbirant->type}}</p>


                            <hr>
                            <div>

                                   <form action="{{route('cars.destroy',$car->id)}}"
                                       method="POST" class="d-inline">
                                       @csrf
                                       @method('DELETE')
                                   <button type="submit" class="btn">
                                       <i class="fas fa fa-trash" aria-hidden="true"></i>
                                   </button>
                                   </form>




                                   <a href="{{route('cars.edit',$car->id)}}" class="btn" aria-disabled="true">
                                   <i class="fas fa fa-pencil" aria-hidden="true"></i>
                                   </a>




                           </div>



                        </div>
                    </div>


                </div>
            </div>
        </div>



        @endforeach



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
