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
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0 d-flex">
                            <input type="text" class="form-control search-slt no_bl"
                                placeholder="no bl">
                                <span class="py-0 my-0" style="font-size: 18px;"><strong>|</strong></span>
                                <select class="form-control search-slt annee"
                                    id="exampleFormControlSelect1">
                                    <option value=""></option>
                                    @foreach ($annees as $annee)
                                        <option value="{{$annee->annee}}"  {{ $annee->annee == \Carbon\Carbon::now()->format('Y') ? 'selected' : ''  }}><strong>Année :</strong> {{$annee->annee}}</option>
                                    @endforeach
                                </select>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 px-1">
                            <select class="form-control search-slt marche"
                                id="exampleFormControlSelect1">
                                <option value="">Choissisez un marche</option>
                                @foreach ($marches as $c)
                                    <option value="{{$c->id}}">{{$c->no_marche}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <div class="d-flex">
                                <input type="date" class="form-control search-slt date">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 py-0">
                            <select class="form-control fournisseur search-slt"
                                id="fournisseur">
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

        <table class="table datatable" id="bls-table" style="width: 100%;">
            <thead>
                <tr>
                    <th></th>
                    <th>N° BL</th>
                    <th>Magasin</th>
                    <th>Date</th>
                    <th>marche</th>
                    <th>Fournisseur</th>
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

            $('#fournisseur').select2();
            $('.marche').select2();

            table = $('#bls-table').DataTable({
                dom: "tiplr",
                lengthMenu: [
                    [25, 100, -1],
                    [25, 100, "TOUS"]
                ],
                pageLength: 25,
                serverSide: true,
                processing: true,
                "language": {
                "url": "{{ asset('assets/datatable-lang/fr.json') }}"
            },
                ajax: {
                    url: '{{ route('bls.getBlofMarche') }}',
                    data: function(d) {
                        d.marche_id = $('.marche').val()
                        d.no_bl = $('.no_bl').val()
                        d.date = $('.date').val()
                        d.fournisseur_id = $('.fournisseur').val()
                        d.annee = $('.annee').val()
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
                        data: 'no_bl',
                        name: 'no_bl',
                        width: '16%',

                    },
                    {
                        data: 'magasin',
                        name: 'magasin',
                        // width: '16%',

                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'marche',
                        name: 'marche'
                    },
                    {
                        data: 'fournisseur',
                        name: 'fournisseur'
                    },

                    {
                        data: 'actions',
                        name: 'actions',
                        searchable: false,
                        sortable: false,

                    },


                ]
            });

            $('#bls-table tbody').on('click', 'td.dt-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(row.data().bl_details).show();
                    tr.addClass('shown');
                }
            });

            $(".no_bl").keyup(function() {
                table.draw();
            });
            $(".marche").on('change',function() {
                table.draw();
            });
            $(".date").on('change',function() {
                table.draw();
            });
            $(".fournisseur").on('change',function() {
                table.draw();
            });
            $(".annee").on('change',function() {
                table.draw();
            });

            $(".sous_magasin").on('change',function() {
                table.draw();
            });

        });
    </script>
@endpush
