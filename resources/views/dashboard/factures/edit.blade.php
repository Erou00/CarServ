@extends('layouts.dashboard.app')



@section('content')

<section class="content">
  <div>
        <h2>Factures</h2>
    </div>

    <ul class="breadcrumb mt-2">
        <li class="breadcrumb-item"><a href="">Acceuil</a></li>
        <li class="breadcrumb-item"><a href="">Paramétrage</a></li>
        <li class="breadcrumb-item"><a  href="">Factures</a> </li>
        <li class="breadcrumb-item">Editer</li>
    </ul>


    <div class="box box-primary">

        <div class="box-header">
            {{-- <h3 class="box-title">@lang('site.add')</h3> --}}
        </div><!-- end of box header -->
        <div class="box-body">
            @include('dashboard.partials._errors')

            <form action="{{ route('factures.update', $facture->id) }}" method="post">

                {{ csrf_field() }}
                {{ method_field('put') }}


                <div class="rendered-form">

                    <div class="formbuilder-text form-group field-no_facture">
                        <label for="no_facture" class="formbuilder-text-label">No Facture<span class="formbuilder-required">*</span></label>
                        <input type="text" class="form-control" name="n_facture" access="false" id="no_facture" required="required"
                        aria-required="true" value="{{$facture->n_facture}}">
                    </div>
                    <div class="formbuilder-text form-group field-n_pv">
                        <label for="n_pv" class="formbuilder-text-label">N pv<span class="formbuilder-required">*</span></label>
                        <input type="text" class="form-control" name="n_pv" access="false" id="n_pv"
                         required="required" aria-required="true" value="{{$facture->n_pv}}">
                    </div>
                    <div class="formbuilder-text form-group field-montant">
                        <label for="montant" class="formbuilder-text-label">Montant<span class="formbuilder-required">*</span></label>
                        <input type="text" class="form-control" name="montant" access="false" id="montant" required="required"
                         aria-required="true" value="{{$facture->montant}}" >
                    </div>
                    <div class="formbuilder-text form-group field-date_depot">
                        <label for="date_depot" class="formbuilder-text-label">Date depot<span class="formbuilder-required">*</span></label>
                        <input type="date" class="form-control" name="date_depot" access="false" id="date_depot"
                        required="required" aria-required="true" value="{{$facture->date_depot}}">
                    </div>
                    <div class="formbuilder-text form-group field-n_registre">
                        <label for="n_registre" class="formbuilder-text-label">N registre<span class="formbuilder-required">*</span></label>
                        <input type="text" class="form-control" name="n_registre" access="false" id="n_registre" required="required"
                        aria-required="true" value="{{$facture->n_registre}}">
                    </div>


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

