@extends('layouts.dashboard.app')



@section('content')

<section class="content">
  <div>
        <h2>Classes</h2>
    </div>

    <ul class="breadcrumb mt-2">
        <li class="breadcrumb-item"><a href="">Acceuil</a></li>
        <li class="breadcrumb-item"><a href="">Paramétrage</a></li>
        <li class="breadcrumb-item"><a  href="">Classes</a> </li>
        <li class="breadcrumb-item">Ajouter</li>
    </ul>


    <div class="box box-primary">

        <div class="box-header">
            {{-- <h3 class="box-title">@lang('site.add')</h3> --}}
        </div><!-- end of box header -->
        <div class="box-body">

            @include('dashboard.partials._errors')

            <form action="{{ route('classes.store') }}" method="post">

                {{ csrf_field() }}
                {{ method_field('post') }}


                    <div class="form-group">
                        <label>Nom :</label>
                        <input type="text" name="nom" class="form-control" value="">
                    </div>

                    <div class="form-group">
                        <label>Code :</label>
                        <input type="text" name="code" class="form-control" value="">
                    </div>

                    <div class="form-group">
                        <label>Volume</label>
                        <select name="volume_id" class="form-control">
                            <option value=""></option>
                            @foreach ($volumes as $v)
                                <option value="{{ $v->id }}" {{ old('v_id') == $v->id ? 'selected' : '' }}>{{ $v->nom }}</option>
                            @endforeach
                        </select>
                    </div>


                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Ajouter</button>
                </div>

            </form><!-- end of form -->

        </div><!-- end of box body -->

    </div><!-- end of box -->
</section>
@endsection

@push('scripts')


<script>


</script>

@endpush

