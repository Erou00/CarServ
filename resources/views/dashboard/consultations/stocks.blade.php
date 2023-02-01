@extends('layouts.dashboard.app')
@section('meta')

@endsection

@section('styles')
 <style>
    .red-bg{
        background-color: #ff000057 !important;
    }
 </style>
@endsection
@section('content')
<section class="search-sec">
    <div class="container">

                <form action="{{route('consultations.stocks.rapport')}}" method="get" id="form_stock_rapport">
                    @csrf
                    @method('GET')
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <select class="form-control ms-4 search-slt produit_id select"
                            id="exampleFormControlSelect1" data-select2-id="produit_id"
                            name="produit_id">
                            <option value="">Designation</option>
                            @foreach ($produits as $p)
                                <option value="{{$p->id}}">{{$p->designation}}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <select class="form-select form-control categorie_id" name="categorie_id" id="categorie"
                            data-dependent="sous_categorie_id" aria-label="Default select example" required >
                            <option option value="" >Categorie</option>
                            @foreach ($categories as $c)
                            <option value="{{$c->id}}">{{$c->nom}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
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
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                            <select class="form-control ms-4 search-slt marque_id select"
                            data-dependent="marque_id" id="marque_id"
                            name="marque_id" aria-label="Default select example">
                            <option value="">Marque</option>
                            @foreach ($marques as $m)
                                <option value="{{$m->id}}">{{$m->nom}}</option>
                            @endforeach
                        </select>
                        </div>

                        @if (Auth::user()->hasRole('master'))
                        <div class="col-lg-3 col-md-3 col-sm-12 p-0 mt-1">
                            <select class="form-control ms-4 search-slt magasin_id select"
                            id="magasin_id"
                            name="magasin_id" aria-label="Default select example">
                            <option value=""></option>
                            @foreach ($magasins as $magasin)
                                <option value="{{$magasin->id}}"
                                     {{  $magasin->id == Auth::user()->magasin_id ? 'selected' : ''  }}>
                                    {{$magasin->nom}}
                                </option>
                            @endforeach
                        </select>
                        </div>
                        @endif



                    </div>
                </form>


    </div>
</section>

<section class=" my-3" >

    <div class="container">
        <table class="datatable cell-border" id="produits-stock-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>Categorie</th>
                    <th>S Categorie</th>
                    <th>Marque</th>
                    <th>Designation</th>
                    <th>U.R</th>
                    <th>MIN</th>
                    <th>Stock</th>
                    <th>Qte Accordée</th>

                </tr>
            </thead>

        </table>

    </div>




</section>


@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('.produit_id').select2();
            $('.categorie_id').select2();
            $('.sous_categorie_id').select2();
            $('.marque_id').select2();
            table = $('#produits-stock-table').DataTable({
                dom: 'Brt<"bottom"fl>p',
                // dom: "tiplr",
                buttons: [
                    {
                        text: 'Pdf',
                        action: function ( e, dt, node, config ) {
                            $("#form_stock_rapport").submit();
                        }
                    },
                    {
                        extend: 'excel',
                        title: 'Situation de stock '+' ' +new Date().toLocaleString(),
                        exportOptions: {
                            modifier : {
                                    order : "index",
                                    page : "all",
                                    search : "applied"
                                }
                        }
                    }


                ],
                lengthMenu: [
                    [25, 100, -1],
                    [25, 100, "TOUS"]
                ],
                pageLength: 25,
                "searching": false,
                serverSide: true,
                processing: true,
                ajax: {
                    url: '{{ route('consultations.stocks') }}',
                    data: function(d) {
                        d.categorie_id = $('.categorie_id').val()
                        d.sous_categorie_id = $('.sous_categorie_id').val()
                        d.marque_id = $('.marque_id').val()
                        d.produit_id = $('.produit_id').val()
                        d.magasin_id = $('.magasin_id').val()
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
                        width: '16%',
                    },
                    {
                        data: 'marque',
                        name: 'marque'
                    },

                    {
                        data: 'designation',
                        name: 'designation',
                        width: '32%',
                    },

                    {
                        data: 'ur',
                        name: 'ur',
                        width: '2%',
                    },
                    {
                        data: 'stock_min',
                        name: 'stock_min',
                    },
                    {
                        data: 'stock',
                        name: 'stock',
                    },
                    {
                        data: 'product_stock',
                        name: 'product_stock',
                    },

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

            $(".magasin_id").on('change',function() {
                table.draw();
            });

        });
    </script>
@endpush
