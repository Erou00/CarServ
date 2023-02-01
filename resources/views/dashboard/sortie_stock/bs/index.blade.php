@extends('layouts.dashboard.app')
@section('styles')
 <style>
    .red-bg{
        background-color: #ff000057 !important;
    }
 </style>
@endsection

@section('content')
<section class="search-sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <form action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                            <input type="text" class="form-control search-slt no_bl"
                                placeholder="no bl">
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <select class="form-control search-slt select commande"
                                id="exampleFormControlSelect1">
                                <option value="">Choissisez une demande</option>
                                @foreach ($commandes as $c)
                                    <option value="{{$c->id}}">{{$c->no_commande.'/'.$c->annee}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                            <input type="date" class="form-control search-slt date">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <select class="form-control search-slt entite"
                                id="exampleFormControlSelect1">
                                <option value="">Choissisez un Entite</option>
                                @foreach ($entites as $e)
                                    <option value="{{$e->id}}">{{$e->nom}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                            <select class="form-control sortie">
                                <option value="">TOUS LES B.L</option>
                                <option value="preparation" selected>Preparées</option>
                                <option value="validation">Classées</option>
                                <option value="annulation">Annulées</option>


                            </select>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
</section>


<section class=" mt-3">

    <div class="container">

        <div class="row mb-2">
            @if (auth()->user()->hasPermission('update_bs'))
              <div class="col-md-4">
                <form method="post" action="{{route('bs.bulk_validation')}}" style="display: inline-block;">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="record_ids" id="record-ids">
                    <button type="submit" class="btn btn-default" id="bulk-delete" disabled="true"><i
                            class="fa fa-add"></i> Classée Les B.L Selectionées</button>
                </form><!-- end of form -->
            </div>
            @endif


            <div class="col-md-4 offset-md-4 formButton" style="text-align: end;">


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
                    <th>

                    </th>
                    <th></th>
                    <th>N° BL</th>
                    <th>Magasin</th>
                    <th>Date</th>
                    <th>Commande</th>
                    <th>entite</th>
                    <th>Etat</th>
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
                    url: '{{ route('bs.index') }}',
                    data: function(d) {
                        d.commande_id = $('.commande').val()
                        d.no_bl = $('.no_bl').val()
                        d.date = $('.date').val()
                        d.entite_id = $('.entite').val()
                        d.sortie = $('.sortie').val()
                        d.magasin_id = $('.magasin').val()
                        d.sous_magasin_id = $('.sous_magasin').val()
                    }
                },
                columns: [
                    {
                        data: 'record_select',
                        name: 'record_select',
                        searchable: false,
                        sortable: false,
                        width: '1%'
                    },
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
                        width: '10%',

                    },
                    {
                        data: 'magasin',
                        name: 'magasin',

                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'commande',
                        name: 'commande'
                    },
                    {
                        data: 'entite',
                        name: 'entite',
                        width:'35%'
                    },
                    {
                        data: 'validation',
                        name: 'validation',
                        width:'20%',
                        searchable: false,
                        sortable: false,
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
            $(".commande").on('change',function() {
                table.draw();
            });
            $(".date").on('change',function() {
                table.draw();
            });
            $(".entite").on('change',function() {
                table.draw();
            });

            $(".sortie").on('change',function() {
                table.draw();
                console.log($('.sortie').val());
            });
            $(".sous_magasin").on('change',function() {
                table.draw();
            });




        });



    </script>
{{-- {{  route('index').'/'.Session::get('download.in.the.next.request') }} --}}

@endpush
