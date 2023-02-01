@extends('layouts.dashboard.app')

@section('content')

<section class="content">


    <div class="box box-primary">

        <div class="box-header">
            <div class="row mb-2">

                <div class="col-md-12">


                        <a href="{{ route('utilisateurs.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Ajouter</a>



                        <form method="post" action="{{ route('utilisateurs.bulk_delete') }}"
                         style="display: inline-block; margin-bottom: 20px">
                            @csrf
                            @method('delete')
                            <input type="hidden" name="record_ids" id="record-ids">
                            <button type="submit" class="btn btn-danger" id="bulk-delete" disabled="true"><i class="fa fa-trash"></i> Supprimer tous</button>
                        </form><!-- end of form -->


                </div>

            </div><!-- end of row -->

            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" id="data-table-search" class="form-control"
                        autofocus placeholder="chercher">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <div class="form-group">
                            <select id="role" class="form-control select2">
                                <option value=""></option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                {{-- <div class="col-md-4">
                    <div class="form-group">
                        <div class="form-group">
                            <select id="service" class="form-control select2">
                                <option value=""></option>
                                @foreach ($services as $s)
                                    <option value="{{ $s->id }}">{{ $s->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div> --}}

            </div><!-- end of row -->
        </div><!-- end of box header -->
        <div class="box-body">
            <div class="row">

                <div class="col-md-12">

                    <div class="tile shadow">



                        <div class="row">

                            <div class="col-md-12">

                                <div class="table-responsive">

                                    <table class="table datatable" id="utilisateurs-table" style="width: 100%;">
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
                                            <th>Utilisateur</th>
                                            <th>Nom</th>
                                            <th>Prenom</th>
                                            <th>Email</th>
                                            <th>Roles</th>
                                            <th>Créé a</th>
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

        </div><!-- end of box body -->

    </div><!-- end of box -->




</section>

@endsection

@push('scripts')

    <script>

        let role;
        let service;





        let utilisateursTable = $('#utilisateurs-table').DataTable({
            dom: "tiplr",
            serverSide: true,
            processing: true,
            "language": {
                "url": "{{ asset('assets/datatable-lang/fr.json') }}"
            },
            ajax: {
                url: '{{ route('utilisateurs.data') }}',
                data: function (d) {
                    d.role_id = role;
                    d.service_id = service;
                }
            },
            columns: [
                {data: 'record_select', name: 'record_select', searchable: false, sortable: false, width: '1%'},
                {data: 'utilisateur', name: 'utilisateur'},
                {data: 'nom', name: 'nom'},
                {data: 'prenom', name: 'prenom'},
                {data: 'email', name: 'email'},
                {data: 'roles', name: 'roles'},
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'actions', name: 'actions', searchable: false, sortable: false, width: '20%'},
            ],
            order: [[4, 'desc']],
            drawCallback: function (settings) {
                $('.record__select').prop('checked', false);
                $('#record__select-all').prop('checked', false);
                $('#record-ids').val();
                $('#bulk-delete').attr('disabled', true);
            }
        });

        $('#data-table-search').keyup(function () {
            utilisateursTable.search(this.value).draw();
        })

        $('#role').on('change', function () {
            role = this.value;
            utilisateursTable.ajax.reload();
        })

        $('#service').on('change', function () {
            service = this.value;
            utilisateursTable.ajax.reload();
        })
    </script>

@endpush
