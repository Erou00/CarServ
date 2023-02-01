@extends('layouts.dashboard.app')


@section('styles')

@endsection
@section('content')
<section class="search-sec">
    <div class="container">

                <form action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0 d-flex">
                            <input type="text" class="form-control search-slt no_entrer ms-3"
                                placeholder="N° BL" name="no_entrer">
                            <span class="py-0 my-0" style="font-size: 18px;"><strong>|</strong></span>
                            <select class="form-control search-slt annee"
                                id="exampleFormControlSelect1">
                                <option value=""></option>
                                @foreach ($annees as $annee)
                                    <option value="{{$annee->annee}}"  {{ $annee->annee == \Carbon\Carbon::now()->format('Y') ? 'selected' : ''  }}><strong>Année :</strong> {{$annee->annee}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-12 px-1">
                            <input type="text" class="form-control search-slt objet"
                             placeholder="Objet">
                        </div>
                        <div class="col-lg-3 col-md-2 col-sm-12 p-0">
                            <div class="d-flex">
                                <input type="date" class="form-control search-slt date">

                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 py-0">
                            <select class="form-control ms-4 search-slt fournisseur"
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
</section>

<section class=" mt-3" >

    <div class="container">


        @if (Auth::user()->hasRole('master'))
        <div class="row mb-2">
            <div class="col-md-4 offset-md-8 formButton" style="text-align: end;">

                <div class="d-flex">
                    <select name="" class="form-control me-2 magasin" id=""
                    data-dependent="sous_magasin_id">
                        <option value="">Magasin</option>
                        @foreach ($magasins as $m)
                             <option value="{{ $m->id }}">{{ $m->nom }}</option>
                        @endforeach
                    </select>

                    <select name="sous_magasin" class="form-control sous_magasin"
                       id="sous_magasin_id">
                        <option value="">Sous Magasin</option>
                    </select>
                </div>


            </div>
        </div>
        @endif

        <table class="datatable cell-border" id="entrers-table" style="width: 100%;">
            <thead>
                <tr>
                    <th></th>
                    <th>N° B.L</th>
                    <th>Date</th>
                    <th>Fournisseur</th>
                    <th>TVA%</th>
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
            table = $('#entrers-table').DataTable({
                dom: "tiplr",
                serverSide: true,
                processing: true,
                lengthMenu: [
                    [25, 100, -1],
                    [25, 100, "TOUS"]
                ],

                "language": {
                "url": "{{ asset('assets/datatable-lang/fr.json') }}"
            },
                pageLength: 25,
                ajax: {
                    url: '{{ route('autres.autre_magasins') }}',
                    data: function(d) {
                        d.no_entrer = $('.no_entrer').val()
                        d.objet = $('.objet').val()
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
                        data: 'date',
                        name: 'date',
                        width: '10%',
                    },
                    {
                        data: 'fournisseur',
                        name: 'fournisseur'
                    },

                    {
                        data: 'tva',
                        name: 'tva',
                        width: '5%',
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

            $('#entrers-table tbody').on('click', 'td.dt-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(row.data().entrer_details).show();
                    tr.addClass('shown');
                }
            });

            $(".no_entrer").keyup(function() {
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
            $(".annee").on('change',function() {
                table.draw();
            });
            $(".magasin").on('change',function() {
                if($(this).val() != '')
                {
                var select = $(this).attr("id");
                var value = $(this).val();
                var dependent = $(this).data('dependent');
                var _token = "{{ csrf_token() }}";

                $.ajax({
                    url:"{{route('sous_magasins.getSousMagasins')}}",
                    method:"POST",
                    data:{select:select, value:value, _token:_token, dependent:dependent},
                    success:function(result)
                    {
                    $('#'+dependent).html(result);
                    }
                })
                }
                table.draw();
            });

            $(".sous_magasin").on('change',function() {
                table.draw();
            });

        });
    </script>
@endpush