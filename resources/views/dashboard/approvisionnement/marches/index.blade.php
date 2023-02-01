@extends('layouts.dashboard.app')

@section('styles')

@endsection
@section('content')
<section class="search-sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <form action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <input type="text" class="form-control search-slt no_marche"
                                placeholder="N° Marché">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <input type="text" class="form-control search-slt objet" placeholder="Objet">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <input type="date" class="form-control search-slt date">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <select class="form-control search-slt fournisseur"
                                id="exampleFormControlSelect1">
                                <option value="">Choissisez un fournisseur</option>
                                @foreach ($fournisseurs as $f)
                                    <option value="{{$f->id}}">{{$f->nom}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
</section>


<section class=" mt-3" id="app">

    <div class="container">



        <div class="row mb-2">
            <div class="col-md-4 offset-md-8 formButton" style="text-align: end;">


                    <select name="sous_magasin" class="form-control sous_magasin"   id="sous_magasin_id">
                        <option value="">Sous Magasin</option>
                        @foreach (Auth::user()->sousmagasins as $m)
                             <option value="{{ $m->id }}">{{ $m->nom }}</option>
                        @endforeach
                    </select>


            </div>
        </div>


        <table class="table datatable" id="marches-table" style="width: 100%;">
            <thead>
                <tr>
                    <th></th>
                    <th>N° Marche</th>
                    <th>L.B</th>
                    <th>Objet</th>
                    <th>Fournisseur</th>
                    <th>D.E</th>
                    <th>ODS</th>
                    <th>Type de marché</th>
                    <th>Actions</th>
                </tr>
            </thead>

        </table>
    </div>

</section>


@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('.fournisseur').select2();

            table = $('#marches-table').DataTable({
                dom: "tiplr",
                lengthMenu: [
                    [25, 100, -1],
                    [25, 100, "TOUS"]
                ],
                "language": {
                "url": "{{ asset('assets/datatable-lang/fr.json') }}"
            },
                pageLength: 25,
                serverSide: true,
                processing: true,
                ajax: {
                    url: '{{ route('marches.data') }}',
                    data: function(d) {
                        d.no_marche = $('.no_marche').val()
                        d.objet = $('.objet').val()
                        d.date = $('.date').val()
                        d.fournisseur_id = $('.fournisseur').val()
                        d.magasin_id = $('.magasin').val()
                        d.sous_magasin_id = $('.sous_magasin').val()
                    }
                },
                columns: [
                    {
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: '',
                        width: '2%',
                    },
                    {
                        data: 'no_marche',
                        name: 'no_marche',
                        width: '10%',

                    },
                    {
                        data: 'ligne_budgetaire',
                        name: 'ligne_budgetaire'
                    },
                    {
                        data: 'objet',
                        name: 'objet'
                    },
                    {
                        data: 'fournisseur',
                        name: 'fournisseur'
                    },
                    {
                        data: 'delais_execution',
                        name: 'delais_execution',

                    },
                    {
                        data: 'ods',
                        name: 'ods',
                        width: '10%',
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        searchable: false,
                        sortable: false,
                        width: '5%',

                    },


                ]
            });

            $('#marches-table tbody').on('click', 'td.dt-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(row.data().marche_details).show();
                    tr.addClass('shown');
                }
            });

            $(".no_marche").keyup(function() {
                table.draw();
            });
            $(".objet").keyup(function() {
                table.draw();
            });
            $(".date").on('change',function() {
                table.draw();
            });
            $(".fournisseur").on('change',function() {
                table.draw();
            });

            $(".sous_magasin").on('change',function() {
                table.draw();
            });

        });
    </script>
@endpush
