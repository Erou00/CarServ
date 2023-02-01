@extends('layouts.dashboard.app')



@section('content')
    <section class="content">
        <div>
            <h2>Sous Magasins</h2>
        </div>

        <ul class="breadcrumb mt-2">
            <li class="breadcrumb-item"><a href="">Acceuil</a></li>
            <li class="breadcrumb-item"><a href="">Paramétrage</a></li>
            <li class="breadcrumb-item">Sous Magasins</li>
        </ul>


        <div class="box box-primary">

            <div class="box-header">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-3">
                            <a href="{{route('sous_magasins.print')}}" class="btn btn-primary">
                                <i class="fa fa-print"></i>Imprimer
                            </a>
                            {{-- <form method="post" action="{{route('sous_magasins.bulk_delete')}}" style="display: inline-block;">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="record_ids" id="record-ids">
                                <button type="submit" class="btn btn-danger" id="bulk-delete" disabled="true"><i
                                        class="fa fa-trash"></i> Supprimer tout</button>
                            </form><!-- end of form --> --}}


                        </div>

                        <div class="col-md-3">
                            <input type="text" id="data-table-search" class="form-control" autofocus placeholder="chercher">
                        </div>

                        <div class="col-md-3">


                            <a href="{{ route('sous_magasins.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i>
                                Ajouter</a>

                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <select id="magasin" class="form-control select2" required>
                                    <option value="">Chercher par magasin</option>
                                    @foreach ($magasins as $c)
                                        <option value="{{ $c->id }}" {{ $c->id == request()->magasin_id ? 'selected' : '' }}>{{ $c->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                </div>
            </div>


            <div class="row">

                <div class="col-md-12">

                    <div class="tile shadow">

                        <div class="row">

                            <div class="col-md-12">

                                <div class="table-responsive">

                                    <table class="table datatable" id="sous-magasins-table" style="width: 100%;">
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
                                                <th>magasin</th>
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
    let magasin = "{{ request()->magasin_id }}";
    let sousmagasinsTable = $('#sous-magasins-table').DataTable({
            dom: "tiplr",
            serverSide: true,
            processing: true,
            "language": {
                "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
            },
            ajax: {
                url: '{{ route('sous_magasins.allSousMagasins') }}',
                data: function (d) {
                    d.magasin_id = magasin;

                }
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
                    data: 'magasin_mere',
                    name: 'magasin_mere'
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
            sousmagasinsTable.search(this.value).draw();
        })

        $('#magasin').on('change', function () {
            magasin = this.value;
            sousmagasinsTable.ajax.reload();
        });
} );

    </script>
@endpush
