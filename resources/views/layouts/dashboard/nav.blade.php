
        <nav class="navbar navbar-expand-lg main-navbar">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="main_nav">
                    <ul class="navbar-nav">

                        @if (auth()->user()->hasPermission('read_conventions') || auth()->user()->hasPermission('read_marches') || auth()->user()->hasPermission('read_commandes'))
                        <li class="nav-item dropdown has-megamenu">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fa fa-archive" aria-hidden="true"></i> Approvisionnement
                            </a>
                            <div class="dropdown-menu megamenu" role="menu">
                                <div class="container">
                                    <div class="row g-3">

                                        @if (auth()->user()->hasPermission('read_commandes'))
                                        <div class="col-lg-3 col-6">
                                            <div class="col-megamenu">
                                                <h6 class="title">Bon Commande</h6>
                                                <ul class="list-unstyled">
                                                    <li><a class="nav-link megamenulink"
                                                            href="{{ route('commandes.index') }}">Consulter les
                                                            Commandes</a></li>
                                                    <li><a class="nav-link megamenulink"
                                                            href="{{ route('commandes.create') }}">Ajouter une
                                                            Commande</a></li>

                                                    @if (Auth::user()->hasRole('master'))
                                                    <li><a class="nav-link megamenulink"
                                                        href="{{ route('commandes.autre_magasins') }}">Consulter les autre Magasins</a>
                                                    </li>
                                                    @endif




                                                </ul>
                                            </div> <!-- col-megamenu.// -->
                                        </div><!-- end col-3 -->
                                        @endif

                                        @if (auth()->user()->hasPermission('read_marches'))
                                        <div class="col-lg-3 col-6">
                                            <div class="col-megamenu">
                                                <h6 class="title">Marché</h6>
                                                <ul class="list-unstyled">
                                                    <li><a class="nav-link megamenulink"
                                                            href="{{ route('marches.index') }}">Consulter les
                                                            Marchés</a></li>
                                                    <li><a class="nav-link megamenulink"
                                                            href="{{ route('marches.create') }}">Ajouter un Marché</a>
                                                    </li>
                                                    @if (Auth::user()->hasRole('master'))
                                                    <li><a class="nav-link megamenulink"
                                                        href="{{ route('marches.autre_magasins') }}">Consulter les autre Magasins</a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div> <!-- col-megamenu.// -->
                                        </div><!-- end col-3 -->
                                        @endif

                                        @if (auth()->user()->hasPermission('read_conventions'))
                                        <div class="col-lg-3 col-6">
                                            <div class="col-megamenu">
                                                <h6 class="title">Convention</h6>
                                                <ul class="list-unstyled">
                                                    <li><a class="nav-link megamenulink"
                                                            href="{{ route('conventions.index') }}">Consulter les
                                                            Conventions</a></li>
                                                    <li><a class="nav-link megamenulink"
                                                            href="{{ route('conventions.create') }}">Ajouter une
                                                            Convention</a></li>

                                                    @if (Auth::user()->hasRole('master'))
                                                    <li><a class="nav-link megamenulink"
                                                        href="{{ route('conventions.autre_magasins') }}">Consulter les autre Magasins</a>
                                                    </li>
                                                    @endif

                                                </ul>
                                            </div> <!-- col-megamenu.// -->
                                        </div>
                                        @endif


                                        <div class="col-lg-3 col-6">
                                            <div class="col-megamenu">
                                                <h6 class="title">Lettre</h6>
                                                <ul class="list-unstyled">
                                                    <li><a class="nav-link megamenulink" href="#">Consulter les
                                                            Lettres</a></li>
                                                    <li><a class="nav-link megamenulink" href="#">Ajouter une
                                                            Lettre</a></li>

                                                </ul>
                                            </div> <!-- col-megamenu.// -->
                                        </div><!-- end col-3 -->
                                    </div><!-- end row -->
                                </div>

                            </div> <!-- dropdown-mega-menu.// -->
                        </li>
                        @endif

                        @if (auth()->user()->hasPermission('read_bl') )

                        <li class="nav-item dropdown has-megamenu">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fa fa-sign-in" aria-hidden="true"></i> Entrée Stock
                            </a>

                            <div class="dropdown-menu megamenu container" role="menu">


                                <div class="container">
                                    <div class="row g-3">
                                        <div class="col-lg-3 col-6">
                                            <div class="col-megamenu">
                                                <h6 class="title">Bon commande</h6>
                                                <ul class="list-unstyled">

                                                    <li><a href="{{route('bls.getBlofCommande')}}" class="nav-link megamenulink">Consulter</a></li>
                                                    <li><a class="nav-link megamenulink"
                                                        href="{{route('createBlCommande')}}">Ajouter</a></li>

                                                        @if (Auth::user()->hasRole('master'))
                                                        <li><a class="nav-link megamenulink"
                                                            href="{{ route('bls.autre_magasins') }}">Consulter les autre Magasins</a>
                                                        </li>
                                                        @endif

                                                </ul>
                                            </div> <!-- col-megamenu.// -->
                                        </div><!-- end col-3 -->
                                        <div class="col-lg-3 col-6">
                                            <div class="col-megamenu">
                                                <h6 class="title">Marche</h6>
                                                <ul class="list-unstyled">
                                                    <li><a href="{{route('bls.getBlofMarche')}}" class="nav-link megamenulink">Consulter</a></li>
                                                    <li><a class="nav-link megamenulink"
                                                        href="{{route('createBlMarche')}}">Ajouter</a></li>

                                                    @if (Auth::user()->hasRole('master'))
                                                    <li><a class="nav-link megamenulink"
                                                        href="{{ route('bls.autre_magasins') }}">Consulter les autre Magasins</a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div> <!-- col-megamenu.// -->
                                        </div><!-- end col-3 -->
                                        <div class="col-lg-3 col-6">
                                            <div class="col-megamenu">
                                                <h6 class="title">Convention</h6>
                                                <ul class="list-unstyled">
                                                    <li><a href="{{route('bls.getBlofConvention')}}" class="nav-link megamenulink">Consulter</a></li>
                                                    <li><a class="nav-link megamenulink"
                                                        href="{{route('createBlConvention')}}">Ajouter</a></li>
                                                    @if (Auth::user()->hasRole('master'))
                                                    <li><a class="nav-link megamenulink"
                                                        href="{{ route('bls.autre_magasins') }}">Consulter les autre Magasins</a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div> <!-- col-megamenu.// -->
                                        </div>

                                        <div class="col-lg-3 col-6">
                                            <div class="col-megamenu">
                                                <h6 class="title">Autre</h6>
                                                <ul class="list-unstyled">
                                                    <li><a href="{{route('autres.index')}}" class="nav-link megamenulink">Consulter</a></li>
                                                    <li><a class="nav-link megamenulink"
                                                        href="{{route('autres.create')}}">Ajouter</a></li>

                                                        @if (Auth::user()->hasRole('master'))
                                                        <li><a class="nav-link megamenulink"
                                                            href="{{ route('autres.autre_magasins') }}">Consulter les autre Magasins</a>
                                                        </li>
                                                        @endif
                                                </ul>
                                            </div> <!-- col-megamenu.// -->
                                        </div>

                                    </div><!-- end row -->

                                </div>

                            </div>

                        </li>

                        @endif

                        @if (auth()->user()->hasPermission('read_inventaires'))
                            <li class="nav-item dropdown has-megamenu">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fa fa-cubes" aria-hidden="true"></i> Inventaire
                            </a>

                            <div class="dropdown-menu megamenu container" role="menu">

                                <div class="container">
                                            <div class="row g-3">

                                            @if (auth()->user()->hasPermission('create_inventaires'))
                                                <div class="col-lg-6 col-6">
                                                    <div class="col-megamenu">
                                                        <h6 class="title"><a href="{{route('inventaires.create')}}">Nouveau Inventaire</a></h6>
                                                    </div> <!-- col-megamenu.// -->
                                                </div><!-- end col-3 -->
                                            @endif

                                            <div class="col-lg-6 col-6">
                                                <div class="col-megamenu">
                                                    <h6 class="title"><a href="{{route('inventaires.index')}}">Consulter</a></h6>



                                                    <ul class="list-unstyled">

                                                        @if (Auth::user()->hasRole('master'))
                                                        <li><a class="nav-link megamenulink"
                                                            href="{{ route('inventaires.autre_magasins') }}">Consulter les autre Magasins</a>
                                                        </li>
                                                        @endif
                                                </ul>
                                                </div> <!-- col-megamenu.// -->
                                            </div><!-- end col-3 -->


                                        </div><!-- end row -->

                                </div>

                            </div>

                        </li>
                        @endif


                        @if (auth()->user()->hasPermission('read_demandes') )

                        <li class="nav-item dropdown has-megamenu">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fa fa-sign-out" aria-hidden="true"></i>

                                Commande Interne
                            </a>

                            <div class="dropdown-menu megamenu container" role="menu">

                                <div class="container">
                                    <div class="row g-3">
                                        @if (auth()->user()->hasPermission('create_demandes') )

                                        <div class="col-lg-4 col-6">
                                            <div class="col-megamenu">

                                                <h6 class="title"><a href="{{route('demandes.create')}}">Nouvelle Demande</a></h6>

                                            </div> <!-- col-megamenu.// -->
                                        </div><!-- end col-3 -->
                                        @endif
                                        <div class="col-lg-4 col-6">
                                            <div class="col-megamenu">
                                                <h6 class="title"><a href="{{route('demandes.storeDemandePeriodique')}}">Demande Periodique</a></h6>


                                            </div> <!-- col-megamenu.// -->
                                        </div><!-- end col-3 -->
                                        <div class="col-lg-3 col-6">
                                            <div class="col-megamenu">
                                                <h6 class="title"><a href="{{route('demandes.index')}}">Historique</a></h6>
                                                <ul class="list-unstyled">

                                                        @if (Auth::user()->hasRole('master'))
                                                        <li><a class="nav-link megamenulink"
                                                            href="{{ route('demandes.autre_magasins') }}">Consulter les autre Magasins</a>
                                                        </li>
                                                        @endif
                                                </ul>

                                            </div> <!-- col-megamenu.// -->
                                        </div>

                                    </div><!-- end row -->
                                </div>

                            </div>

                        </li>

                        @endif


                        @if (auth()->user()->hasPermission('read_demandes_extern'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-external-link" aria-hidden="true"></i></i>Commande Externe
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                            <li><a class="dropdown-item" href="{{route('demandes-externe.create')}}">Nouvelle Demande</a><li>
                            <li><a class="dropdown-item" href="{{route('demandes-externe.index')}}">Mes demandes</a><li>

                            </ul>
                        </li>
                        @endif

                        @if (auth()->user()->hasPermission('read_bs') )
                        <li class="nav-item dropdown has-megamenu">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fa fa-sign-out" aria-hidden="true"></i> Sortie Stock
                            </a>

                            <div class="dropdown-menu megamenu container" role="menu" >
                                <div class="container">
                                     <div class="row g-2">
                                    <div class="col-lg-6 col-6">
                                        <div class="col-megamenu" >
                                            <h6 class="title">Bon Livraison</h6>
                                            <ul class="list-unstyled">
                                                <li><a class="nav-link megamenulink" href="{{route('bs.index')}}">Consulter</a></li>
                                                <li><a class="nav-link megamenulink" href="{{route('bs.create')}}">Ajouter</a></li>
                                                @if (Auth::user()->hasRole('master'))
                                                <li><a class="nav-link megamenulink"
                                                    href="{{ route('bs.autre_magasins') }}">Consulter les autre Magasins</a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div> <!-- col-megamenu.// -->
                                    </div><!-- end col-3 -->
                                    <div class="col-lg-6 col-6">
                                        <div class="col-megamenu">
                                            <h6 class="title">Bon Sortie</h6>
                                            <ul class="list-unstyled">
                                                <li><a class="nav-link megamenulink" href="{{route('bs.externe')}}">Consulter</a></li>
                                                <li><a class="nav-link megamenulink" href="{{route('bs.mbs')}}">Ajouter</a></li>
                                                @if (Auth::user()->hasRole('master'))
                                                <li><a class="nav-link megamenulink"
                                                    href="{{ route('bonSortie.autre_magasins') }}">Consulter les autre Magasins</a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div> <!-- col-megamenu.// -->
                                    </div><!-- end col-3 -->


                                </div><!-- end row -->
                                </div>


                            </div>

                        </li>

                         @endif

                         @if (auth()->user()->hasPermission('read_entites'))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-cogs me-1" aria-hidden="true"></i>Paramétrage
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">


                                <li><a class="dropdown-item" href="{{route('entites.index')}}">Entités</a><li>
                                    <li><a class="dropdown-item" href="{{route('types.index')}}">Type Entités</a><li>

                                    <li><a class="dropdown-item" href="{{route('groupes.index')}}">Groupes</a><li>
                                    <li><a class="dropdown-item" href="{{route('fournisseurs.index')}}">Fournisseur</a><li>
                                    <li><a class="dropdown-item" href="{{route('categories.index')}}">Catégorie</a><li>
                                    <li><a class="dropdown-item" href="{{route('sous_categories.index')}}">Sous Categories</a><li>
                                    <li><a class="dropdown-item" href="{{route('marques.index')}}">Marques</a><li>
                                    <li><a class="dropdown-item" href="{{route('villes.index')}}">Ville</a><li>
                                    <li><a class="dropdown-item" href="{{route('pays.index')}}">Pays</a><li>
                                    <li><a class="dropdown-item" href="{{route('unite_reglementaire.index')}}">Unite Reglementaire</a><li>
                                    <li><a class="dropdown-item" href="{{route('devises.index')}}">Devise </a><li>
                                    <li><a class="dropdown-item" href="{{route('roles.index')}}">Profiles</a><li>
                                    <li><a class="dropdown-item" href="{{route('magasins.index')}}">Magasins </a><li>
                                    <li><a class="dropdown-item" href="{{route('sous_magasins.index')}}">Sous Magasins </a><li>

                                    <li><a class="dropdown-item" href="{{route('tvas.index')}}">TVA</a><li>

                                </ul>
                            </li>
                         @endif

                         @if (auth()->user()->hasPermission('read_conventions') || auth()->user()->hasPermission('read_marches') || auth()->user()->hasPermission('read_commandes'))
                         <li class="nav-item ">
                            <a class="nav-link" href="{{ route('editions.index') }}">
                                <i class="fa fa-list-alt me-1" aria-hidden="true"></i>Edition
                            </a>
                        <li>
                        @endif

                    </ul>


                </div> <!-- navbar-collapse.// -->
            </div> <!-- container-fluid.// -->
        </nav>
