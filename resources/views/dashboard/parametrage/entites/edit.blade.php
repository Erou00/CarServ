@extends('layouts.dashboard.app')



@section('content')

<section class="content">

    <div class="box box-primary">

        <div class="box-header">
            {{-- <h3 class="box-title">@lang('site.add')</h3> --}}
        </div><!-- end of box header -->
        <div class="box-body">
            @include('dashboard.partials._errors')

            <form action="{{ route('entites.update', $entite->id) }}" method="post">

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
                    <legend class="p-2">{{ $entite->nom}}</legend>

                    <div class="form-group row">
                        <label class="col-2 text-end">Code:</label>

                        <div class="col-2">
                            <input type="text" name="abbreviation" class="form-control" value="{{ $entite->abbreviation }}">
                        </div>

                        <label class="col-1 text-end">Nom:</label>

                        <div class="col-2">
                            <input type="text" name="nom" class="form-control" value="{{ $entite->nom }}">
                        </div>

                        <label class="col-2 text-end">Entite Mére:</label>
                        <div class="col-3">
                            <select name="entite_mere_id" class="form-control select2">
                                <option value=""></option>
                                @foreach ($mereEntites as $e)
                                    <option value="{{ $e->id }}" {{$entite->entite_mere_id == $e->id ? 'selected' : '' }}>{{ $e->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <label class="col-2 col-form-label  text-end">Type:</label> <br>

                        <div class="col-2">
                            <select name="type_entite_id" class="form-control col-2 select">
                                <option value=""></option>
                                @foreach ($types as $t)
                                    <option value="{{$t->id }}" {{$entite->type_entite_id == $t->id ? 'selected' : '' }}>{{ $t->type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <label class="col-1 col-form-label  text-end">Email:</label> <br>

                        <div class="col-3">
                            <input type="email" name="email" class="form-control col-2" value="{{ $entite->email }}">

                        </div>




                    </div>
                </fieldset>



            </form>

        </div><!-- end of box body -->

    </div><!-- end of box -->
</section>
@endsection

@push('scripts')


<script>

   $('.select2').select2();

</script>

@endpush

