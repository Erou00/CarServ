@extends('layouts.dashboard.app')

@section('styles')
    <style>
        #demande-modal {
            padding: 10px
        }

        #demande-modal input,
        #demande-modal select,
        #demande-modal textarea {
            padding: 5px;
            margin: 5px
        }

        .dataTables_wrapper .dataTables_length {
            float: left;
            text-align: start;
            padding-top: 0em;
        }
    </style>
@endsection
@section('content')
    <section class="content">




        <div class="box box-primary">

            <div class="box-header">
                <div class="row mb-2">

                    <div class="col-md-12">

                    </div>

                </div><!-- end of row -->

            </div><!-- end of box header -->
            <div class="box-body">
                <div class="row">

                    <div class="col-md-12">

                        <div class="tile shadow">



                            <div class="row">

                                <div class="col-md-12">

                                    <div class="table-responsive">

                                        <table class="table datatable" id="demandes-table" style="width: 100%;">
                                            <thead>
                                                <tr>

                                                    <th>No bl</th>
                                                    <th>Date bl</th>
                                                    <th>Facture</th>
                                                    <th>Retard en jour</th>
                                                    <th>Pour</th>
                                                    <th>Actions</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bls as $bl)
                                                <tr>
                                                    <td>{{$bl->no_bl}}</td>
                                                    <td>{{$bl->date}}</td>

                                                    <td>{{($bl->facture) ? $bl->facture->no_facture : ''}}</td>
                                                    <td>{{ (!$bl->commande) ? ($bl->retard >= 0) ?  'Il reste en cours '.$bl->retard.' jour' :  'Le delai a été depassé par '.abs($bl->retard).' jour' : ''}}</td>
                                                    <td>{!! $bl->marche_id ? '<strong>Marché N°:</strong>' .$bl->marche->no_marche : ($bl->commande_id ? '<strong>Commande N°:</strong>' . $bl->commande->no_commande : '<strong>Convention N°:</strong>' .$bl->convention->no_convention)   !!}</td>
                                                    <td>
                                                        <a href="{{ route('bls.show', $bl->id) }}"
                                                            class="btn btn-success btn-sm">
                                                            <i class="fa fa-eye"></i></a>

                                                            <print-link :id="{{$bl->id}}"
                                                                pour="bls"
                                                                duplicata="{{($bl->imp == true) ? 'duplicata':'non'}}" />


                                                     </td>
                                                    <td>
                                                        <!-- Delete Modal -->
                                                        <div class="modal fade" id="delete{{$bl->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">{{$bl->no_bl}}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{route('bls.destroy',$bl->id)}}" method="post">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary">Supprimer</button>
                                                                      </form>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </td>

                                                </tr>

                                                @endforeach

                                            </tbody>
                                        </table>

                                    </div><!-- end of table responsive -->

                                </div><!-- end of col -->

                            </div><!-- end of row -->

                        </div><!-- end of tile -->

                    </div><!-- end of col -->

                </div><!-- end of row -->

            </div><!-- end of box body -->

        </div><!-- end of box -->




<!-- Button trigger modal -->







    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#demandes-table').dataTable();
            $('.entite').select2({
                dropdownParent: $('.exampleModal')
            });

        });
    </script>
@endpush
