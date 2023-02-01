@extends('layouts.dashboard.app')



@section('content')

<section class="content">


            <form action="{{ route('groupes.store') }}" method="post">

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
                    <legend class="p-2">Ajouter un groupe</legend>
                    <div class="form-group row">
                        <label class="col-1 text-end">Nom :</label>
                        <div class="col-3">
                            <input type="text" name="nom" class="form-control" value="">
                        </div>
                    </div>
                </fieldset>



                    @include('dashboard.partials._errors')



            </form><!-- end of form -->

        </div><!-- end of box body -->

    </div><!-- end of box -->
</section>
@endsection

@push('scripts')


<script>


</script>

@endpush

