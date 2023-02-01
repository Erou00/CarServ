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
                        <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                            <input type="text" class="form-control search-slt no_bl"
                                placeholder="no bl">
                        </div>


                        <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                            <input type="date" class="form-control search-slt date">
                        </div>

                    </div>
                </form>

            </div>
        </div>

    </div>
</section>


<section class=" mt-3">

    <div class="container">
        @if (Auth::user()->hasRole('master'))
        <div class="row mb-2">
            <div class="col-md-4 offset-md-8 formButton" style="text-align: end;">

                <div class="d-flex">
                    <select name="" class="form-control me-2 magasin" id="" data-dependent="sous_magasin_id">
                        <option value="">Magasin</option>
                        @foreach ($magasins as $m)
                             <option value="{{ $m->id }}">{{ $m->nom }}</option>
                        @endforeach
                    </select>

                    <select name="sous_magasin" class="form-control sous_magasin"   id="sous_magasin_id">
                        <option value="">Sous Magasin</option>
                    </select>
                </div>


            </div>
        </div>
        @endif

        <table class="table datatable" id="bls-table" style="width: 100%;">
            <thead>
                <tr>
                    <th></th>
                    <th>N° BL</th>
                    <th>Date</th>
                    <th>Designation</th>
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
                    url: '{{ route('bonSortie.autre_magasins') }}',
                    data: function(d) {
                        d.no_bl = $('.no_bl').val()
                        d.date = $('.date').val()
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
                        data: 'no_sortie',
                        name: 'no_sortie',
                        width: '16%',

                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'designation',
                        name: 'designation'
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



