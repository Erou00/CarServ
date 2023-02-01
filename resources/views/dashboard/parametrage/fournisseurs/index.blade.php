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
                            <select id="ville" class="form-control select2" required>
                                <option value="">Chercher par Ville</option>
                                @foreach ($villes as $v)
                                    <option value="{{ $v->id }}" {{ $v->id == request()->v_id ? 'selected' : '' }}>{{ $v->nom }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <input type="text" id="data-table-search" class="form-control" autofocus placeholder="chercher">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <a href="{{ route('fournisseurs.create') }}" class="btn btn-default">
                                <i class="fa fa-plus"></i>
                                Ajouter</a>
                                <a href="{{route('fournisseurs.print')}}" class="btn btn-default">
                                    <i class="fa fa-print"></i>Imprimer
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
                <legend class="p-2">Les Fournisseurs</legend>


            <div class="row">

                <div class="col-md-12">

                    <div class="table-responsive">

                        <table class="table datatable" id="fournisseurs-table" style="width: 100%;">
                            <thead>
                            <tr>
                                <th>Ville</th>
                                <th>Nom</th>
                                <th>Representant</th>
                                <th>Adresse</th>
                                <th>Telephone</th>
                                <th>Fax</th>
                                <th>Email</th>
                                <th>Site Web</th>
                                <th>Patente</th>
                                {{-- <th>Categorie du fournisseur</th> --}}
                                <th>Actions</th>
                            </tr>
                            </thead>
                        </table>

                    </div><!-- end of table responsive -->

                </div><!-- end of col -->

            </div><!-- end of row -->

           </fieldset>
        </div>

    </section>
@endsection

@push('scripts')
    <script>



        let ville = "{{ request()->ville_id }}";
        let c_fourinisseur = "{{ request()->c_fourinisseur_id }}";

        let fournisseursTable = $('#fournisseurs-table').DataTable({
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
                url: '{{ route('fournisseurs.data') }}',
                data: function (d) {
                    d.ville_id = ville;
                    d.c_fournisseur_id = c_fourinisseur;

                }
            },
            columns: [
                {data: 'ville', name: 'ville'},
                {data: 'nom', name: 'nom'},
                {data: 'representant', name: 'representant'},
                {data: 'adresse', name: 'adresse'},
                {data: 'telephone', name: 'telephone'},
                {data: 'fax', name: 'fax'},
                {data: 'email', name: 'email'},
                {data: 'siteweb', name: 'siteweb'},
                {data: 'patente', name: 'patente'},
                // {data: 'cat_fournisseur', name: 'cat_fournisseur'},
                {data: 'actions', name: 'actions', searchable: false, sortable: false},
            ],
            order: [[5, 'desc']],
            drawCallback: function (settings) {
                $('.record__select').prop('checked', false);
                $('#record__select-all').prop('checked', false);
                $('#record-ids').val();
                $('#bulk-delete').attr('disabled', true);
            }
        });

        $('#ville').on('change', function () {
            genre = this.value;
            fournisseursTable.ajax.reload();
        })


        $('#categorie').on('change', function () {
            type = this.value;
            fournisseursTable.ajax.reload();
        })

        $('#data-table-search').keyup(function () {
            fournisseursTable.search(this.value).draw();
        })


    </script>
@endpush
