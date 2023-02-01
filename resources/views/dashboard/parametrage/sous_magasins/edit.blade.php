@extends('layouts.dashboard.app')



@section('content')

<section class="content">
  <div>
        <h2>Sous Categories</h2>
    </div>

    <ul class="breadcrumb mt-2">
        <li class="breadcrumb-item"><a href="">Acceuil</a></li>
        <li class="breadcrumb-item"><a href="">Paramétrage</a></li>
        <li class="breadcrumb-item"><a  href="">Sous Categories</a> </li>
        <li class="breadcrumb-item">Editer</li>
    </ul>


    <div class="box box-primary">

        <div class="box-header">
            {{-- <h3 class="box-title">@lang('site.add')</h3> --}}
        </div><!-- end of box header -->
        <div class="box-body">
            @include('dashboard.partials._errors')

            <form action="{{ route('sous_magasins.update', $magasin->id) }}" method="post">

                {{ csrf_field() }}
                {{ method_field('put') }}


                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="nom" class="form-control" value="{{ $magasin->nom }}">
                    </div>

                    <div class="form-group">
                        <label>Magasin</label>
                        <select name="magasin_id" class="form-control">
                            <option value=""></option>
                            @foreach ($mereMagasins as $sm)
                                <option value="{{ $sm->id }}" {{$magasin->magasin_id == $sm->id ? 'selected' : '' }}>{{ $sm->nom }}</option>
                            @endforeach
                        </select>
                    </div>


                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> Enregistrer</button>
                </div>
            </form>

        </div><!-- end of box body -->

    </div><!-- end of box -->
</section>
@endsection

@push('scripts')


<script>


</script>

@endpush

