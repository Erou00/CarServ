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
                            <input type="text" id="data-table-search" class="form-control" autofocus placeholder="chercher">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <a href="{{ route('types.create') }}" class="btn btn-default">
                                <i class="fa fa-plus"></i>
                                Ajouter
                            </a>
                                <a href="{{route('types.print')}}" class="btn btn-default">
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

<section class="content" >

    <div class="container ">

        <div class="row my-2">



                                <table class="table datatable" id="types-table" style="width: 100%;">
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
                                            <th>Type</th>
                                            <th>Créé à</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>



        </div><!-- end of row -->
    </div>


    </section>
@endsection

@push('scripts')
    <script>

$(document).ready( function () {
    let genresTable = $('#types-table').DataTable({
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
                url: '{{ route('types.data') }}',
            },
            columns: [{
                    data: 'record_select',
                    name: 'record_select',
                    searchable: false,
                    sortable: false,
                    width: '1%'
                },
                {
                    data: 'type',
                    name: 'type'
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
