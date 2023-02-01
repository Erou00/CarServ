@extends('layouts.dashboard.app')

@section('styles')

@endsection
@section('content')
    <section class="content">

              <fieldset class="form-group p-3 mt-2">
      <legend class="p-2">Entrer Stock Global</legend>

      <form action="{{route('consultations.entrerStocksGlobalFilter')}}" method="get" >
        @csrf
        <div class="container-fluid mb-3">
            <div class="row">
              <div class="col-md-3">
                <select class="form-select form-control categorie_id" name="categorie_id" id="categorie"
                data-dependent="sous_categorie_id" aria-label="Default select example" required >
                <option option value="" >Categorie</option>
                @foreach ($categories as $c)
                <option value="{{$c->id}}">{{$c->nom}}</option>
                @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <select name="sous_categorie_id" id="sous_categorie_id"
                            class="form-control sous_categorie_id"
                            data-dependent="marque_id" required
                            aria-label="Default select example" style="appearance: auto;">
                            <option option value="" >Sous Categorie</option>
                            @foreach ($sousCategories as $sc)
                            <option value="{{$sc->id}}">{{$sc->nom}}</option>
                            @endforeach
                            </select>
              </div>
              <div class="col-md-3">
                <select class="form-control ms-4 search-slt marque_id select"
                data-dependent="marque_id" id="marque_id"
                name="marque_id" aria-label="Default select example">
                <option value="">Marque</option>
                @foreach ($marques as $m)
                    <option value="{{$m->id}}">{{$m->nom}}</option>
                @endforeach
            </select>
              </div>

              <div class="col-md-3">
                <select class="form-control produit_id select" name="produit_id" id="">
                    <option value="">Designation</option>
                    @foreach ($produits as $p)
                    <option value="{{$p->id}}">{{ $p->designation}}</option>
                    @endforeach
                </select>
              </div>

              @if (Auth::user()->hasRole('master'))
              <div class="col-md-3 mt-4">
                <select class="form-control magasin_id select" name="magasin_id" id=""
                    data-dependent="sous_magasin_id">
                    <option value="">Magasin</option>
                    @foreach ($magasins as $magasin)
                    <option value="{{$magasin->id}}">{{ $magasin->nom }}</option>
                    @endforeach
                </select>

              </div>

              <div class="col-md-3 mt-4">
                <select name="sous_magasin_id" class="form-control sous_magasin"
                id="sous_magasin_id">
                    <option value="">Sous Magasin</option>
                </select>

              </div>

              @endif

              <div class="col-md-4">
                <label for="" class="mt-2"><strong>Entre Date :</strong></label>
                    <div class="d-flex">
                        <input type="date" class="form-control startDate" id="" placeholder="" name="startDate">
                        <input type="date" class="form-control endDate mx-1" id="" placeholder="" name="endDate">
                </div>
              </div>

              <div class="col-md-3 mt-4">
                <select class="form-control groupe_id select" name="groupe_id" id="">
                    <option value="">Groupe</option>
                    @foreach ($groupes as $g)
                    <option value="{{$g->id}}">{{ $g->nom }}</option>
                    @endforeach
                </select>

              </div>
              <div class="col-md-2">
                <div class="form-check  mt-4">
                    <input class="form-check-input" type="checkbox"  name="annee" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Par Annee  </label>
                </div>
                {{-- <div class="mt-3 ms-2">
                    <button type="submit" value="imprimer" class="btn btn-secondary">Imprimer</button>
                </div> --}}

            </div>
          </div>

    </fieldset>
    <fieldset class="form-group p-2 mt-2">
      <legend class="p-2">Stock</legend >
      </form>
        <table class="datatable cell-border" id="stock-table" style="width: 100%;">
            <thead>
                <tr>
                    <th scope="col">Categorie</th>
                    <th scope="col">Sous Categorie</th>
                    <th scope="col">Marque/Famille</th>
                    <th scope="col">Code</th>
                    <th scope="col">Designation</th>
                    <th scope="col">Unité</th>
                    <th scope="col">Qte livree</th>
                </tr>
            </thead>

        </table>



    </fieldset>

    </section>
@endsection

@push('scripts')
<script src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.11/vue.min.js"></script>

    <script>
        $(document).ready(function() {

            $('.produit_id').select2();
            $('.categorie_id').select2();
            $('.sous_categorie_id').select2();
            $('.marque_id').select2();
            table = $('#stock-table').DataTable({
                dom: "lBfrtip",
                buttons: [
                    {
                        text: 'Pdf',
                        action: function ( e, dt, node, config ) {
                            $("form").submit();
                        }
                    },
                    {
                        extend: 'excel',
                        title: 'Entrer Stock Global '+' ' +new Date().toLocaleString(),
                        exportOptions: {
                            modifier : {
                                    order : "index",
                                    page : "all",
                                    search : "applied"
                                }
                        }
                    }


                ],
                "language": {
                "url": "{{ asset('assets/datatable-lang/fr.json') }}"
            },
                "searching": false,
                "bPaginate": false,
                serverSide: true,
                processing: true,
                ajax: {
                    url: '{{ route('consultations.entrerStocksGlobalFilter') }}',
                    data: function(d) {
                        d.categorie_id = $('.categorie_id').val()
                        d.sous_categorie_id = $('.sous_categorie_id').val()
                        d.marque_id = $('.marque_id').val()
                        d.produit_id = $('.produit_id').val()
                        d.groupe_id = $('.groupe_id').val()
                        d.startDate = $('.startDate').val()
                        d.endDate = $('.endDate').val()

                        d.magasin_id = $('.magasin_id').val()
                        d.sous_magasin_id = $('.sous_magasin').val()
                    }
                },
                columns: [

                    {
                        data: 'categorie',
                        name: 'categorie',
                        width: '16%',
                    },

                    {
                        data: 'sousCategorie',
                        name: 'sousCategorie',

                    },
                    {
                        data: 'marque',
                        name: 'marque'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },

                    {
                        data: 'designation',
                        name: 'designation',
                        width: '20%',
                    },

                    {
                        data: 'ur',
                        name: 'ur',
                        width: '2%',
                    },

                    {
                        data: 'qte_livree',
                        name: 'qte_livree',
                    }


                ]
            });


                $(".produit_id").on('change',function() {
                    table.draw();
                });
                $('.categorie_id').change(function(){
                if($(this).val() != '')
                {
                var select = $(this).attr("id");
                var value = $(this).val();
                var dependent = $(this).data('dependent');
                var _token = "{{ csrf_token() }}";

                $.ajax({
                    url:"{{route('sous_categories.getSousCategorie')}}",
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
            $(".sous_categorie_id").on('change',function() {
                if($(this).val() != '')
                {
                var select = $(this).attr("id");
                var value = $(this).val();
                var dependent = $(this).data('dependent');
                var _token = "{{ csrf_token() }}";

                console.log(select);
                console.log(value);
                console.log(dependent);
                $.ajax({
                    url:"{{route('marques.getMarque')}}",
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

                $(".marque_id").on('change',function() {
                    table.draw();
                });
                $(".groupe_id").on('change',function() {
                    table.draw();
                });
                $(".startDate").on('change',function() {
                    table.draw();
                });
                $(".endDate").on('change',function() {
                    table.draw();
                });

                $(".magasin_id").on('change',function() {
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


