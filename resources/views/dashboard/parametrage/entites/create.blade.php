@extends('layouts.dashboard.app')



@section('content')

<section class="content">


    <div class="box box-primary">

        <div class="box-header">
            {{-- <h3 class="box-title">@lang('site.add')</h3> --}}
        </div><!-- end of box header -->
        <div class="box-body">

            @include('dashboard.partials._errors')

            <form action="{{ route('entites.store') }}" method="post">

                {{ csrf_field() }}
                {{ method_field('post') }}


                <div class="row">
                    <div class="col-md-6 offset-md-6 d-lg-flex  justify-content-end">
                        <div class="form-group mt-1">
                            <button type="submit" class="btn btn-default"><i class="fa fa-plus"></i> Ajouter</button>
                        </div>
                    </div>
                </div>
                <fieldset class="form-group p-3 mt-2">
                    <legend class="p-2">Ajouter Entite</legend>
                    <div class="form-group row">
                        <label class="col-2 col-form-label text-end">Code :</label>

                        <div class="col-2 m-0">
                            <input type="text" name="abbreviation" class="form-control" value="">
                        </div>

                        <label class="col-1 col-form-label  text-end">Nom :</label>

                        <div class="col-2 m-0">
                            <input type="text" name="nom" class="form-control col-2" value="">
                        </div>

                        <label class="col-2 col-form-label  text-end">Entite mére</label> <br>

                        <div class="col-3">
                            <select name="entite_mere_id" class="form-control col-2 select">
                                <option value=""></option>
                                @foreach ($entites as $entite)
                                    <option value="{{$entite->id }}" {{ old('entite_id') == $entite->id ? 'selected' : '' }}>{{ $entite->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <label class="col-2 col-form-label  text-end">Type:</label> <br>

                        <div class="col-2">
                            <select name="type_entite_id" class="form-control col-2 select">
                                <option value=""></option>
                                @foreach ($types as $t)
                                    <option value="{{$t->id }}" >{{ $t->type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <label class="col-1 col-form-label  text-end">Email:</label> <br>

                        <div class="col-3">
                            <input type="email" name="email" class="form-control col-2" value="">

                        </div>










                    </div>


                </fieldset>




            </form><!-- end of form -->

        </div><!-- end of box body -->

    </div><!-- end of box -->
</section>
@endsection

@push('scripts')


<script>

$('.select2').select2();
</script>

@endpush

