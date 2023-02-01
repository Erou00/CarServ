@extends('layouts.dashboard.app')

@section('content')

        <section>
            <div class="container mt-2">
                <form action="{{ route('utilisateurs.store') }}" method="post">
                    @csrf
                    <div class="row mb-0">
                        <div class="col-md-3 offset-md-9 formButton" style="text-align: end;">
                            <button class="btn btn-default" type="submit" id="submit">
                                <i class="fa fa-floppy-o me-1" aria-hidden="true"></i>Enregistrer</button>
                        </div>
                    </div>
                    @include('dashboard.partials._errors')

                    <fieldset class="form-group p-3 ms-0 me-0 mt-1 w-100">
                        <legend class="p-2">Ajouter un Utilisateur</legend>

                        <div class="form-group row my-2 lh-1">
                            <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Utlisateur*:</label>
                            <div class="col-2">
                                <div class="input-group">
                                    <input type="text" name="utilisateur" autofocus class="form-control" required>
                                </div>
                            </div>

                            <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Nom*:</label>
                            <div class="col-2">
                                <div class="input-group">
                                    <input type="text" name="nom" autofocus class="form-control"
                                        value="{{ old('nom') }}" required>
                                </div>
                            </div>

                            <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Prenom*:</label>
                            <div class="col-2">
                                <div class="input-group">
                                    <input type="text" name="prenom" autofocus class="form-control"
                                        value="{{ old('prenom') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row my-2 lh-1">
                            <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Email*:</label>
                            <div class="col-2">
                                <div class="input-group">
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                        required>

                                </div>
                            </div>

                            <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Mot de passe*:</label>
                            <div class="col-2">
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" value="" required>

                                </div>
                            </div>

                            <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Confirmer Mot de
                                passe*:</label>
                            <div class="col-2">
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" class="form-control" value=""
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row my-2 lh-1">
                            <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Categories*:</label>

                            <div class="col-3">
                                <div class="input-group">
                                    <select class="select--roles form-control" multiple="multiple" name="categorie_id[]">
                                        <option value=""></option>
                                        @foreach ($categories as $c)
                                            <option value="{{ $c->id }}"
                                                {{ $c->id == old('role_id') ? 'selected' : '' }}>{{ $c->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @if (Auth::user()->hasRole('master'))
                                <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Magasin*:</label>
                                <div class="col-3">
                                    <div class="input-group">

                                        <select class="select--roles form-control magasin" name="magasin_id"
                                            data-dependent="sous_magasin_id"
                                            >
                                            <option value=""></option>
                                            @foreach ($magasins as $m)
                                                <option value="{{ $m->id }}"
                                                    {{ $m->id == old('role_id') ? 'selected' : '' }}>{{ $m->nom }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                            @else
                            <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Magasin*:</label>

                            <div class="col-3">
                                <div class="input-group">

                                    <select class="form-control magasin" name="magasin_id"
                                            data-dependent="sous_magasin_id" readonly
                                            >
                                            <option value="{{ Auth::user()->magasin->id}} " selected>{{ Auth::user()->magasin->nom }}</option>

                                    </select>


                                </div>
                            </div>

                            @endif




                        </div>

                        <div class="form-group row my-2 lh-1">


                            <label for="text1" class="col-2 col-form-label text-end lh-1">Role*:</label>
                            <div class="col-3">
                                <div class="input-group">


                                    <select class="select--roles form-control" multiple="multiple" name="role_id[]">
                                        <option value=""></option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ $role->id == old('role_id') ? 'selected' : '' }}>{{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Sous Magasin:</label>


                            <div class="col-3">
                                <div class="input-group">
                                    <select class="select--roles form-control" multiple="multiple" name="sousmagasin_id[]"
                                        id="sous_magasin_id">

                                    </select>

                                </div>
                            </div>


                        </div>
                    </fieldset>

                </form>
            </div>
        </section>

@endsection


@push('scripts')
    <script>
        $(document).ready(function() {

            $.ajax({
                url:"{{route('sous_magasins.getSousMagasins')}}",
                method:"POST",
                data:{select:'', value:$('.magasin').val(), _token:"{{ csrf_token() }}", dependent:'sous_magasin_id'},
                success:function(result)
                {
                $('#sous_magasin_id').html(result);
                }
            });

            $('.select--roles').select2();

            $(".magasin").on('change', function() {
                if ($(this).val() != '') {
                    var select = $(this).attr("id");
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = "{{ csrf_token() }}";

                    $.ajax({
                        url: "{{ route('sous_magasins.getSousMagasins') }}",
                        method: "POST",
                        data: {
                            select: select,
                            value: value,
                            _token: _token,
                            dependent: dependent
                        },
                        success: function(result) {
                            $('#' + dependent).html(result);
                        }
                    })
                }
            });

            $(".sous_magasin").on('change', function() {});

        })
    </script>
@endpush
