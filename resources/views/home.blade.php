@extends('layouts.dashboard.app')

@section('styles')
    <style>

    </style>
@endsection
@section('content')
    <section class="content">
        <div>
            <h2>Profile</h2>
        </div>

        <ul class="breadcrumb mt-3 p-2">
            <li class="breadcrumb-item"><a href="">Acceuil</a></li>
            <li class="breadcrumb-item">Profile</li>
        </ul>




        <div class="box box-primary">

            <div class="box-header">
                <div class="row mb-2">

                    <div class="col-md-12">

                    </div>

                </div><!-- end of row -->

            </div><!-- end of box header -->
            <div class="box-body">
                <div class="row">

                    <div class="col-md-12">

                        <div class="tile shadow">



                            <div class="row">

                                <div class="col-md-12">
                                    @include('dashboard.partials._errors')

                                    <form action="{{route('profile.update',Auth::user()->id)}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                            <div class="card">
                                            <div class="card-body">

                                                @if (session('status'))
                                                <div class="alert alert-success" role="alert">
                                                    {{ session('status') }}
                                                </div>
                                                @elseif (session('error'))
                                                    <div class="alert alert-danger" role="alert">
                                                        {{ session('error') }}
                                                    </div>
                                                @endif


                                                <div class="row mb-3">
                                                    <div class="col-sm-2">
                                                        <h5 style='font-weight:bold' class="mb-0">
                                                            NOM:</h5>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="text"
                                                        class="form-control"
                                                        value="{{auth()->user()->nom}}" name="nom" required>
                                                    </div>
                                                </div>


                                                <div class="row mb-3">
                                                    <div class="col-sm-2">
                                                        <h5 style='font-weight:bold' class="mb-0">PRENOM:</h5>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="text" class="form-control"
                                                        value="{{auth()->user()->prenom}}" name="prenom"
                                                        required>


                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-2">
                                                        <h5 style='font-weight:bold' class="mb-0">EMAIL:
                                                            </h5>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="text" class="form-control"
                                                        value="{{auth()->user()->email}}" name="email" required>


                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-2">
                                                        <h5 style='font-weight:bold' class="mb-0">MOT DE PASSE:
                                                            </h5>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input name="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" id="oldPasswordInput"
                                                        placeholder="Mote de passe">


                                                    </div>
                                                </div>


                                                <div class="row mb-3">
                                                    <div class="col-sm-2">
                                                        <h5 style='font-weight:bold' class="mb-0">NOUVEAU MOTE DE PASSE:
                                                            </h5>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input name="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" id="newPasswordInput"
                                                        placeholder="Nouveau mot de passe">

                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-2">
                                                        <h5 style='font-weight:bold' class="mb-0">CONFIRMER MOTE DE PASSE:
                                                            </h5>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input name="new_password_confirmation" type="password" class="form-control" id="confirmNewPasswordInput"
                                                        placeholder="Confirmer mot de passe">

                                                    </div>
                                                </div>










                                                <div class="row">
                                                    <div class="col-sm-3 text-secondary">
                                                        <input type="submit"
                                                        class="btn btn-primary px-4"
                                                         value="Enregistrer">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </form>

                                </div><!-- end of col -->

                            </div><!-- end of row -->

                        </div><!-- end of tile -->

                    </div><!-- end of col -->

                </div><!-- end of row -->

            </div><!-- end of box body -->

        </div><!-- end of box -->




<!-- Button trigger modal -->







    </section>
@endsection

@push('scripts')
    <script>

    </script>
@endpush
