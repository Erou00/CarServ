@extends('layouts.dashboard.app')



@section('content')
<section class=" mt-3" >

    <div class="container">

        <div class="row">


                        <div class="col-4">
                            <input type="text" id="data-table-search" class="form-control" autofocus placeholder="chercher">
                        </div>

                        <div class="col-4">
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i>
                                Ajouter</a>


                        </div>

                        <div class="col-4">
                            <a href="{{route('categories.print')}}" class="btn btn-primary">
                                <i class="fa fa-print"></i>Imprimer
                            </a>



        </div>



        <div class="row">

            <div class="col-md-12">

                <div class="tile shadow">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="table-responsive">

                                <table class="table datatable" id="categories-table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="animated-checkbox">
                                                    <label class="m-0">
                                                        <input type="checkbox" id="record__select-all">
                                                        <span class="label-text"></span>
                                                    </label>
                                                </div>
                                            </th>
                                            <th>Nom</th>
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
    </div>


    </section>
@endsection

@push('scripts')
    <script>

$(document).ready( function () {
    let genresTable = $('#categories-table').DataTable({
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
            ajax: {
                url: '{{ route('categories.data') }}',
            },
            columns: [{
                    data: 'record_select',
                    name: 'record_select',
                    searchable: false,
                    sortable: false,
                    width: '1%'
                },
                {
                    data: 'nom',
                    name: 'nom'
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
                    width: '20%'
                },
            ],
            order: [
                [3, 'desc']
            ],
            drawCallback: function(settings) {
                $('.record__select').prop('checked', false);
                $('#record__select-all').prop('checked', false);
                $('#record-ids').val();
                $('#bulk-delete').attr('disabled', true);
            }
        });

        $('#data-table-search').keyup(function() {
            genresTable.search(this.value).draw();
        })
} );

    </script>
@endpush
