@extends('layouts.dashboard.app')



@section('content')

<section class="content">


            @include('dashboard.partials._errors')


                <form action="{{ route('fournisseurs.store') }}" method="post">

                {{ csrf_field() }}
                {{ method_field('post') }}

                    <div class="row">
                        <div class="col-md-6 offset-md-6 d-lg-flex  justify-content-end">
                            <div class="form-group mt-1">
                                <button type="submit" class="btn btn-default"><i class="fa fa-plus"></i> Enregistrer</button>
                            </div>
                        </div>
                    </div>
                    <fieldset class="form-group p-3 mt-2">
                        <legend class="p-2">{{ $fournisseur->nom }}</legend>

                <div class="rendered-form">
                    <div class="formbuilder-select form-group row">
                        <label for="ville_id" class="formbuilder-select-label col-1 text-end">Ville:<span class="formbuilder-required"></span></label>
                      <div class="col-2">
                        <select class="form-control select" name="ville_id" id="ville_id"  aria-required="true">
                            <option value="" selected="true" id=""></option>
                            @foreach ($villes as $ville)
                            <option value="{{$ville->id}}" id="{{$ville->id}}" {{ $fournisseur->ville_id == $ville->id ? 'selected' : ''  }}>{{$ville->nom}}</option>
                            @endforeach
                        </select>
                      </div>

                      <label for="nom" class="formbuilder-text-label col-1 text-end">Nom:<span class="formbuilder-required">*</span></label>
                      <div class="col-2">
                        <input type="text" class="form-control" name="nom" access="false" id="nom" value="{{ $fournisseur->nom }}" required="required" aria-required="true">
                      </div>


                      <label for="representant" class="formbuilder-text-label col-1 text-end">Representant:<span class="formbuilder-required"></span></label>
                      <div class="col-2">
                        <input type="text" class="form-control" name="representant" value="{{ $fournisseur->representant }}" access="false" id="representant"  aria-required="true">
                      </div>

                      <label for="adresse" class="formbuilder-text-label col-1 text-end">Adresse:<span class="formbuilder-required"></span></label>
                        <div class="col-2">
                            <input type="text" class="form-control" name="adresse" value="{{ $fournisseur->adresse }}" access="false" id="adresse"  aria-required="true">
                        </div>


                    </div>

                    {{-- <div class="formbuilder-text form-group field-representant">
                    </div> --}}
                    <div class="formbuilder-text form-group field-adresse row my-2">


                        <label for="telephone" class="formbuilder-text-label col-1 text-end">Telephone:<span class="formbuilder-required"></span></label>
                        <div class="col-2">
                             <input type="tel" class="form-control" name="telephone" value="{{ $fournisseur->telephone }}" access="false" id="telephone"  aria-required="true">
                        </div>



                        <label for="fax" class="formbuilder-text-label col-1 text-end">Fax<span class="formbuilder-required"></span></label>
                        <div class="col-2">
                            <input type="text" class="form-control" name="fax" value="{{ $fournisseur->fax }}" access="false" id="fax"  aria-required="true">
                        </div>

                        <label for="email" class="formbuilder-text-label col-1 text-end">Email:<span class="formbuilder-required"></span></label>
                        <div class="col-2">
                          <input type="email" class="form-control" name="email" value="{{ $fournisseur->email }}" access="false" id="email"  aria-required="true">
                         </div>


                         <label for="siteweb" class="formbuilder-text-label col-1 text-end">Site Web:</label>
                         <div class="col-2">
                             <input type="text" class="form-control" name="siteweb" value="{{ $fournisseur->siteweb }}" access="false" id="siteweb">
                         </div>


                    </div>

                    <div class="formbuilder-text form-group field-email row">


                        <label for="patente" class="formbuilder-text-label col-1 text-end">Patente:</label>
                        <div class="col-2">
                            <input type="text" class="form-control" name="patente" value="{{ $fournisseur->patente }}" access="false" id="patente">
                        </div>


                    </div>

                </div>



            </form><!-- end of form -->

            </fieldset>


</section>
@endsection

@push('scripts')


<script>


</script>

@endpush

