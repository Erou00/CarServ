<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('meta')
    <title>Gestion DE STOCK</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

      {{-- noty --}}
      <link rel="stylesheet" href="{{ asset('assets/plugins/noty/noty.css') }}">
      <script src="{{ asset('assets/plugins/noty/noty.min.js') }}"></script>


      @yield('styles')

</head>

<body>

    <header>
        <div class="main-header">
            <div class="container">
                <div class="header-container">
                    <div class="header-item-left">
                     <h3>
                        <img src="{{ asset('assets/images/logo.png') }}" alt="" srcset=""
                        style="width: 215px;height: 50px;">
                     </h3>
                    </div>
                    <div class="header-item-right d-flex">
                        <label class="text-end">
                            <strong>{{ strtoupper(Auth::user()->nom) . ' ' . strtoupper(Auth::user()->prenom) }}</strong>
                            <br /> Vous êtes connecté(e) en tant que {{ Auth::user()->roles->first()->name }}
                        </label>
                        <div class="header-btn d-flex ms-2">

                            <div class="dropdown p-0 m-0">
                                <button class="btn-default profile-button dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('home') }}"><strong>Profile</strong></a>
                                    </a>


                                </ul>
                            </div>
                            <div class="ms-2">
                                <a class="btn logout-button" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                                    <i class="fa fa-power-off " aria-hidden="true"></i>
                                    <label for="">{{ __('Déconnexion') }}</label>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="second-header">
            <div class="container">
                <div class="row justify-content-between">

                    <div class="col-lg-4 col-md-6 d-flex">
                        <a href="{{ route('index') }}" class="btn second-head-btn me-4" style="">
                            <i class="fa fa-home" style=""></i>
                        </a>

                        @if (auth()->user()->hasPermission('read_produits'))
                        <div class="dropdown">
                            <button class="btn second-head-btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-cube" aria-hidden="true"></i> Produits
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="{{ route('produits.index') }}">Consulter</a></li>
                                <li><a class="dropdown-item" href="{{route('produits.create')}}">Ajouter un produit</a></li>
                                <li><a class="dropdown-item" href="{{ route('produits.multi') }}">Ajouter Multi Produit</a></li>
                            </div>
                        </div>
                        @endif

                        @if (auth()->user()->hasPermission('read_users'))
                        <div class="dropdown ms-1">
                            <button class="btn second-head-btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-users"></i> Utilisateurs
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="{{route('utilisateurs.index')}}">Consulter</a></li>
                                <li><a class="dropdown-item" href="{{route('utilisateurs.create')}}">Ajouter un Utilisateur</a></li>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if (auth()->user()->hasPermission('read_stocks'))
                    <div class="col-lg-8 col-md-6">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div class="dropdown">
                                    <button class="btn second-head-btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-info-circle me-1" aria-hidden="true"></i>Consultation
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="{{route('consultations.stocks')}}">Stock</a></li>
                                        <li><a class="dropdown-item" href="{{route('consultations.stockMinimum')}}">Alert stock minimum</a></li>

                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div class="dropdown">
                                    <button class="btn second-head-btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-sign-in me-1" aria-hidden="true"></i>Situation Entrée Stock
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="{{route('consultations.entrerStocks')}}" >Entrer stock muliticriteres</a></li>
                                        <li><a class="dropdown-item" href="{{route('consultations.entrerStocksGlobal')}}">Entrer stock Global</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div class="dropdown">
                                    <button class="btn second-head-btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-sign-in me-1" aria-hidden="true"></i>Situation Sortie Stock
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="{{route('consultations.sortieStocks')}}" >Sortie stock muliticriteres</a></li>
                                        <li><a class="dropdown-item" href="{{route('consultations.sortieStocksGlobal')}}">Sortie stock Global</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif


                </div>
            </div>
        </div>
        @include('layouts.dashboard.nav')
    </header> <!-- section-header.// -->


    <main id="app">
        @yield('content')
    </main>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
        </div>
        <strong>Copyright &copy; </strong>
    </footer>



    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/custom/roles.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('js/dataTableButtons.js') }}"></script>

    <script src="{{ asset('js/jszip.min.js') }}"></script>
    <script src="{{ asset('js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/buttons.print.min.js') }}"></script>

    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/index.js') }}"></script>



    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            /////// Prevent closing from click inside dropdown
            document.querySelectorAll('.dropdown-menu').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            })
        });
        // DOMContentLoaded  end
    </script>

    <script>
        $(document).ready(function() {
            $('.select').select2();
             //delete
             $(document).on('click', '.delete, #bulk-delete', function(e) {

            var that = $(this)

            e.preventDefault();

            var n = new Noty({
                text: "Vous êtes sûr",
                type: "alert",
                killer: true,
                buttons: [
                    Noty.button("Oui", 'btn btn-success mr-2', function() {
                        let url = that.closest('form').attr('action');
                        let data = new FormData(that.closest('form').get(0));

                        let loadingText =
                        '<i class="fa fa-circle-o-notch fa-spin"></i>';
                        let originalText = that.html();
                        that.html(loadingText);

                        n.close();

                        $.ajax({
                            url: url,
                            data: data,
                            method: 'post',
                            processData: false,
                            contentType: false,
                            cache: false,
                            success: function(response) {

                                $("#record__select-all").prop("checked",
                                    false);

                                $('.datatable').DataTable().ajax.reload();

                                new Noty({
                                    layout: 'topRight',
                                    type: 'alert',
                                    text: response,
                                    killer: true,
                                    timeout: 2000,
                                }).show();

                                that.html(originalText);
                            },

                        }); //end of ajax call

                    }),

                            Noty.button("Non", 'btn btn-danger ms-2', function() {
                                n.close();
                            })
                        ]
                    });

                    n.show();

                    }); //end of delete
                    })
    </script>


@if (Session::has('download.in.the.next.request'))
<script>
       setTimeout(() => {
        window.open('{{ '/'.Session::get('download.in.the.next.request')}}','_blank');
    },0);
</script>
@endif
{{-- route('index').'/' --}}
@stack('scripts')

</body>

</html>
