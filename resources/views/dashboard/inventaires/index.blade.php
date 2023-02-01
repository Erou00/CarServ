@extends('layouts.dashboard.app')


@section('styles')

@endsection
@section('content')
<section class="search-sec">
    <div class="container">

                <form action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <input type="text" class="form-control search-slt no_inventaire ms-3"
                                placeholder="N° inventaire">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <select class="form-control etat" name="etat" >
                                <option value="" >Tous les inventaires</option>
                                <option value="preparation" selected>Preparation</option>
                                <option value="verification">Verification</option>
                                <option value="validation" >Validation</option>
                            </select>
                        </div>
                    </div>
                </form>


    </div>
</section>

<section class=" mt-3" >

    <div class="container">

        <table class="datatable cell-border" id="inventaires-table" style="width: 100%;">
            <thead>
                <tr>
                    <th></th>
                    <th>N° Inventaire</th>
                    <th>Etat</th>
                    {{-- <th>Fournisseur</th>
                    <th>TVA</th> --}}
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
            table = $('#inventaires-table').DataTable({
                dom: "tiplr",
                serverSide: true,
                processing: true,
                lengthMenu: [
                    [25, 100, -1],
                    [25, 100, "TOUS"]
                ],
                pageLength: 25,
                "language": {
                "url": "{{ asset('assets/datatable-lang/fr.json') }}"
            },
                ajax: {
                    url: '{{ route('inventaires.index') }}',
                    data: function(d) {
                        d.no_inventaire = $('.no_inventaire').val()
                        d.etat = $('.etat').val()
                        d.magasin_id = $('.magasin').val()

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
                        data: 'no_inventaire',
                        name: 'no_inventaire',
                        width: '16%',
                    },

                    {
                        data: 'etat',
                        name: 'etat',
                        width: '10%',
                    },
                    // {
                    //     data: 'fournisseur',
                    //     name: 'fournisseur'
                    // },

                    // {
                    //     data: 'tva',
                    //     name: 'tva',
                    //     width: '5%',
                    // },
                    {
                        data: 'actions',
                        name: 'actions',
                        searchable: false,
                        sortable: false,
                        width: '5%',


                    },


                ]
            });

            $('#inventaires-table tbody').on('click', 'td.dt-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(row.data().inventaire_details).show();
                    tr.addClass('shown');
                }
            });

            $(".no_inventaire").keyup(function() {
                table.draw();
            });
            $(".etat").on('change',function() {
                table.draw();
            });

            $(".magasin").on('change',function() {
                table.draw();
            });

        });
    </script>
@endpush
