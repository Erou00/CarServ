@extends('layouts.dashboard.app')



@section('content')
    <section class="content">
        <div>
            <h2>Volumes</h2>
        </div>

        <ul class="breadcrumb mt-2">
            <li class="breadcrumb-item"><a href="">Acceuil</a></li>
            <li class="breadcrumb-item"><a href="">Paramétrage</a></li>
            <li class="breadcrumb-item">Volumes</li>
        </ul>

        <div class="box box-primary">

            <div class="box-header">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-4">
                            <form method="post" action="{{ route('volumes.bulk_delete') }}" style="display: inline-block;">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="record_ids" id="record-ids">
                                <button type="submit" class="btn btn-danger" id="bulk-delete" disabled="true"><i
                                        class="fa fa-trash"></i> Supprimer tout</button>
                            </form><!-- end of form -->


                        </div>

                        <div class="col-md-4">
                            <input type="text" id="data-table-search" class="form-control" autofocus
                                placeholder="chercher">
                        </div>

                        <div class="col-md-4">


                            <a href="{{ route('volumes.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i>
                                Ajouter</a>

                        </div>

                    </div>

                </div>
            </div>


            <div class="row">

                <div class="col-md-12">

                    <div class="table-responsive">

                        <table class="table datatable" id="volumes-table" style="width: 100%;">
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
                                    <th>Code</th>
                                    <th>Créé à</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>

                    </div><!-- end of table responsive -->

                </div><!-- end of col -->

            </div><!-- end of row -->

        </div>

    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let volumesTable = $('#volumes-table').DataTable({
                dom: "tiplr",
                serverSide: true,
                processing: true,
                "language": {
                    "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
                },
                ajax: {
                    url: '{{ route('volumes.data') }}',
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
                        data: 'code',
                        name: 'code'
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
                volumesTable.search(this.value).draw();
            })
        });
    </script>
@endpush
