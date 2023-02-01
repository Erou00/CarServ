@extends('layouts.dashboard.app')



@section('content')

<section class="content">


    <div class="box box-primary">

        <div class="box-header">
            {{-- <h3 class="box-title">@lang('site.add')</h3> --}}
        </div><!-- end of box header -->
        <div class="box-body">
            @include('dashboard.partials._errors')

            <form action="{{ route('magasins.update', $magasin->id) }}" method="post">

                {{ csrf_field() }}
                {{ method_field('put') }}

                <fieldset class="form-group p-3 mt-2">
                    <legend class="p-2">Modifier</legend>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="nom" class="form-control" value="{{ $magasin->nom }}">
                    </div>


                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> @lang('site.edit')</button>
                </div>
                </fieldset>


            </form>

        </div><!-- end of box body -->

    </div><!-- end of box -->
</section>
@endsection

@push('scripts')


<script>


</script>

@endpush

