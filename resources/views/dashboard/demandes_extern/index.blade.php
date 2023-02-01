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
                            <input type="text" class="form-control search-slt no_commande ms-3"
                                placeholder="N° Commande">
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 p-0">
                            <input type="date" class="form-control search-slt date"
                            style="width: 146px !important">
                        </div>


                        <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                            <select class="form-control ms-4 search-slt annee"
                                id="exampleFormControlSelect1">
                                @foreach ($annees as $annee)
                                    <option value="{{$annee->annee}}"  {{ $annee->annee == \Carbon\Carbon::now()->format('Y') ? 'selected' : ''  }}><strong>Année :</strong> {{$annee->annee}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>


    </div>
</section>

<section class=" mt-3" >

    <div class="container">



        <table class="datatable cell-border" id="commandes-table" style="width: 100%;">
            <thead>
                <tr>
                    <th></th>
                    <th>N° Commande</th>
                    <th>Date</th>
                    <th>Entite</th>
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

            $('.entite').select2();
            table = $('#commandes-table').DataTable({
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
                    url: '{{ route('demandes-externe.index') }}',
                    data: function(d) {
                        d.no_commande = $('.no_commande').val()
                        d.date = $('.date').val()
                        d.annee = $('.annee').val()

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
                        data: 'no_commande',
                        name: 'no_commande',
                        width: '16%',
                    },

                    {
                        data: 'date_commande',
                        name: 'date_commande',
                        width: '10%',
                    },
                    {
                        data: 'entite',
                        name: 'entite'
                    },

                    {
                        data: 'actions',
                        name: 'actions',
                        searchable: false,
                        sortable: false,
                        width: '10%',


                    },


                ]
            });

            $('#commandes-table tbody').on('click', 'td.dt-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(row.data().commande_details).show();
                    tr.addClass('shown');
                }
            });

            $(".no_commande").keyup(function() {
                table.draw();
            });
            $(".date").on('change',function() {
                table.draw();
            });
            $(".entite").on('change',function() {
                table.draw();
            });
            $(".annee").on('change',function() {
                table.draw();
            });



        });
    </script>
@endpush
