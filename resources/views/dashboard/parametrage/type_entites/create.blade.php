@extends('layouts.dashboard.app')



@section('content')

<section class="content">


    <div class="box box-primary">

        <div class="box-header">
            {{-- <h3 class="box-title">@lang('site.add')</h3> --}}
        </div><!-- end of box header -->
        <div class="box-body">

            @include('dashboard.partials._errors')

            <form action="{{ route('types.store') }}" method="post">

                {{ csrf_field() }}
                {{ method_field('post') }}

                <fieldset class="form-group p-3 mt-2">
                    <legend class="p-2">Type Entite</legend>

                    <div class="form-group">
                        <label>Type :</label>
                        <input type="text" name="type" class="form-control" value="">
                    </div>


                <div class="form-group">
                    <button type="submit" class="btn btn-default mt-2"><i class="fa fa-plus"></i> Ajouter</button>
                </div>
                </fieldset>


            </form><!-- end of form -->

        </div><!-- end of box body -->

    </div><!-- end of box -->
</section>
@endsection

@push('scripts')


<script>


</script>

@endpush

