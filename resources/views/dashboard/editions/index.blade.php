@extends('layouts.dashboard.app')

@section('styles')
<style>
    ul.edition {
  list-style-type: none;
  margin: 0;
  padding: 0;
  width: 200px;
  background-color: #f1f1f1;
}

ul.edition li a {
  display: block;
  color: #000;
  padding: 8px 16px;
  text-decoration: none;
}

/* Change the link color on hover */
ul.edition li a:hover {
  background-color: #555;
  color: white;
}
</style>
@endsection
@section('content')


<section class=" mt-3" >

    <div class="container">

                    <form action="{{ route('editions.approvisionnement') }}" method="get" target="_blank">
                        <fieldset class="form-group p-3 mt-2">
                            <legend class="p-2">Situation D'Approvisionnemen :</legend>

                            <div class="container">
                                <div class="form-group row">



                                    <div class="col-1">

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="soldes"
                                                 name="etat" id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">
                                              Soldée
                                            </label>
                                          </div>
                                    </div>
                                    <label for="text1" class="col-1 col-form-label text-end">Depuis:</label>
                                    <div class="col-2">
                                        <div class="input-group">
                                             <input type="date" class="form-control" name="startDate">
                                        </div>
                                    </div>
                                    <label for="text1" class="col-1 col-form-label text-end">à:</label>
                                    <div class="col-2">
                                        <div class="input-group">
                                            <input type="date" class="form-control" name="endDate">
                                        </div>
                                    </div>

                                    @if (Auth::user()->hasRole('master'))
                                    <label for="text1" class="col-1 col-form-label text-end">Magasin:</label>
                                    <div class="col-2">
                                        <div class="input-group">


                                            <select name="app_magasin_id" id="" class="form-control"  >
                                                <option value=""> </option>
                                                @foreach ($magasins as $m)
                                                    <option value="{{ $m->id }}" {{ $m->id == Auth::user()->magasin_id ? 'selected' : '' }}>
                                                    {{ $m->nom }}


                                                @endforeach
                                             </select>



                                        </div>
                                    </div>
                                    @else
                                    <input type="hidden" name="app_magasin_id" value="{{ Auth::user()->magasin_id }}">

                                    @endif

                                    <div class="col-1 ">
                                        <button class="btn btn-default">Imprimer </button>
                                    </div>

                                </div>
                            </div>

                        </fieldset>


                    </form>


                    <form action="{{ route('editions.statistiqueUtlistateurs') }}" method="get" target="_blank">
                        <fieldset class="form-group p-3 mt-2">
                            <legend class="p-2">Statistique des utlisateur :</legend>

                            <div class="form-group row">




                                <label for="text1" class="col-1 col-form-label text-end">Depuis:</label>
                                <div class="col-2">
                                    <div class="input-group">
                                         <input type="date" name="statistique_startDate" class="form-control">
                                    </div>
                                </div>
                                <label for="text1" class="col-1 col-form-label text-end">à:</label>
                                <div class="col-2">
                                    <div class="input-group">
                                        <input type="date" name="statistique_endDate" class="form-control">
                                    </div>
                                </div>

                                @if (Auth::user()->hasRole('master'))
                                <label for="text1" class="col-1 col-form-label text-end">Magasin:</label>
                                <div class="col-2">
                                    <div class="input-group">

                                        <select name="statistique_magasin_id" id="" class="form-control"  >
                                           <option value=""></option>
                                            @foreach ($magasins as $m)
                                                <option value="{{ $m->id }}" {{ $m->id == Auth::user()->magasin_id ? 'selected' : '' }}>
                                                {{ $m->nom }}
                                                </option>

                                            @endforeach
                                         </select>



                                    </div>
                                </div>
                                @else
                                <input type="hidden" name="app_magasin_id" value="{{ Auth::user()->magasin_id }}">

                                @endif

                                <div class="col-1 ">
                                    <button class="btn btn-default">Imprimer </button>
                                </div>

                            </div>
                        </fieldset>


                    </form>

                    <form action="{{ route('editions.blPreparation') }}" method="get" target="_blank">
                        <fieldset class="form-group p-3 mt-2">
                            <legend class="p-2">BL en cours de preparation :</legend>

                            <div class="form-group row">

                                @if (Auth::user()->hasRole('master'))
                                <label for="text1" class="col-1 col-form-label text-end">Magasin:</label>
                                <div class="col-2">
                                    <div class="input-group">

                                        <select name="bl_magasin_id" id="" class="form-control"  >
                                            @foreach ($magasins as $m)
                                                <option value="{{ $m->id }}" {{ $m->id == Auth::user()->magasin_id ? 'selected' : '' }}>
                                                {{ $m->nom }}
                                                </option>

                                            @endforeach
                                         </select>



                                    </div>
                                </div>
                                @else
                                <input type="hidden" name="app_magasin_id" value="{{ Auth::user()->magasin_id }}">

                                @endif

                                <div class="col-1 ">
                                    <button class="btn btn-default">Imprimer </button>
                                </div>

                            </div>
                        </fieldset>


                    </form>


                    <form action="{{ route('editions.produitsNonActive') }}" method="get" target="_blank">
                        <fieldset class="form-group p-3 mt-2">
                            <legend class="p-2">Produits Non Active :</legend>
                                <div class="col-1 ">
                                    <button class="btn btn-default">Imprimer </button>
                                </div>

                            </fieldset>
                        </fieldset>


                    </form>



        </div>

    </div>




</section>


@endsection

@push('scripts')
    <script>

    </script>
@endpush
