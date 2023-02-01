

@extends('layouts.dashboard.app')

@section('styles')
    <style>


        .dataTables_wrapper .dataTables_length {
            float: left;
            text-align: start;
            padding-top: 0em;
        }
    </style>
@endsection


@section('content')
    <section class="content">
        <div>
            <h2>factures</h2>
        </div>

        <ul class="breadcrumb mt-2">
            <li class="breadcrumb-item"><a href="">Acceuil</a></li>
            <li class="breadcrumb-item"><a href="">Paramétrage</a></li>
            <li class="breadcrumb-item">factures</li>
        </ul>





        <div class="box box-primary">

            <div class="box-header">
                <div class="row mb-2">

                    <div class="col-md-12">


                        <a href="{{ route('factures.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Ajouter</a>

                    </div>

                </div><!-- end of row -->
            </div>

            <div class="row">

                <div class="col-md-12">

                    <div class="table-responsive">

                        <table class="table datatable" id="factures-table" style="width: 100%;">
                            <thead>
                            <tr>

                                <th>No Facutre</th>
                                <th>No pv</th>
                                <th>Montant</th>
                                <th>Date depot</th>
                                <th>No registre</th>
                                {{-- <th>Categorie du facture</th> --}}
                                <th>Actions</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($factures as $f)
                                    <tr>

                                        <td>{{ $f->n_facture }}</td>
                                        <td>{{ $f->n_pv }}</td>
                                        <td>{{ $f->montant }}</td>
                                        <td>{{ $f->date_depot }}</td>
                                        <td>{{ $f->n_registre }}</td>
                                        <td>
                                            <a href="{{ route('factures.edit', $f->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>

                                            {{-- <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete{{$f->id}}">
                                                <i class="fa fa-trash"></i>
                                              </button> --}}

                                        </td>
                                        <td>
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="delete{{$f->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">{{$f->n_facture}}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{route('factures.destroy',$f->id)}}" method="post">
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


        </div>

    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#factures-table').DataTable({});
        })
    </script>
@endpush
