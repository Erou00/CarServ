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
                            <a href="{{ route('groupes.create') }}" class="btn btn-default">
                                <i class="fa fa-plus"></i>
                                Ajouter</a>
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
        <div>


    <div class="box box-primary">
        <fieldset class="form-group p-3 mt-2">
            <legend class="p-2">Les Groupes</legend>


        <div class="row">

            <div class="col-md-12">

                <div class="tile shadow">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="table-responsive">

                                <table class="table datatable" id="groupes-table" style="width: 100%;">
                                    <thead>
                                        <tr>

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

        </fieldset>
    </div>


    </section>
@endsection

@push('scripts')
    <script>

$(document).ready( function () {
    let groupesTable = $('#groupes-table').DataTable({
            dom: "tiplr",
            serverSide: true,
            processing: true,

            ajax: {
                url: '{{ route('groupes.data') }}',
            },
            columns: [{
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
                [2, 'desc']
            ],
            drawCallback: function(settings) {
                $('.record__select').prop('checked', false);
                $('#record__select-all').prop('checked', false);
                $('#record-ids').val();
                $('#bulk-delete').attr('disabled', true);
            }
        });

        $('#data-table-search').keyup(function() {
            groupesTable.search(this.value).draw();
        })
} );

    </script>
@endpush
