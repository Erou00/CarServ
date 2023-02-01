@extends('layouts.dashboard.app')



@section('content')

<section class="content">


            @include('dashboard.partials._errors')

            <form action="{{ route('groupes.update', $groupe->id) }}" method="post">

                {{ csrf_field() }}
                {{ method_field('put') }}

                <div class="row">
                    <div class="col-md-6 offset-md-6 d-lg-flex  justify-content-end">
                        <div class="form-group mt-1">
                            <button type="submit" class="btn btn-default"><i class="fa fa-edit"></i> Modifier</button>
                        </div>
                    </div>
                </div>

                <fieldset class="form-group p-3 mt-2">
                    <legend class="p-2">{{ $groupe->nom }}</legend>

                    <div class="form-group row">
                        <label class="col-1 text-end">Nom:</label>
                        <div class="col-4">
                            <input type="text" name="nom" class="form-control" value="{{ $groupe->nom }}">
                        </div>
                    </div>

                </fieldset >


            </form>


</section>
@endsection

@push('scripts')


<script>


</script>

@endpush

