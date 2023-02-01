@extends('layouts.dashboard.app')
@section('styles')
    <style>
        #submit {
            display: none;
        }

        .demandeDetails {
            font-size: 16px;
        }

        hr {
            border-top: 3px solid #3c8dbc;
        }

        .modal-body.inventaireDetailsForm {
            padding: 0rem 1rem;
            height: 300px !important;
            overflow-x: auto;
        }

        .dataTables_wrapper .dataTables_length {
            float: left;
            text-align: start;
            padding-top: 0em;
        }

        .add-modal-dialog {
            max-width: 80%;
        }
    </style>
@endsection


@section('content')

    <section>
        <div class="container mt-2">
            <form action="{{ route('inventaires.update', $inventaire->id) }}" method="post">
                @csrf
                @method('PUT')

                @include('dashboard.partials._errors')

                <div class="row mb-0">
                        <div class="col-md-6 offset-md-6 d-lg-flex justify-content-end">
                        <a
                            href="{{ route('inventaires.rapport', $inventaire->id) }}"
                            name="imprimer"
                            value="imprimer"
                            class="btn btn-default me-1"
                        >
                            <i class="fa fa-print"></i> Imprimer</a
                        >
                        <button type="button" class="btn btn-default me-1" id="modifier">
                            <i class="fa fa-edit me-2"></i> Modifier
                        </button>
                        <button class="btn btn-default" type="submit" id="submit">
                            <i class="fa fa-floppy-o me-1" aria-hidden="true"></i>Enregistrer
                        </button>
                        </div>
                  </div>
                  <fieldset class="form-group p-3 mt-2">
                    <legend class="p-2">{{ $inventaire->no_inventaire }} :</legend>
                    <div class="form-group row mt-1">
                      <label for="text1" class="col-2 col-form-label text-end"
                        >No inventaire:</label
                      >
                      <div class="col-2">
                        <div class="input-group">
                          <input
                            type="text"
                            id="no_inventaire"
                            name="no_inventaire"
                            class="form-control"
                            readonly
                            value="{{$inventaire->no_inventaire}}"
                            required
                          />
                        </div>
                      </div>
                      <label for="text1" class="col-2 col-form-label text-end"
                        >Date Preparation :</label
                      >
                      <div class="col-2">
                        <div class="input-group">
                          <input
                            type="date"
                            id="date_inventaire"
                            name="date_preparation"
                            class="form-control"
                            readonly
                            value="{{$inventaire->date_preparation}}"
                          />
                        </div>
                      </div>
                      <label for="text1" class="col-1 col-form-label">Etat:</label>
                      <div class="col-3">
                        <select class="form-control disableClass etat" name="etat" disabled>
                            @if ($inventaire->etat == 'preparation')
                            <option
                            value="preparation"
                             {{ $inventaire->etat == 'preparation' ? 'selected' :'' }}>Preparation</option>
                            @endif



                          <option
                            value="verification"
                            {{ $inventaire->etat == 'verification' ? 'selected' :'' }}> Verification</option>

                            @if ($inventaire->etat == 'verification' || $inventaire->etat == 'validation')
                            <option
                            value="validation"
                            {{$inventaire->etat == 'validation' ? 'selected' :''}}
                          >Validation</option>
                            @endif

                        </select>
                      </div>
                    </div>

                    <div class="form-group row mt-2">
                      <label for="text1" class="col-2 col-form-label text-end"
                        >Date Verification:</label
                      >
                      <div class="col-2">
                        <div class="input-group">
                          <input
                            id="text1"
                            name="date_verification"
                            type="date"
                            readonly
                            required="required"
                            class="form-control date_verification"
                            value="{{$inventaire->date_verification}}"
                          />
                        </div>
                      </div>

                      <label for="text1" class="col-2 col-form-label text-end"
                        >Date Validation:</label
                      >
                      <div class="col-2">
                        <div class="input-group">
                          <input
                            id="text1"
                            name="date_validation"
                            type="date"
                            readonly
                            class="form-control date_validation"
                            value="{{$inventaire->date_validation}}"
                          />
                        </div>
                      </div>


                    </div>
                  </fieldset>
                <update-inventaire :id="{{$inventaire->id}}">

        </form>
        </div>


               <!--Add Modal -->


    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // table = $('#inventaires-table').DataTable()
            var disabled = true;

            $('#modifier').click(function(e) {
                e.preventDefault();
                if (disabled) {
                    $(".disableClass").prop('disabled', false); // if disabled, enable
                    $('#submit').css({
                        "display": "block"
                    });
                    $('#modifier').html("Annuler");
                } else {
                    $(".disableClass").prop('disabled', true); // if enabled, disable
                    $('#submit').css({
                        "display": "none"
                    });
                    $('#modifier').html("Modifier");
                }
                disabled = !disabled;
            })

            $('.etat').on('change',function(e){
                e.preventDefault();
                let dateNow = new Date().toISOString().substr(0, 10)
                if ($('.etat').val() == 'verification') {
                    $(".date_verification").val(dateNow)
                }
                if ($('.etat').val() == 'validation') {
                    $(".date_validation").val(dateNow)
                }


            })

        });
    </script>
@endpush
