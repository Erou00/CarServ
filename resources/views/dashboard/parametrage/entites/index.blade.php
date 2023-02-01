@extends('layouts.dashboard.app')



@section('content')


<section class="search-sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <form action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <select id="entite" class="form-control select" required>
                                <option value="">Chercher par Entité mére</option>
                                @foreach ($entites as $e)
                                    <option value="{{ $e->id }}" {{ $e->id == request()->entite_id ? 'selected' : '' }}>{{ $e->nom }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <select id="type" class="form-control select" required>
                                <option value="">Type</option>
                                @foreach ($types as $t)
                                    <option value="{{ $t->id }}" {{ $t->id == request()->type_entite_id ? 'selected' : '' }}>{{ $t->type }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <input type="text" id="data-table-search" class="form-control" autofocus placeholder="chercher">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <a href="{{ route('entites.create') }}" class="btn btn-default">
                                <i class="fa fa-plus"></i>
                                Ajouter</a>
                                <a href="{{route('entites.print')}}" class="btn btn-default">
                                    <i class="fa fa-print me-1"></i>Imprimer
                                </a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">

                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
</section>
    <section class="content">

        <div class="box box-primary">

            <fieldset class="form-group p-3 mt-2">
                <legend class="p-2">Les Entitées</legend>



            <div class="row">

                <div class="col-md-12">

                    <div class="tile shadow">

                        <div class="row">

                            <div class="col-md-12">

                                <div class="table-responsive mt-2">

                                    <table class="table datatable" id="entites-table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Nom</th>
                                                <th>Entite Mére</th>
                                                <th>Type</th>
                                                <th>Email</th>
                                                <th>Créé à</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>

                                </div><!-- end of table responsive -->

                            </div><!-- end of col -->

                        </div><!-- end of row -->

                    </div><!-- end of tile -->

                </div><!-- end of col -->

            </div><!-- end of row -->

            </fieldset>

        </div>


    </section>
@endsection

@push('scripts')
    <script>

$(document).ready( function () {
    let entite = "{{ request()->entite_id }}";
    let type = "{{ request()->type_entite_id }}";

    let entitesTable = $('#entites-table').DataTable({
            dom: "tiplr",
            lengthMenu: [
                    [25, 100, -1],
                    [25, 100, "TOUS"]
                ],
                "language": {
                "url": "{{ asset('assets/datatable-lang/fr.json') }}"
            },
            serverSide: true,
            processing: true,
            ajax: {
                url: '{{ route('entites.data') }}',
                data: function (d) {
                    d.entite_id = entite;
                    type_entite_id = type

                }
            },
            columns: [
                {
                    data: 'abbreviation',
                    name: 'abbreviation'
                },
                {
                    data: 'nom',
                    name: 'nom'
                },
                {
                    data: 'entite_mere',
                    name: 'entite_mere'
                },
                {
                    data: 'type_entite',
                    name: 'type_entite'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    searchable: false
                },
                {
                    data: 'actions',
                    name: 'actions',
                    searchable: false,
                    sortable: false,
                    width: '5%'
                },
            ],
            order: [
                [4, 'desc']
            ],
            drawCallback: function(settings) {
                $('.record__select').prop('checked', false);
                $('#record__select-all').prop('checked', false);
                $('#record-ids').val();
                $('#bulk-delete').attr('disabled', true);
            }
        });

        $('#data-table-search').keyup(function() {
            entitesTable.search(this.value).draw();
        })

        $('#entite').on('change', function () {
            entite = this.value;
            entitesTable.ajax.reload();
        });

        $('#type').on('change', function () {
            type = this.value;
            entitesTable.ajax.reload();
        });
} );

    </script>
@endpush
