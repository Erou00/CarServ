@extends('layouts.dashboard.app')



@section('content')

<section class="content">
  <div>
        <h2>Historiques</h2>
    </div>

    <ul class="breadcrumb mt-2 p-2">
        <li class="breadcrumb-item"><a href="">Acceuil</a></li>
        <li class="breadcrumb-item"><a href="">Paramétrage</a></li>
        <li class="breadcrumb-item"><a  href="">Produits</a> </li>
        <li class="breadcrumb-item">Historiques</li>
    </ul>


    <div class="box box-primary">

        <div class="box-header">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <h4><strong>Chercher par users :</strong></h4>
                            <select id="user" class="form-control select--produit" required>
                                <option value="" style="width: 100px"></option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}" >{{ $u->id.'/ '.$u->nom.' '.$u->prenom}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <h4><strong>Chercher par produits :</strong></h4>
                            <select id="produit" class="form-control select--produit" required>
                                <option value="" style="width: 100px"></option>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}" >{{ $p->id.'/ '.$p->designation }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

            </div>
        </div><!-- end of box header -->
        <div class="box-body">



            <div class="row">

                <div class="col-md-12">

                    <div class="table-responsive">

                        <table class="table datatable" id="historiques-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Modifer par</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                        </table>

                    </div><!-- end of table responsive -->

                </div><!-- end of col -->

            </div><!-- end of row -->

        </div><!-- end of box body -->

    </div><!-- end of box -->
</section>
@endsection

@push('scripts')
    <script>

$(document).ready( function () {
    let produit = "{{ request()->produit_id }}";
    let user = "{{ request()->user_id }}";
    let hitoriquesTable = $('#historiques-table').DataTable({
            dom: "tiplr",
            serverSide: true,
            processing: true,
            ajax: {
                url: '{{ route('produits.historiquesData') }}',
                data: function (d) {
                    d.produit_id = produit;
                    d.user_id = user;

                }
            },
            columns: [
                {
                    data: 'produit',
                    name: 'produit'
                },
                {
                    data: 'user',
                    name: 'user',
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                },
            ],
            order: [
                [2, 'desc']
            ],
            // drawCallback: function(settings) {
            //     $('.record__select').prop('checked', false);
            //     $('#record__select-all').prop('checked', false);
            //     $('#record-ids').val();
            //     $('#bulk-delete').attr('disabled', true);
            // }
        });

        $('#produit').on('change', function () {
            produit = this.value;
            hitoriquesTable.ajax.reload();
        });

        $('#user').on('change', function () {
            user = this.value;
            hitoriquesTable.ajax.reload();
        });

        $('#data-table-search').keyup(function() {
            hitoriquesTable.search(this.value).draw();
        })
} );


        $('.select--produit').select2();

    </script>
@endpush


