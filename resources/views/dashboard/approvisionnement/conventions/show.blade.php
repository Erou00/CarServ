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

        .modal-body.conventionDetailsForm {
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
            <form action="{{ route('conventions.update', $convention->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row mb-0">
                    <div class="col-md-6 offset-md-6 d-lg-flex  justify-content-end">
                        <a href="{{ route('conventions.rapport', $convention->id) }}" name="imprimer" value="imprimer"
                            class="btn btn-default me-1">
                            <i class="fa fa-print"></i> Imprimer</a>
                        <button type="button" class="btn btn-default me-1" id="modifier">
                            <i class="fa fa-edit me-2"></i> Modifier</button>
                        <button class="btn btn-default" type="submit" id="submit">
                            <i class="fa fa-floppy-o me-1" aria-hidden="true"></i>Enregistrer</button>
                    </div>
                </div>

                <fieldset class="form-group p-3 mt-2">
                    <legend class="p-2">{{ $convention->no_convention }} :
                        <div>

                        </div>
                    </legend>

                    @include('dashboard.partials._errors')

                    <div class="form-group row">
                        <label for="text1" class="col-2 col-form-label text-end">N° convention:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <input id="text1" name="no_convention" type="text" required="required"
                                    class="form-control disableClass" disabled value="{{ $convention->no_convention }}">
                            </div>
                        </div>

                        <label for="text1" class="col-1 col-form-label">Fournisseur:</label>
                        <div class="col-3">
                            <select class="form-control select disableClass" name="fournisseur_id" disabled>
                                <option value=""></option>
                                @foreach ($fournisseurs as $f)
                                    <option value="{{ $f->id }}"
                                        {{ $f->id == $convention->fournisseur_id ? 'selected' : '' }}>
                                        {{ $f->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="text1" class="col-1 col-form-label text-end">TVA %:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <input id="text1" name="tva" disabled type="number" required="required"
                                    class="form-control tva disableClass" value="{{ $convention->tva }}">
                            </div>
                        </div>
                    </div>



                    <div class="form-group row mt-2">
                        <label for="text1" class="col-2 col-form-label text-end">Ligne budgetaire:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <input id="text1" name="ligne_budgetaire" disabled type="text" required="required"
                                    class="form-control disableClass" value="{{ $convention->ligne_budgetaire }}">
                            </div>
                        </div>

                        <label for="text1" class="col-1 col-form-label text-end">ODS:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <input id="text1" name="ods" disabled type="text" required="required"
                                    class="form-control disableClass" value="{{ $convention->ods }}">
                            </div>
                        </div>

                        <label for="text1" class="col-2 col-form-label text-end">Delais d'execution:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <input id="text1" disabled name="delais_execution" type="number" required="required" placeholder=""
                                    class="form-control disableClass" value="{{ $convention->delais_execution }}">
                            </div>
                        </div>

                    </div>

                    <div class="form-group row mt-2">
                        <label for="text1" class="col-2 col-form-label text-end">Magasin:</label>
                        <div class="col-3">
                            <select class="form-control select disableClass" name="sous_magasin_id"
                            disabled>
                                <option value=""></option>
                                @foreach (Auth::user()->sousmagasins as $sm)
                                    <option value="{{ $sm->id }}"
                                        {{ $sm->id == $convention->sous_magasin_id ? 'selected' : '' }}>
                                        {{ $sm->nom}}</option>
                                @endforeach
                            </select>
                        </div>

                        <label for="text1" class="col-2 col-form-label text-end">Objet:</label>
                        <div class="col-5">
                            <textarea id="textarea" disabled name="objet" cols="40" rows="2" class="form-control disableClass">{{ $convention->objet }}</textarea>
                            <label for="" class="mt-2">
                                <i class="fas fa-funnel-dollar"></i>Crée Par :
                                {{ $convention->user->nom . ' ' . $convention->user->prenom }}, Le:
                                <i class="fa fa-calendar"></i> {{ date('d/m/Y', strtotime($convention->created_at)) }}
                            </label>
                        </div>


                    </div>

                </fieldset>

            </form>

            <ul class="nav nav-tabs mt-2" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                        type="button" role="tab" aria-controls="home" aria-selected="true">
                        <h6><strong>Details de convention</strong></h6>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                        role="tab" aria-controls="profile" aria-selected="false">
                        <h6><strong>Historiques de convention</strong></h6>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bls" type="button"
                        role="tab" aria-controls="bls" aria-selected="false">
                        <h6><strong>B.L({{ $convention->bls->count() }})</strong></h6>
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">


                        <div class="col-md-2">
                            <button type="button" class="btn btn-default my-3 mx-3 w-100" data-bs-toggle="modal"
                                data-bs-target="#addModal">
                                <i class="fa fa-plus-square" aria-hidden="true"></i> Ajouter</button>
                        </div>
                        <div class="col-md-12">



                            <div class="tile shadow">

                                <div class="container-fluid">

                                    <div class="row">

                                        <div class="col-md-12">

                                            <div class="table-responsive">
                                                @if (Session::has('error') || Session::has('success'))
                                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
                                                        {{ Session::get('error') }}</p>
                                                @endif

                                                <table class="table datatable mt-2" id="conventions-table"
                                                    style="width: 100%;">
                                                    <thead>
                                                        <tr>

                                                            <th>Code</th>
                                                            <th>Designation</th>
                                                            <th>Unite</th>
                                                            <th>Qte</th>
                                                            <th>P.U.H.T</th>
                                                            <th>TVA</th>
                                                            <th>Qte livrée</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($convention->conventionDetails->sortBy('created_at') as $s)
                                                            <tr>

                                                                <td>{{ $s->produit->id }}</td>
                                                                <td>{{ $s->produit->designation }}</td>
                                                                <td>{{ $s->produit->uniteReglementaire->code }}</td>
                                                                <td>{{ $s->qte }}</td>
                                                                <td>{{ $s->puht }}</td>
                                                                <td>{{ $s->tva }}</td>
                                                                <td>
                                                                    @php
                                                                        $qte = 0;
                                                                    @endphp
                                                                    @foreach ($convention->bls as $bl)
                                                                        @foreach ($bl->blDetails as $item)
                                                                            @if ($item->produit_id == $s->produit->id)
                                                                                @php
                                                                                    $qte += $item->qte_livree;
                                                                                @endphp
                                                                            @endif
                                                                        @endforeach
                                                                    @endforeach
                                                                    {{ $qte }}
                                                                </td>

                                                                <td>


                                                                    <button type="button" class="btn btn-sm"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#updateModal{{ $s->id }}">
                                                                        <i class="fa fa-edit"
                                                                            style="font-weight: bold; color: rgb(0, 0, 0)"></i><span
                                                                            style="color: rgb(209, 145, 49)">Modifier</span>
                                                                    </button>

                                                                    <div class="modal fade"
                                                                        id="updateModal{{ $s->id }}"
                                                                        tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title"
                                                                                        id="exampleModalLabel">
                                                                                        {{ $s->produit->designation }}</h5>
                                                                                    <button type="button"
                                                                                        class="btn-close"
                                                                                        data-bs-dismiss="modal"
                                                                                        aria-label="Close"></button>
                                                                                </div>
                                                                                <div
                                                                                    class="container  conventionDetailsForm">
                                                                                    <form
                                                                                        action="{{ route('conventions.updateStock', $s->id) }}"
                                                                                        method="post">
                                                                                        @csrf
                                                                                        @method('PUT')
                                                                                        <update-convention-details-form
                                                                                            :qte="{{ $s->qte }}"
                                                                                            :puht="{{ $s->puht }}"
                                                                                            :tva="{{ $convention->tva }}"
                                                                                            :prixtotal="{{ $s->prix_total }}" />


                                                                                    </form>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                    <button type="button" class="btn btn-sm"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#deleteModal{{ $s->id }}">
                                                                        <i class="fa fa-trash"
                                                                            style="font-weight: bold; color: rgb(0, 0, 0)"></i><span
                                                                            style="color: rgb(209, 145, 49)">Supprimer</span>
                                                                    </button>

                                                                    <div class="modal fade"
                                                                        id="deleteModal{{ $s->id }}"
                                                                        tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-footer delete-form">
                                                                                    {{ $s->produit->designation }}
                                                                                    <form
                                                                                        action="{{ route('conventions.deleteStock', $s->id) }}"
                                                                                        method="post">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <button type="button"
                                                                                            class="btn btn-default btn-sm"
                                                                                            data-bs-dismiss="modal">
                                                                                            <i class="fa fa-close"></i>
                                                                                            Annuler</button>
                                                                                        <button type="submit"
                                                                                            class="btn btn-default btn-sm">
                                                                                            <i
                                                                                                class="fa fa-trash"></i>Supprimer</button>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                </td>


                                                            </tr>
                                                            <!-- Modal -->
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                            </div><!-- end of table responsive -->

                                        </div><!-- end of col -->

                                    </div><!-- end of row -->

                                </div>


                            </div><!-- end of tile -->

                        </div><!-- end of col -->

                    </div><!-- end of row -->
                </div>
                <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="container-fluid mt-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">

                                    <table class="table datatable" id="historiques-table" style="width: 100%;">
                                        <thead>
                                            <tr>

                                                <th>Modifier par</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($convention->historiqueConventions->sortBy('created_at') as $hc)
                                                <tr>
                                                    <td>{{ $hc->user->nom . ' ' . $hc->user->prenom }}</td>
                                                    <td>{{ date('d/m/Y', strtotime($hc->created_at)) }}</td>
                                                </tr>
                                                <!-- Modal -->
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div><!-- end of table responsive -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="bls" role="tabpanel" aria-labelledby="bls-tab">
                    <div class="container-fluid mt-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">

                                    <table class="table datatable" id="bls-table" style="width: 100%;">
                                        <thead>
                                            <tr>

                                                <th>N Bl</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($convention->bls->sortBy('created_at') as $bl)
                                                <tr>
                                                    <td>{{ $bl->no_bl }}</td>
                                                    <td>{{ date('d/m/Y', strtotime($bl->date)) }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#blDetails{{ $bl->id }}">
                                                            <i class="fa fa-eye me-1" style="font-weight: bold; color: black;"></i><span style="color: rgb(209, 145, 49)">Voir</span>
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="blDetails{{ $bl->id }}"
                                                            tabindex="-1" aria-labelledby="exampleModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog add-modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            <strong>{{ $bl->no_bl }}</strong>
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <table class="table datatable"
                                                                            id="bls-details-table" style="width: 100%;">
                                                                            <thead>
                                                                                <tr>

                                                                                    <th>Code</th>
                                                                                    <th>Designationt</th>
                                                                                    <th>Unite</th>
                                                                                    <th>Qte</th>
                                                                                    <th>Qte livree </th>
                                                                                    <th>Magasin</th>

                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($bl->blDetails as $bld)
                                                                                    <tr>

                                                                                        <td>{{ $bld->produit->id }}</td>
                                                                                        <td>{{ $bld->produit->designation }}
                                                                                        </td>
                                                                                        <td>{{ $bld->produit->uniteReglementaire->code }}
                                                                                        </td>
                                                                                        <td>{{ $bld->qte }}</td>
                                                                                        <td>{{ $bld->qte_livree }}</td>
                                                                                        <td>{{ $bld->magasin->nom }}</td>

                                                                                    </tr>
                                                                                    <!-- Modal -->
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Annuler</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>




                                                    <!-- Modal -->
                                            @endforeach
                                            </tr>
                                        </tbody>
                                    </table>

                                </div><!-- end of table responsive -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


                <!--Add Modal -->
                <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog add-modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ajouter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <add-convention-details-form
                            :mid="{{$convention->id}}" :tva="{{$convention->tva}}" />
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                    </div>
                </div>

    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // table = $('#conventions-table').DataTable()
            var disabled = true;

            $('#modifier').click(function(e) {
                e.preventDefault();
                console.log('test');
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

        });
    </script>
@endpush
