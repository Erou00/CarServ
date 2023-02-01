@extends('layouts.dashboard.app')



@section('content')
    <section class="content">
        <div>
            <h2>Sous Categories</h2>
        </div>

        <ul class="breadcrumb mt-2">
            <li class="breadcrumb-item"><a href="">Acceuil</a></li>
            <li class="breadcrumb-item"><a href="">Paramétrage</a></li>
            <li class="breadcrumb-item">Sous Categories</li>
        </ul>


        <div class="box box-primary">

            <div class="box-header">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-3">
                            <a href="{{route('sous_categories.print')}}" class="btn btn-primary">
                                <i class="fa fa-print"></i>Imprimer
                            </a>
                            {{-- <form method="post" action="{{route('sous_categories.bulk_delete')}}" style="display: inline-block;">
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


                            <a href="{{ route('sous_categories.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i>
                                Ajouter</a>

                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <select id="categorie" class="form-control select2" required>
                                    <option value="">Chercher par Categorie</option>
                                    @foreach ($categories as $c)
                                        <option value="{{ $c->id }}" {{ $c->id == request()->categorie_id ? 'selected' : '' }}>{{ $c->nom }}</option>
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

                                    <table class="table datatable" id="sous-categories-table" style="width: 100%;">
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
                                                <th>Categorie</th>
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
    let categorie = "{{ request()->categorie_id }}";
    let sousCategoriesTable = $('#sous-categories-table').DataTable({
            dom: "tiplr",
            serverSide: true,
            processing: true,
            "language": {
                "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
            },
            ajax: {
                url: '{{ route('sous_categories.allSousCategories') }}',
                data: function (d) {
                    d.categorie_id = categorie;

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
                    data: 'categorie',
                    name: 'categorie'
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
            sousCategoriesTable.search(this.value).draw();
        })

        $('#categorie').on('change', function () {
            categorie = this.value;
            sousCategoriesTable.ajax.reload();
        });
} );

    </script>
@endpush
