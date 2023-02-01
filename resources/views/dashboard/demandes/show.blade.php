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

        .modal-body.demandeDetailsForm {
            padding: 0rem 1rem;
            height: 150px !important;
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
            <form action="{{ route('demandes.update', $demande->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row mb-0">
                    <div class="col-md-6 offset-md-6 d-lg-flex  justify-content-end">
                        <a href="{{ route('demandes.rapport', $demande->id) }}" name="imprimer" value="imprimer"
                            class="btn btn-default me-1">
                            <i class="fa fa-print"></i> Imprimer</a>
                        <button type="button" class="btn btn-default me-1" id="modifier">
                            <i class="fa fa-edit me-2"></i> Modifier</button>
                        <button class="btn btn-default" type="submit" id="submit">
                            <i class="fa fa-floppy-o me-1" aria-hidden="true"></i>Enregistrer</button>
                    </div>
                </div>

                <fieldset class="form-group p-3 mt-2">
                    <legend class="p-2">{{  $demande->no_commande.'/'.$demande->annee  }} :
                        <div>

                        </div>
                    </legend>
                    @include('dashboard.partials._errors')
                    <div class="form-group row mt-1">
                        <label for="text1" class="col-2 col-form-label text-end">No demande:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <input type="text" id="no_demande" name="" class="form-control"
                                    readonly value="{{ $demande->no_commande.'/'.$demande->annee }}" required>
                            </div>
                        </div>
                        <label for="text1" class="col-1 col-form-label text-end">Date:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <input type="date" id="date_demande" name="date_commande"
                                    class="form-control disableClass" disabled value="{{ $demande->date_commande }}">
                            </div>
                        </div>
                        <label for="text1" class="col-1 col-form-label">Entite:</label>
                        <div class="col-4">
                            <select class="form-control select disableClass" name="entite_id" disabled>
                                <option value=""></option>
                                @foreach ($entites as $e)
                                    <option value="{{ $e->id }}"
                                        {{ $e->id == $demande->entite_id ? 'selected' : '' }}>
                                        {{ $e->nom }}</option>
                                @endforeach
                            </select>
                        </div>


                        <label for="text1" class="col-2 col-form-label text-end">Facture:</label>
                        <div class="col-2">
                            <select class="form-control select disableClass" name="facture_id"
                            disabled>
                                <option value=""></option>
                                @foreach ($factures as $f)
                                    <option value="{{ $e->id }}"
                                        {{ $f->id == $demande->facture_id ? 'selected' : '' }}>
                                        {{ $f->n_facture }}</option>
                                @endforeach
                            </select>
                        </div>


                        <label for="text1" class="col-1 col-form-label text-end">Magasin:</label>
                        <div class="col-2">
                            <select class="form-control select disableClass" name="sous_magasin_id"
                            disabled>
                                <option value=""></option>
                                @foreach (Auth::user()->sousmagasins as $sm)
                                    <option value="{{ $sm->id }}"
                                        {{ $sm->id == $demande->sous_magasin_id ? 'selected' : '' }}>
                                        {{ $sm->nom}}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>

                    <div class="container">
                        <div class="row">
                            <label for="" class="mt-3 text-end col-4 offset-8">
                                <i class="fas fa-funnel-dollar"></i>Crée Par :
                                {{ $demande->user->nom . ' ' . $demande->user->prenom }}, Le:
                                <i class="fa fa-calendar"></i> {{ date('d/m/Y', strtotime($demande->created_at)) }}
                            </label>
                        </div>
                    </div>



                </fieldset>

            </form>

            <ul class="nav nav-tabs mt-2" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                        type="button" role="tab" aria-controls="home" aria-selected="true">
                        <h6><strong>Details de demande</strong></h6>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                        role="tab" aria-controls="profile" aria-selected="false">
                        <h6><strong>Historiques de demande</strong></h6>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bs" type="button"
                        role="tab" aria-controls="bs" aria-selected="false">
                        <h6><strong>B.L({{ $demande->bs->count() }})</strong></h6>
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">



                        <div class="col-md-2 my-3">
                            @if ($demande->bs->count() == 0)
                                <button type="button" class="btn btn-default mx-3 w-100" data-bs-toggle="modal"
                                    data-bs-target="#addModal">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i> Ajouter
                                </button>
                            @endif
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

                                                <table class="table datatable" id="demandes-table" style="width: 100%;">
                                                    <thead>
                                                        <tr>

                                                            <th scope="col">Code</th>
                                                            <th scope="col">Designation</th>
                                                            <th scope="col">Unité</th>
                                                            <th scope="col">Qte Stock</th>
                                                            <th scope="col">Qté Demandee</th>
                                                            <th>Actions</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($demande->demandeDetails->sortBy('created_at') as $details)
                                                            <tr>

                                                                <td>{{ $details->produit->id }}</td>
                                                                <td>{{ $details->produit->designation }}</td>
                                                                <td>{{ $details->produit->uniteReglementaire->code }}</td>
                                                                <td>
                                                                    @php
                                                                    $qte_stock = 0
                                                                     @endphp
                                                                    @foreach ($details->produit->stocks as $s )
                                                                        @if ($demande->magasin_id  == $s->magasin_id)
                                                                            @php
                                                                                $qte_stock = $s->qte
                                                                            @endphp

                                                                          {{  $qte_stock }}

                                                                        @endif
                                                                    @endforeach

                                                                </td>
                                                                <td>{{ $details->qte_demandee }}</td>
                                                                <td>

                                                                    <button type="button" class="btn btn-sm"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#updateModal{{ $details->id }}">
                                                                        <i class="fa fa-edit"
                                                                            style="font-weight: bold; color: rgb(0, 0, 0)"></i><span
                                                                            style="color: rgb(209, 145, 49)">Modifier</span>
                                                                    </button>

                                                                    <button type="button" class="btn  btn-sm"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteModal{{$details->id }}">
                                                                    <i class="fa fa-trash"
                                                                        style="font-weight: bold; color: rgb(0, 0, 0)"></i><span
                                                                        style="color: rgb(209, 145, 49)">Supprimer</span>
                                                                </button>


                                                                <div class="modal fade"
                                                                id="updateModal{{ $details->id }}" tabindex="-1"
                                                                aria-labelledby="exampleModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLabel">
                                                                                {{ $details->produit->designation }}</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body demandeDetailsForm">
                                                                            <form action="{{route('demandes.updateDetails',$details->id)}}" method="post">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <update-demande-details-form
                                                                                    :qtedemandee="{{ $details->qte_demandee }}"
                                                                                />


                                                                            </form>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal fade" id="deleteModal{{$details->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                  <div class="modal-content">

                                                                    <div class="modal-footer delete-form">
                                                                        {{ $details->produit->designation }}
                                                                       <form action="{{route('demandes.deleteDetails',$details->id)}}" method="post">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button" class="btn btn-default " data-bs-dismiss="modal"><i class="fa fa-close me-1"></i>Annuler</button>
                                                                        <button type="submit" class="btn btn-default"><i class="fa fa-trash"></i> Supprimer</button>
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
                                            @foreach ($demande->historiquedemandes->sortBy('created_at') as $hc)
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

                <div class="tab-pane" id="bs" role="tabpanel" aria-labelledby="bs-tab">
                    <div class="container-fluid mt-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">

                                    <table class="table datatable" id="bs-table" style="width: 100%;">
                                        <thead>
                                            <tr>

                                                <th>N Bl</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($demande->bs->sortBy('created_at') as $bl)
                                                <tr>
                                                    <td>{{ $bl->no_bl.'/'.$bl->annee }}</td>
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
                                                                            id="bs-details-table" style="width: 100%;">
                                                                            <thead>
                                                                                <tr>

                                                                                    <th>Code</th>
                                                                                    <th>Designationt</th>
                                                                                    <th>Unite</th>
                                                                                    <th>Qte Demandée</th>
                                                                                    <th>Qte Acordéee </th>
                                                                                    <th>Magasin</th>

                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($bl->bsDetails as $bld)
                                                                                    <tr>

                                                                                        <td>{{ $bld->produit->id }}</td>
                                                                                        <td>{{ $bld->produit->designation }}
                                                                                        </td>
                                                                                        <td>{{ $bld->produit->uniteReglementaire->code }}
                                                                                        </td>
                                                                                        <td>{{ $bld->qte_demandee }}</td>
                                                                                        <td>{{ $bld->qte_donnee }}</td>
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
                  <add-demande-details-form     :details="{{ $demande->demandeDetails }}"  :id="{{$demande->id}}"/>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>


    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // table = $('#demandes-table').DataTable()
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
