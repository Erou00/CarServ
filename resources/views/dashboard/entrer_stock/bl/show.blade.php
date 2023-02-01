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

        .modal-body.blDetailsForm {
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
            <form action="{{ route('bls.update', $bl->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row mb-0">
                    <div class="col-md-6 offset-md-6 d-lg-flex  justify-content-end">
                        <a href="{{ route('bls.rapport', $bl->id) }}" name="imprimer" value="imprimer"
                            class="btn btn-default me-1">
                            <i class="fa fa-print"></i> Imprimer</a>
                        <button type="button" class="btn btn-default me-1" id="modifier">
                            <i class="fa fa-edit me-2"></i> Modifier</button>
                        <button class="btn btn-default" type="submit" id="submit">
                            <i class="fa fa-floppy-o me-1" aria-hidden="true"></i>Enregistrer</button>
                    </div>
                </div>

                <fieldset class="form-group p-3 mt-2">
                    <legend class="p-2">{{ $bl->no_bl }}
                    </legend>
                    @include('dashboard.partials._errors')
                    <div class="form-group row mt-1">
                        <label for="text1" class="col-2 col-form-label text-end">No bl:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <input type="text" id="no_bl" name="no_bl" class="form-control disableClass"
                                    disabled value="{{ $bl->no_bl }}" required>
                            </div>


                        </div>
                        <label for="text1" class="col-1 col-form-label text-end">Date:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <input type="date" id="date_bl" name="date"
                                    class="form-control disableClass" disabled value="{{ $bl->date }}">
                            </div>
                        </div>

                        @if (!$bl->commande_id)
                            <label for="text1" class="col-1 col-form-label">Retard:</label>
                            <div class="col-3">
                            <input type="number" id="retard" name="retard"
                            class="form-control" disabled
                            value="{{ $bl->retard }}">
                            </div>
                        @endif

                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-2"></div>
                        <label for="" class="col-4  mt-3">
                            <i class="fas fa-funnel-dollar"></i>Crée Par :
                            {{ $bl->user->nom . ' ' . $bl->user->prenom }}, Le:
                            <i class="fa fa-calendar"></i> {{ date('d/m/Y', strtotime($bl->created_at)) }}
                        </label>
                    </div>


                </fieldset>

            </form>

            <ul class="nav nav-tabs mt-2" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                        type="button" role="tab" aria-controls="home" aria-selected="true">
                        <h6><strong>Details de bl</strong></h6>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                        role="tab" aria-controls="profile" aria-selected="false">
                        <h6><strong>Historiques de bl</strong></h6>
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


                                                <table class="table datatable" id="bls-table" style="width: 100%;">
                                                    <thead>
                                                        <tr>

                                                            <th>Code</th>
                                                            <th>Designationt</th>
                                                            <th>Unite</th>
                                                            <th>Qte</th>
                                                            <th>Qte livree </th>
                                                            <th>Magasin</th>
                                                            <th>Actions</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($bl->blDetails as $bld)
                                                            <tr>

                                                                <td>{{ $bld->produit->id }}</td>
                                                                <td>{{ $bld->produit->designation }}</td>
                                                                <td>{{ $bld->produit->uniteReglementaire->code }}</td>
                                                                <td>{{ $bld->qte }}</td>
                                                                <td>{{ $bld->qte_livree }}</td>
                                                                <td>{{ $bld->magasin->nom }}</td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#updateModal{{ $bld->id }}">
                                                                        <i class="fa fa-edit"
                                                                            style="font-weight: bold; color: rgb(0, 0, 0)"></i><span
                                                                            style="color: rgb(209, 145, 49)">Modifier</span>
                                                                    </button>

                                                                    <div class="modal fade"
                                                                        id="updateModal{{ $bld->id }}" tabindex="-1"
                                                                        aria-labelledby="exampleModalLabel"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title"
                                                                                        id="exampleModalLabel">
                                                                                        {{ $bld->produit->designation }}</h5>
                                                                                    <button type="button" class="btn-close"
                                                                                        data-bs-dismiss="modal"
                                                                                        aria-label="Close"></button>
                                                                                </div>
                                                                                <div class="container">

                                                                                    <form action="{{route('blDetailsUpdate',$bld->id)}}" method="post">
                                                                                        @csrf
                                                                                        @method('PUT')
                                                                                        <update-bl-details-form
                                                                                            :ql="{{ $bld->qte_livree }}"
                                                                                            :magasin="{{ $bld->magasin_id }}"
                                                                                            :qte="{{$bld->qte}}"
                                                                                            />


                                                                                    </form>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{$bld->id}}">
                                                                        <i class="fa fa-trash"
                                                                            style="font-weight: bold; color: rgb(0, 0, 0)"></i><span
                                                                            style="color: rgb(209, 145, 49)">Supprimer</span>
                                                                    </button>

                                                                    <div class="modal fade" id="deleteModal{{$bld->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                          <div class="modal-content">
                                                                            <div class="modal-footer delete-form">
                                                                                {{ $bld->produit->designation }}
                                                                                <form
                                                                                    action="{{route('deleteBlDetails',$bld->id)}}"
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
                                                                <td>


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
                                            @foreach ($bl->historiqueBls->sortBy('created_at') as $hc)
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

                  <add-bl-details-form  :blid="{{$bl->id}}"
                    :marche ="{{ ($bl->marche) ? $bl->marche->marcheDetails : (($bl->commande) ? $bl->commande->commandeDetails : $bl->convention->conventionDetails) }}"
                    :bld="{{$bl->blDetails}}"   :id="{{($bl->commande) ? $bl->commande_id: ($bl->marche  ? $bl->marche_id : $bl->convention_id )}}"/>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Annuler
                </button>
                </div>
            </div>
            </div>
        </div>

    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // table = $('#bls-table').DataTable()
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
