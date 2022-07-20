@extends('layouts.app')
@section('styles')
    <style>

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid transparent;
    border-radius: .25rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 2px 6px 0 rgb(206 206 238 / 54%);
}
.me-2 {
    margin-right: .5rem!important;
}
    </style>
@endsection
@section('content')
<div class="container mt-5">
    <div class="main-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="{{ isset(Auth::user()->image) ? asset(Auth::user()->image_path) : asset('img/user.jpg')}}" alt="Admin" class="rounded-circle p-1 bg-primary" width="110" height="110">
                            <div class="mt-3">
                                <h4>{{Auth::user()->first_name.' '.Auth::user()->last_name}}</h4>
                                <p class="text-secondary mb-1">{{Auth::user()->email}}</p>
                                <p class="text-muted font-size-sm">{{Auth::user()->adress}}</p>

                            </div>
                        </div>
                        <hr class="my-4">

                         <ul class="list-group list-group-flush">



                            @if (Auth::user()->hasRole('client'))

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <a href="{{route('cars.index')}}">
                                    <h6>Cars</h6>
                                </a>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <a href="{{route('demandes.clientDemandes')}}">
                                    <h6>Demandes</h6>
                                </a>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <a href="{{route('demandes.InProgress')}}">
                                    <h6>Demandes in progress</h6>
                                </a>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <a href="{{route('clientOrders')}}">
                                    <h6>Orders</h6>
                                </a>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <a href="{{route('chat.chatPage')}}">
                                    <h6>Chat</h6>
                                </a>
                            </li>


                            @endif

                            @if (Auth::user()->hasRole('mecanicien'))

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <a href="{{route('mechanic.MechanicDemandeAffected')}}">
                                    <h6>Demandes affected</h6>
                                </a>
                            </li>
                            @endif

                            @if (Auth::user()->hasRole('admin'))

                            @endif



                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <form action="{{route('users.update',Auth::user()->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                    <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">First Name</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="text" class="form-control" value="{{auth()->user()->first_name}}" name="first_name">

                              @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Last Name</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="text" class="form-control" value="{{auth()->user()->last_name}}" name="last_name">

                                @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Email</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="text" class="form-control" value="{{auth()->user()->email}}" name="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                        </div>



                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Phone</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="text" class="form-control" value="{{auth()->user()->phone_number}}" name="phone_number">

                                @error('phone_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">ID</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="text" class="form-control" value="{{auth()->user()->cin}}" name="cin">

                                @error('cin')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Image</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="file" class="form-control"  name="image">
                                @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Address</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="text" class="form-control" value="{{auth()->user()->adress}}" name="adress">
                                @error('adress')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9 text-secondary">
                                <input type="submit" class="btn btn-primary px-4" value="Save Changes">
                            </div>
                        </div>
                    </div>
                </div>
                </form>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
