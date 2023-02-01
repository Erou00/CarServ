@extends('layouts.dashboard.app')



@section('content')

<section class="content">

            @include('dashboard.partials._errors')

            <form action="{{ route('marques.update', $marque->id) }}" method="post">

                {{ csrf_field() }}
                {{ method_field('put') }}



                <fieldset class="form-group p-3 mt-2">
                    <legend class="p-2">{{$marque->nom}}</legend>

                   <div class="container-fluid">

                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom" class="form-control" value="{{ $marque->nom }}">
                    </div>

                    <div class="form-group">
                        <label>Sous Categorie</label>
                        <select name="categorie_id" class="form-control">
                            <option value=""></option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}" {{$marque->sous_categorie_id == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-default"><i class="fa fa-edit"></i> Enregistrer</button>
                    </div>

                   </div>

                </fieldset>

            </form>

@endsection

@push('scripts')


<script>


</script>

@endpush

