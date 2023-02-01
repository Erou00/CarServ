@extends('layouts.dashboard.app')



@section('content')
    <section>
        <div class="container mt-2">
            <form action="{{ route('produits.update', $produit->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row mb-0">
                    <div class="col-md-3 offset-md-9 formButton" style="text-align: end;">
                        <button class="btn btn-default" type="submit" id="submit">
                            <i class="fa fa-floppy-o me-1" aria-hidden="true"></i>Enregistrer</button>
                    </div>
                </div>
                @include('dashboard.partials._errors')


                <fieldset class="form-group p-3 ms-0 me-0 mt-1 w-100">
                    <legend class="p-2 ">{{ $produit->designation }}</legend>


                    <div class="form-group row my-2 lh-1">
                        <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Categorie*:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <select name="categorie_id" id="" required="required" class="form-control  py-0 my-0 select">
                                    <option value=""></option>
                                    @foreach ($categories as $c)
                                    <option value="{{ $c->id }}"
                                        {{ $c->id == $produit->categorie_id ? 'selected ' : '' }}>{{ $c->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- <input id="text1" name="categorie" type="text" required="required"
                                    class="form-control  py-0 my-0"> -->
                            </div>
                        </div>

                        <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Sous Categorie*:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <select name="sous_categorie_id" class="form-control select  py-0 my-0" required="required">
                                    <option value=""></option>
                                    @foreach ($sousCategories as $sc)
                                    <option value="{{ $sc->id }}"
                                        {{ $sc->id == $produit->sous_categorie_id ? 'selected ' : '' }}>{{ $sc->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- <input id="text1" name="sous_categorie" type="text" required="required"
                                    class="form-control  py-0 my-0"> -->
                            </div>
                        </div>

                        <label for="text1" class="col-2 col-form-label text-end my-0">Marque / Famille*:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <select name="marque_id" class="form-control select marque_id py-0 my-0" required="required"
                                data-select2-id="marque_id">
                                    <option value=""></option>
                                    @foreach ($marques as $m)
                                        <option value="{{ $m->id }}"
                                            {{ $m->id == $produit->marque_id ? 'selected ' : '' }}>{{ $m->nom }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>




                    </div>

                    <div class="form-group row my-2 lh-1">
                        <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Unite Reglementaire*:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <select name="unite_reglementaire_id" class="form-control  py-0 my-0" required="required">
                                    <option value="unite-1">unite-1</option>
                                    @foreach ($uniteReglementaires as $ur)
                                        <option value="{{ $ur->id }}"
                                            {{ $ur->id == $produit->unite_reglementaire_id ? 'selected ' : '' }}>{{ $ur->code }}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- <input id="text1" name="unite_reglementaire" type="text" required="required"
                                    class="form-control  py-0 my-0"> -->
                            </div>
                        </div>

                        <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Designation*:</label>
                        <div class="col-2">
                            <div class="input-group">

                                 <input id="text1" name="designation" type="text" required="required"
                                    class="form-control  py-0 my-0" value="{{ $produit->designation }}">
                            </div>
                        </div>

                        <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">stock min*:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <input id="text1" name="stock_min"
                                type="number" step=".00" required="required"
                                    class="form-control  py-0 my-0" value="{{ $produit->stock_min }}">
                            </div>
                        </div>


                    </div>

                    <div class="form-group row my-2 lh-1">
                        <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">prix unitaire*:</label>

                        <div class="col-3">
                            <div class="input-group">
                                <input id="text1" name="prix_unitaire" type="number" step=".01"
                                    required="required" class="form-control  py-0 my-0"
                                    value="{{ $produit->prix_unitaire }}">
                            </div>
                        </div>

                        <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">Devise*:</label>
                        <div class="col-3">
                            <div class="input-group">
                                <select name="devise_id" id="devise" class="form-control  py-0 my-0" required>
                                    <option value=""></option>
                                    @foreach ($devises as $d)
                                        <option value="{{ $d->id }}"
                                            {{ $d->id == $produit->devise_id ? 'selected ' : '' }}>{{ $d->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>


                        <div class="form-group row my-2 lh-1">
                            <label for="text1" class="col-2 col-form-label text-end my-0 lh-1">active:</label>

                                    <div class="col-3">
                                        <div class="input-group">
                                            <!-- <input id="text1" name="ligne_budgetaire" type="text" required="required"
                                                class="form-control  py-0"> -->
                                                <select name="active" id="cars" class="form-control  py-0 my-0" required>
                                                    <option {{  $produit->active == 1 ? 'selected ' : '' }} value="1">Oui</option>
                                                    <option {{  $produit->active == 0 ? 'selected ' : '' }} value="0">Non</option>
                                                  </select>
                                        </div>
                                    </div>

                                    <label for="text1" class="col-2 col-form-label text-end lh-1">Groupe:</label>
                                    <div class="col-3">
                                        <div class="input-group">
                                            <!-- <input id="text1" name="ligne_budgetaire" type="text" required="required"
                                                class="form-control  py-0"> -->
                                                <select name="groupe_id" id="" class="form-control  py-0 my-0">
                                                    <option value=""></option>
                                                    @foreach ($groupes as $g)
                                                        <option value="{{ $g->id }}"
                                                            {{ $g->id == $produit->groupe_id ? 'selected ' : '' }}>{{ $g->nom }}
                                                        </option>
                                                    @endforeach
                                                  </select>
                                        </div>
                                    </div>


                        </div>

                    </div>

                </fieldset>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let tva;
            $(".tva").keyup(function() {
                tva = $('.tva').val()
                console.log(tva);
            });
        })
    </script>
@endpush
