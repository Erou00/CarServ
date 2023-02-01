<?php

namespace App\Http\Controllers;

use App\Models\Bl;
use App\Models\BlDetail;
use App\Models\Bs;
use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Convention;
use App\Models\Demande;
use App\Models\Devise;
use App\Models\Entite;
use App\Models\Entrer;
use App\Models\Fournisseur;
use App\Models\Groupe;
use App\Models\Magasin;
use App\Models\Marche;
use App\Models\Marque;
use App\Models\Produit;
use App\Models\SousCategorie;
use App\Models\UniteReglementaire;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

use function PHPSTORM_META\map;

class ConsultationController extends Controller
{



    public function entrerStocks()
    {
        # code...

        $categories = Categorie::all();
        $sousCategories = SousCategorie::all();
        $marques = Marque::all();
        $uniteReglementaires = UniteReglementaire::all();
        $fournisseurs = Fournisseur::all();
        $magasins = Magasin::all();
        $produits = Produit::where('active', 1)->get();
        $marches = Marche::orderBy('created_at', 'DESC')->get();
        $commandes = Commande::orderBy('created_at', 'DESC')->get();
        $conventions = Convention::orderBy('created_at', 'DESC')->get();
        $entrers = Entrer::orderBy('date', 'DESC')->get();
        $groupes = Groupe::all();


        // dd($produits);

        return view('dashboard.consultations.entrer_stocks', compact([
            'produits', 'categories', 'sousCategories', 'marques',
            'uniteReglementaires', 'entrers', 'fournisseurs', 'magasins', 'marches', 'commandes', 'conventions', 'groupes'
        ]));
    }

    public function entrerStocksFilter(Request $request)
    {
        $products = [];

        if (
            $request->categorie_id || $request->sous_categorie_id  || $request->marque_id ||
            $request->fournisseur_id || $request->groupe_id || $request->produit_id
            || $request->no_bl || $request->from  || $request->magasin_id || $request->entrer_id
            || $request->startDate  || $request->endDate
        ) {
            # code...
            $products1 = Produit::join('bl_details', 'produits.id', '=', 'bl_details.produit_id')
                ->join('bls', 'bl_details.bl_id', '=', 'bls.id')
                ->select(
                    'produits.*',
                    'bls.no_bl',
                    DB::raw('DATE_FORMAT(bls.date, "%d/%m/%Y") as date'),
                    'bls.fournisseur_id',
                    DB::raw("(SELECT SUM(bl_details.qte_livree)
                FROM bl_details
                WHERE bl_details.produit_id = produits.id
                AND bl_details.bl_id = bls.id
                GROUP BY bl_details.produit_id) as qte_livree")
                )

                ->when($request->produit_id, function ($q) use ($request) {
                    return $q->where('produits.id', $request->produit_id);
                })
                ->when($request->categorie_id, function ($q) use ($request) {
                    return $q->where('categorie_id', $request->categorie_id);
                })
                ->when($request->sous_categorie_id, function ($q) use ($request) {
                    return $q->where('sous_categorie_id', $request->sous_categorie_id);
                })
                ->when($request->marque_id, function ($q) use ($request) {
                    return $q->where('marque_id', $request->marque_id);
                })
                ->when($request->groupe_id, function ($q) use ($request) {
                    return $q->where('groupe_id', $request->groupe_id);
                })
                ->when($request->fournisseur_id, function ($q) use ($request) {
                    return $q->where('fournisseur_id', $request->fournisseur_id);
                })
                ->when($request->no_bl, function ($q) use ($request) {
                    return $q->where('no_bl', 'like', '%' . $request->no_bl . '%');
                })->when($request->from, function ($q) use ($request) {
                    return $q->where(function ($query) use ($request) {
                        if (str_contains($request->from, "commande_")) {
                            # code...
                            $commande_id =  intval(substr($request->from, strpos($request->from, "_") + 1));
                            $query->where('bls.commande_id', $commande_id)
                                ->where('bls.marche_id', null)
                                ->where('bls.convention_id', null);
                        }

                        if (str_contains($request->from, "marche_")) {
                            # code...
                            $marche_id =  intval(substr($request->from, strpos($request->from, "_") + 1));
                            $query->where('bls.marche_id', $marche_id)
                                ->where('bls.commande_id', null)
                                ->where('bls.convention_id', null);
                        }

                        if (str_contains($request->from, "convention_")) {
                            # code...
                            $convention_id =  intval(substr($request->from, strpos($request->from, "_") + 1));

                            $query->where('bls.convention_id', $convention_id)
                                ->where('bls.marche_id', null)
                                ->where('bls.commande_id', null);
                        }
                    });
                })->when($request->startDate || $request->endDate, function ($q) use ($request) {
                    $q->where(function ($query) use ($request) {
                        $startDate = $request->startDate;
                        $endDate = $request->endDate;
                        if ($startDate != '' && $endDate == '') {
                            $query->where('date', '>=', $startDate);
                        } elseif ($startDate == '' && $endDate != '') {
                            $query->where('date', '<=', $endDate);
                        } else {
                            $query->whereBetween('date', [$startDate, $endDate]);
                        }
                    });
                })->when($request->entrer_id, function ($q) use ($request) {
                    return $q->where('bls.no_bl', $request->entrer_id);
                })->where(function ($query) use ($request) {

                    if (Auth::user()->hasRole('master')) {
                        $query->when($request->magasin_id, function ($q) use ($request) {
                            return $q->where('bl_details.magasin_id', $request->magasin_id);
                        });
                    } else {
                        $query->where('bl_details.magasin_id', Auth::user()->magasin_id);
                    }
                })->where(function ($query) use ($request) {

                    if (Auth::user()->hasRole('master')) {
                        $query->when($request->sous_magasin_id, function ($q) use ($request) {
                            return $q->where('bl_details.sous_magasin_id', $request->sous_magasin_id);
                        });
                    } else {
                        $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item) {
                            return $item->id;
                        })->toArray();
                        $query->whereIn('bl_details.sous_magasin_id', $userSousMagasin);
                    }
                });


            $products2 = Produit::join('entrer_details', 'produits.id', '=', 'entrer_details.produit_id')
                ->join('entrers', 'entrer_details.entrer_id', '=', 'entrers.id')
                ->select(
                    'produits.*',
                    'entrers.no_bl',
                    DB::raw('DATE_FORMAT(entrers.date, "%d/%m/%Y") as date'),
                    'entrers.fournisseur_id',
                    DB::raw("(SELECT SUM(entrer_details.qte)
                 FROM entrer_details
                 WHERE entrer_details.produit_id = produits.id
                 AND entrer_details.entrer_id = entrers.id
                 GROUP BY entrer_details.produit_id) as qte_livree")
                )

                ->when($request->produit_id, function ($q) use ($request) {
                    return $q->where('produits.id', $request->produit_id);
                })
                ->when($request->categorie_id, function ($q) use ($request) {
                    return $q->where('categorie_id', $request->categorie_id);
                })
                ->when($request->sous_categorie_id, function ($q) use ($request) {
                    return $q->where('sous_categorie_id', $request->sous_categorie_id);
                })
                ->when($request->marque_id, function ($q) use ($request) {
                    return $q->where('marque_id', $request->marque_id);
                })
                ->when($request->groupe_id, function ($q) use ($request) {
                    return $q->where('groupe_id', $request->groupe_id);
                })
                ->when($request->fournisseur_id, function ($q) use ($request) {
                    return $q->where('fournisseur_id', $request->fournisseur_id);
                })->when($request->no_bl, function ($q) use ($request) {
                    return $q->where('no_bl', 'like', '%' . $request->no_bl . '%');
                })->when($request->startDate || $request->endDate, function ($q) use ($request) {
                    $q->where(function ($query) use ($request) {
                        $startDate = $request->startDate;
                        $endDate = $request->endDate;
                        if ($startDate != '' && $endDate == '') {
                            $query->where('date', '>=', $startDate);
                        } elseif ($startDate == '' && $endDate != '') {
                            $query->where('date', '<=', $endDate);
                        } else {
                            $query->whereBetween('date', [$startDate, $endDate]);
                        }
                    });
                })->when($request->from, function ($q) use ($request) {
                    return $q->where('no_entrer', $request->from);
                })->when($request->entrer_id, function ($q) use ($request) {
                    return $q->where('entrers.id', $request->entrer_id);
                })->where(function ($query) use ($request) {

                    if (Auth::user()->hasRole('master')) {
                        $query->when($request->magasin_id, function ($q) use ($request) {
                            return $q->where('entrer_details.magasin_id', $request->magasin_id);
                        });
                    } else {
                        $query->where('entrer_details.magasin_id', Auth::user()->magasin_id);
                    }
                })->where(function ($query) use ($request) {

                    if (Auth::user()->hasRole('master')) {
                        $query->when($request->sous_magasin_id, function ($q) use ($request) {
                            return $q->where('entrer_details.sous_magasin_id', $request->sous_magasin_id);
                        });
                    } else {
                        $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item) {
                            return $item->id;
                        })->toArray();
                        $query->whereIn('entrer_details.sous_magasin_id', $userSousMagasin);
                    }
                });


            $products = $products1->union($products2)->get();
        }

        if ($request->ajax()) {
            # code...

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('categorie', function (Produit $p) {
                    return ($p->categorie) ? $p->categorie->nom : '';
                })
                ->addColumn('sousCategorie', function (Produit $p) {
                    return ($p->sousCategorie) ? $p->sousCategorie->nom : '';
                })
                ->addColumn('marque', function (Produit $p) {
                    return ($p->marque) ? $p->marque->nom : '';
                })
                ->addColumn('ur', function (Produit $p) {
                    return ($p->uniteReglementaire) ? $p->uniteReglementaire->code : '';
                })->toJson();
        } else {

            if ($products) {
                # code...

                $categories_id = $products->map(function ($p) {
                    return $p->categorie_id;
                })->unique()->toArray();

                $sousCategories_id = $products->map(function ($p) {
                    return $p->sous_categorie_id;
                })->unique()->toArray();

                $marques_id = $products->map(function ($p) {
                    return $p->marque_id;
                })->unique()->toArray();

                $categories = Categorie::whereIn('id', $categories_id)->get();
                $scategories = SousCategorie::whereIn('id', $sousCategories_id)->get();
                $marques = Marque::whereIn('id', $marques_id)->get();

                $data = [
                    'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
                    'annee' => $request->annee,
                    'yearStart' => Carbon::parse($request->startDate)->format('Y'),
                    'yearEnd' => ($request->endDate) ? Carbon::parse($request->startDate)->format('Y') : now()->year,
                    'categories' => $categories,
                    'souscategories' => $scategories,
                    'details' => $products,
                ];
                ini_set('max_execution_time', 300);
                $pdf = \PDF::loadView('dashboard/rapports/entrer_stock', array('data' => $data))->setPaper('a4', 'portrait');
                # code...
                return $pdf->download('Entrer Stock Multicritere '.Carbon::now()->format('d/m/Y  H:i').'.pdf');
            } else {
                Session::flash('error', "Liste vide");
                return redirect()->back();
            }
        }
    }


    public function entrerStocksGlobal()
    {
        # code...

        $categories = Categorie::all();
        $sousCategories = SousCategorie::all();
        $marques = Marque::all();
        $produits = Produit::all();
        $groupes = Groupe::all();
        $magasins = Magasin::all();





        return view(
            'dashboard.consultations.entrer_stock_global',
            compact(['categories', 'sousCategories', 'marques', 'produits', 'groupes','magasins'])
        );
    }

    public function entrerStocksGlobalFilter(Request $request)
    {
        # code...
        $produits = [];
        if (
            $request->categorie_id || $request->sous_categorie_id  || $request->marque_id || $request->groupe_id
            || $request->startDate  || $request->endDate
        ) {
            $produits1 = Produit::join('bl_details', 'bl_details.produit_id', '=', 'produits.id')
                ->join('bls', 'bl_details.bl_id', '=', 'bls.id')
                ->when($request->categorie_id, function ($q) use ($request) {
                    return $q->where('produits.categorie_id', $request->categorie_id);
                })->when($request->sous_categorie_id, function ($q) use ($request) {
                    return $q->where('sous_categorie_id', $request->sous_categorie_id);
                })
                ->when($request->marque_id, function ($q) use ($request) {
                    return $q->where('marque_id', $request->marque_id);
                })
                ->when($request->groupe_id, function ($q) use ($request) {
                    return $q->where('groupe_id', $request->groupe_id);
                })->when($request->startDate || $request->endDate, function ($q) use ($request) {
                    $q->where(function ($query) use ($request) {
                        $startDate = $request->startDate;
                        $endDate = $request->endDate;
                        if ($startDate != '' && $endDate == '') {
                            $query->where('date', '>=', $startDate);
                        } elseif ($startDate == '' && $endDate != '') {
                            $query->where('date', '<=', $endDate);
                        } else {
                            $query->whereBetween('date', [$startDate, $endDate]);
                        }
                    });
                })->where(function ($query) use ($request) {

                    if (Auth::user()->hasRole('master')) {
                        $query->when($request->magasin_id, function ($q) use ($request) {
                            return $q->where('bl_details.magasin_id', $request->magasin_id);
                        });
                    } else {
                        $query->where('bl_details.magasin_id', Auth::user()->magasin_id);
                    }
                })->where(function ($query) use ($request) {

                    if (Auth::user()->hasRole('master')) {
                        $query->when($request->sous_magasin_id, function ($q) use ($request) {
                            return $q->where('bl_details.sous_magasin_id', $request->sous_magasin_id);
                        });
                    } else {
                        $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item) {
                            return $item->id;
                        })->toArray();
                        $query->whereIn('bl_details.sous_magasin_id', $userSousMagasin);
                    }
                })
                ->groupBy('produits.id')
                ->with('categorie')
                ->with('souscategorie')
                ->with('marque')
                ->with('uniteReglementaire')
                ->with('devise')
                ->select(
                    'produits.*','bls.date',
                    DB::raw('COALESCE(sum(bl_details.qte_livree),0) as qte_livree')
                )->get();

            $produits2 = Produit::join('entrer_details', 'entrer_details.produit_id', '=', 'produits.id')
                ->join('entrers', 'entrer_details.entrer_id', '=', 'entrers.id')
                ->when($request->categorie_id, function ($q) use ($request) {
                    return $q->where('produits.categorie_id', $request->categorie_id);
                })->when($request->categorie_id, function ($q) use ($request) {
                    return $q->where('produits.categorie_id', $request->categorie_id);
                })->when($request->sous_categorie_id, function ($q) use ($request) {
                    return $q->where('sous_categorie_id', $request->sous_categorie_id);
                })->when($request->marque_id, function ($q) use ($request) {
                    return $q->where('marque_id', $request->marque_id);
                })
                ->when($request->groupe_id, function ($q) use ($request) {
                    return $q->where('groupe_id', $request->groupe_id);
                })->when($request->startDate || $request->endDate, function ($q) use ($request) {
                    $q->where(function ($query) use ($request) {
                        $startDate = $request->startDate;
                        $endDate = $request->endDate;
                        if ($startDate != '' && $endDate == '') {
                            $query->where('date', '>=', $startDate);
                        } elseif ($startDate == '' && $endDate != '') {
                            $query->where('date', '<=', $endDate);
                        } else {
                            $query->whereBetween('date', [$startDate, $endDate]);
                        }
                    });
                })->where(function ($query) use ($request) {

                    if (Auth::user()->hasRole('master')) {
                        $query->when($request->magasin_id, function ($q) use ($request) {
                            return $q->where('entrer_details.magasin_id', $request->magasin_id);
                        });
                    } else {
                        $query->where('entrer_details.magasin_id', Auth::user()->magasin_id);
                    }
                })->where(function ($query) use ($request) {

                    if (Auth::user()->hasRole('master')) {
                        $query->when($request->sous_magasin_id, function ($q) use ($request) {
                            return $q->where('entrer_details.sous_magasin_id', $request->sous_magasin_id);
                        });
                    } else {
                        $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item) {
                            return $item->id;
                        })->toArray();
                        $query->whereIn('entrer_details.sous_magasin_id', $userSousMagasin);
                    }
                })


                ->groupBy('produits.id')
                ->with('categorie')
                ->with('souscategorie')
                ->with('marque')
                ->with('uniteReglementaire')
                ->with('devise')

                ->select(
                    'produits.*','entrers.*',
                    DB::raw('COALESCE(sum(entrer_details.qte),0) as qte_livree')
                )->get();



            $test  = $produits1->map(function ($p) use ($produits2) {
                foreach ($produits2 as  $value) {
                    # code...
                    if ($value->id == $p->id) {
                        # code...
                        $p->qte_livree = $p->qte_livree + $value->qte_livree;
                    }
                }

                return $p;
            });

            $test2  = $produits2->map(function ($p) use ($produits1) {

                return   !in_array($p, $produits1->toArray())  ?  $p : null;
            });



            $produits = $test->union($test2);
            //dd($produits);

        }




        if ($request->ajax()) {

            # code...
            return DataTables::of($produits)
                ->addIndexColumn()
                ->addColumn('categorie', function (Produit $p) {
                    return ($p->categorie) ? $p->categorie->nom : '';
                })
                ->addColumn('sousCategorie', function (Produit $p) {
                    return ($p->sousCategorie) ? $p->sousCategorie->nom : '';
                })
                ->addColumn('marque', function (Produit $p) {
                    return ($p->marque) ? $p->marque->nom : '';
                })
                ->addColumn('ur', function (Produit $p) {
                    return ($p->uniteReglementaire) ? $p->uniteReglementaire->code : '';
                })->toJson();
        } else {

            if ($produits) {
                # code...

                $categories_id = $produits->map(function ($p) {
                    return $p->categorie_id;
                })->unique()->toArray();

                $sousCategories_id = $produits->map(function ($p) {
                    return $p->sous_categorie_id;
                })->unique()->toArray();


                $categories = Categorie::whereIn('id', $categories_id)->get();
                $scategories = SousCategorie::whereIn('id', $sousCategories_id)->get();
                $data = [

                    'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
                    'annee' => $request->annee,
                    'yearStart' => Carbon::parse($request->startDate)->format('Y'),
                    'yearEnd' => ($request->endDate) ? Carbon::parse($request->startDate)->format('Y') : now()->year,
                    'categories' => $categories,
                    'souscategories' => $scategories,
                    'details' => $produits,

                ];
                ini_set('max_execution_time', 300);
                $pdf = \PDF::loadView('dashboard/rapports/entrer_stock_global', array('data' => $data))->setPaper('a4', 'portrait');
                # code...
                return $pdf->download('Entrer Stock Global'.Carbon::now()->format('d/m/Y  H:i').'.pdf');
            }
        }
    }


    public function sortieStocks()
    {
        # code...

        $produits = Produit::where('active', 1)->get();
        $categories = Categorie::all();
        $sousCategories = SousCategorie::all();
        $marques = Marque::all();
        $magasins = Magasin::all();
        $bs = Bs::all();
        $entitesMere = DB::table('entites AS n1')
            ->join('entites AS n2', 'n1.entite_mere_id', '=', 'n2.id')
            ->join('entites AS n3', 'n2.entite_mere_id', '=', 'n3.id')
            ->select('n2.nom as nom', 'n2.id as id')
            ->groupBy('n1.entite_mere_id')
            ->get();
        $entites = Entite::all();

        $groupes = Groupe::all();

        return view(
            'dashboard.consultations.sortie_stocks',
            compact([
                'produits', 'categories', 'sousCategories', 'marques',
                'magasins', 'bs', 'entitesMere', 'entites', 'groupes'
            ])
        );
    }

    public function sortieStocksFilter(Request $request)
    {
        # code...
        $products = [];
        if (
            $request->categorie_id || $request->sous_categorie_id  || $request->marque_id ||
             $request->groupe_id || $request->produit_id
            || $request->bl_id ||  $request->magasin_id || $request->entite_id || $request->entite_mere_id
            || $request->startDate  || $request->endDate
        ) {
            # code...
            $products = Produit::join('bs_details', 'produits.id', '=', 'bs_details.produit_id')
                ->join('bs', 'bs_details.bs_id', '=', 'bs.id')
                ->join('entites', 'bs.entite_id', 'like', 'entites.id')
                ->select(
                    'produits.*','bs.date',
                    DB::raw("CONCAT(bs.no_bl,'/',bs.annee) as no_bl"),
                    //DB::raw('DATE_FORMAT(bs.date, "%d/%m/%Y") as date'),
                    'bs_details.qte_donnee',
                    'entites.nom',
                    'entites.id as enID'
                )->when($request->produit_id, function ($q) use ($request) {
                    $q->where('produits.id', $request->produit_id);
                })->when($request->bl_id, function ($q) use ($request) {
                    $q->where('bs.id', $request->bl_id);
                })->when($request->categorie_id, function ($q) use ($request) {
                    $q->where('categorie_id', $request->categorie_id);
                })->when($request->sous_categorie_id, function ($q) use ($request) {
                    $q->where('sous_categorie_id', $request->sous_categorie_id);
                })->when($request->marque_id, function ($q) use ($request) {
                    $q->where('marque_id', $request->marque_id);
                })->when($request->groupe_id, function ($q) use ($request) {
                    $q->where('produits.groupe_id', $request->groupe_id);
                })->when($request->entite_id, function ($q) use ($request) {
                    $q->where('entite_id', $request->entite_id);
                })->when($request->entite_mere_id, function ($q) use ($request) {
                    $q->where('entites.entite_mere_id', $request->entite_mere_id);
                })->when($request->startDate || $request->endDate, function ($q) use ($request) {
                    $q->where(function ($query) use ($request) {
                        $startDate = $request->startDate;
                        $endDate = $request->endDate;
                        if ($startDate != '' && $endDate == '') {
                            $query->where('date', '>=', $startDate);
                        } elseif ($startDate == '' && $endDate != '') {
                            $query->where('date', '<=', $endDate);
                        } else {
                            $query->whereBetween('date', [$startDate, $endDate]);
                        }
                    });
                })->where(function ($query) use ($request) {

                    if (Auth::user()->hasRole('master')) {
                        $query->when($request->magasin_id, function ($q) use ($request) {
                            return $q->where('bs_details.magasin_id', $request->magasin_id);
                        });
                    } else {
                        $query->where('bs_details.magasin_id', Auth::user()->magasin_id);
                    }
                })->where(function ($query) use ($request) {

                    if (Auth::user()->hasRole('master')) {
                        $query->when($request->sous_magasin_id, function ($q) use ($request) {
                            return $q->where('bs_details.sous_magasin_id', $request->sous_magasin_id);
                        });
                    } else {
                        $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item) {
                            return $item->id;
                        })->toArray();
                        $query->whereIn('bs_details.sous_magasin_id', $userSousMagasin);
                    }
                })

                ->with('categorie')
                ->with('souscategorie')
                ->with('marque')
                ->with('uniteReglementaire')
                ->with('devise')
                //->groupBy('bs.entite_id')
                ->get();
        }


        if ($request->ajax()) {
            # code...
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('categorie', function (Produit $p) {
                    return $p->categorie->nom;
                })
                ->addColumn('sousCategorie', function (Produit $p) {
                    return $p->sousCategorie->nom;
                })
                ->addColumn('marque', function (Produit $p) {
                    return ($p->marque) ? $p->marque->nom : '';
                })
                ->addColumn('ur', function (Produit $p) {
                    return $p->uniteReglementaire->code;
                })
                ->editColumn('date', function ($date) {
                    return Carbon::parse($date->date)->format('m/d/Y');
                })
                // ->addColumn('pour', function () {
                //     return 'test' ;
                // })

                ->toJson();
        } else {

            if ($products) {
                # code...
                $categories_id = $products->map(function ($p) {
                    return $p->categorie_id;
                })->unique()->toArray();

                $sousCategories_id = $products->map(function ($p) {
                    return $p->sous_categorie_id;
                })->unique()->toArray();


                $categories = Categorie::whereIn('id', $categories_id)->get();
                $scategories = SousCategorie::whereIn('id', $sousCategories_id)->get();

                $data = [

                    'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
                    'annee' => $request->trier,
                    'yearStart' => Carbon::parse($request->startDate)->format('Y'),
                    'yearEnd' => ($request->endDate) ? Carbon::parse($request->startDate)->format('Y') : now()->year,
                    'categories' => $categories,
                    'souscategories' => $scategories,
                    'details' => $products,

                ];
                ini_set('max_execution_time', 300);

                if ($request->trier == '' || $request->trier == 'annee') {
                    $pdf = \PDF::loadView('dashboard/rapports/sortie_stock/stock', array('data' => $data))->setPaper('a4', 'portrait');
                } elseif ($request->trier == 'entitee') {
                    if ($request->entite_id) {
                        # code...
                        $entites_id = $products->map(function ($p) {
                            return $p->enID;
                        })->unique()->toArray();

                        $data['entites'] = Entite::whereIn('id', $entites_id)->get();
                    } else {
                        $data['entites'] = Entite::all();
                    }

                    $pdf = \PDF::loadView('dashboard/rapports/sortie_stock/par_entitee', array('data' => $data))->setPaper('a4', 'portrait');
                } elseif ($request->trier == 'entite_mere') {
                    if ($request->entite_mere_id) {
                        # code...
                        $data['entitesMere'] = DB::table('entites AS n1')
                            ->join('entites AS n2', 'n1.entite_mere_id', '=', 'n2.id')
                            ->join('entites AS n3', 'n2.entite_mere_id', '=', 'n3.id')
                            ->select('n2.nom as nom', 'n2.id as id')
                            ->groupBy('n1.entite_mere_id')
                            ->having('id', 'like', $request->entite_mere_id)

                            ->get();
                    } else {
                        $data['entitesMere'] = DB::table('entites AS n1')
                            ->join('entites AS n2', 'n1.entite_mere_id', '=', 'n2.id')
                            ->join('entites AS n3', 'n2.entite_mere_id', '=', 'n3.id')
                            ->select('n2.nom as nom', 'n2.id as id')
                            ->groupBy('n1.entite_mere_id')
                            ->get();
                    }

                    $data['entites'] = Entite::all();
                    $pdf = \PDF::loadView('dashboard/rapports/sortie_stock/par_entite_mere', array('data' => $data))->setPaper('a4', 'portarit');
                }
                return $pdf->download('Sortie Stock Multicritere '.Carbon::now()->format('d/m/Y  H:i').'.pdf');
            } else {

                Session::flash('error', "Liste vide");
                return redirect()->back();
            }
        }
    }

    public function sortieStocksGlobal()
    {
        # code...
        $produits = Produit::all();
        $categories = Categorie::all();
        $sousCategories = SousCategorie::all();
        $marques = Marque::all();
        $uniteReglementaires = UniteReglementaire::all();
        $devises = Devise::all();
        $magasins = Magasin::all();
        $groupes = Groupe::all();


        // dd($produits);

        return view('dashboard.consultations.sortie_stock_global', compact([
            'produits', 'categories', 'sousCategories', 'marques',
            'uniteReglementaires', 'magasins', 'groupes'
        ]));
    }

    public function sortieStocksGlobalFilter(Request $request)
    {
        # code...
        $produits = [];
        if (
            $request->categorie_id || $request->sous_categorie_id  || $request->marque_id || $request->produit_id
            ||  $request->groupe_id || $request->startDate  || $request->endDate || $request->magasin_id
        ) {
            $produits = Produit::join('bs_details', 'produits.id', '=', 'bs_details.produit_id')
                ->join('bs', 'bs_details.bs_id', '=', 'bs.id')

                ->when($request->produit_id, function ($q) use ($request) {
                    return $q->where('produits.id', $request->produit_id);
                })
                ->when($request->categorie_id, function ($q) use ($request) {
                    return $q->where('categorie_id', $request->categorie_id);
                })
                ->when($request->sous_categorie_id, function ($q) use ($request) {
                    return $q->where('sous_categorie_id', $request->sous_categorie_id);
                })
                ->when($request->marque_id, function ($q) use ($request) {
                    return $q->where('marque_id', $request->marque_id);
                })
                ->when($request->groupe_id, function ($q) use ($request) {
                    return $q->where('groupe_id', $request->groupe_id);
                })->when($request->startDate || $request->endDate, function ($q) use ($request) {
                    $q->where(function ($query) use ($request) {
                        $startDate = $request->startDate;
                        $endDate = $request->endDate;
                        if ($startDate != '' && $endDate == '') {
                            $query->where('date', '>=', $startDate);
                        } elseif ($startDate == '' && $endDate != '') {
                            $query->where('date', '<=', $endDate);
                        } else {
                            $query->whereBetween('date', [$startDate, $endDate]);
                        }
                    });
                })->where(function ($query) use ($request) {

                    if (Auth::user()->hasRole('master')) {
                        $query->when($request->magasin_id, function ($q) use ($request) {
                            return $q->where('bs_details.magasin_id', $request->magasin_id);
                        });
                    } else {
                        $query->where('bs_details.magasin_id', Auth::user()->magasin_id);
                    }
                })->where(function ($query) use ($request) {

                    if (Auth::user()->hasRole('master')) {
                        $query->when($request->sous_magasin_id, function ($q) use ($request) {
                            return $q->where('bs_details.sous_magasin_id', $request->sous_magasin_id);
                        });
                    } else {
                        $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item) {
                            return $item->id;
                        })->toArray();
                        $query->whereIn('bs_details.sous_magasin_id', $userSousMagasin);
                    }
                })
                ->groupBy('produits.id')
                ->with('categorie')
                ->with('souscategorie')
                ->with('marque')
                ->with('uniteReglementaire')
                ->with('devise')
                ->get([
                    'produits.*','bs.date',
                    DB::raw("COALESCE(SUM(bs_details.qte_donnee),0) as qte_donnee"),
                    DB::raw("COALESCE((SELECT SUM(demande_details.qte_demandee)
                    FROM demande_details,demandes
                    WHERE demande_details.produit_id = produits.id
                    AND demande_details.demande_id = demandes.id
                    GROUP BY demande_details.produit_id)
                    , 0) as qte_demandee")
                ]);
        }
        if ($request->ajax()) {
            # code...
            return DataTables::of($produits)
                ->addIndexColumn()
                ->addColumn('categorie', function (Produit $p) {
                    return $p->categorie->nom;
                })
                ->addColumn('sousCategorie', function (Produit $p) {
                    return $p->sousCategorie->nom;
                })
                ->addColumn('marque', function (Produit $p) {
                    return ($p->marque) ? $p->marque->nom : '';
                })
                ->addColumn('ur', function (Produit $p) {
                    return $p->uniteReglementaire->code;
                })
                // ->addColumn('pour', function () {
                //     return 'test' ;
                // })

                ->toJson();
        } else {


            if ($produits) {
                # code...
                $categories_id = $produits->map(function ($p) {
                    return $p->categorie_id;
                })->unique()->toArray();

                $sousCategories_id = $produits->map(function ($p) {
                    return $p->sous_categorie_id;
                })->unique()->toArray();


                $categories = Categorie::whereIn('id', $categories_id)->get();
                $scategories = SousCategorie::whereIn('id', $sousCategories_id)->get();

                $data = [

                    'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
                    'annee' => $request->annee,
                    'yearStart' => Carbon::parse($request->startDate)->format('Y'),
                    'yearEnd' => ($request->endDate) ? Carbon::parse($request->startDate)->format('Y') : now()->year,
                    'categories' => $categories,
                    'souscategories' => $scategories,
                    'details' => $produits,

                ];
                ini_set('max_execution_time', 300);
                $pdf = \PDF::loadView('dashboard/rapports/sortie_stock_global', array('data' => $data))->setPaper('a4', 'portrait');
                # code...
                return $pdf->download('Sortie Stock Global '.Carbon::now()->format('d/m/Y  H:i').'.pdf');
            } else {
                Session::flash('error', "Liste vide");
                return redirect()->back();
            }
        }
    }

    public function stockMinimum()
    {
        # code...
        $categories = Categorie::all();
        $marques = Marque::all();

        $stocks = Produit::select(
            'produits.*',
            DB::raw('COALESCE((select qte from stocks where stocks.produit_id = produits.id),0) as qte')
        )
            ->with('user')
            ->with('categorie')
            ->with('souscategorie')
            ->with('marque')
            ->with('uniteReglementaire')
            ->with('devise')
            ->where('active', 0)
            ->get();

        // dd($stocks);
        return view('dashboard.consultations.stockMinimum', compact(['stocks', 'categories', 'marques']));
    }



    public function commandeByEntiteView()
    {
        # code...
        return view('dashboard.consultations.commandesByEntite');
    }

    public function commandeByEntite($id)
    {
        # code...
        $demandes =  DB::table('demandes')
            ->join('demande_details', 'demandes.id', '=', 'demande_details.demande_id')
            ->join('produits', 'produits.id', '=', 'demande_details.produit_id')
            ->join('unite_reglementaires', 'unite_reglementaires.id', '=', 'produits.unite_reglementaire_id')
            ->join('devises', 'devises.id', '=', 'produits.devise_id')
            ->select(
                'produits.*',
                'devises.code',
                'unite_reglementaires.code',
                'demandes.*',
                'demande_details.*',
                DB::raw("COALESCE((SELECT SUM(bs_details.qte_donnee) FROM bs_details,bs
                                WHERE bs_details.produit_id = produits.id
                                AND bs_details.bs_id = bs.id
                                AND bs.demande_id = demandes.id
                                AND bs.sortie = true
                                GROUP BY bs_details.produit_id),0) as product_stock")
            )
            ->where('demandes.entite_id', $id)
            ->get();

        return response()->json([
            "error" => false,
            "demandes" => $demandes,

        ], 200);
    }

    public function produitsByGroupeView()
    {
        # code...
        $groupes = Groupe::all();
        return view('dashboard.consultations.produitsByGroupe', compact('groupes'));
    }

    public function produitsByGroupe($id)
    {
        # code...
        $produits = Produit::with('user')
            ->with('categorie')
            ->with('souscategorie')
            ->with('marque')
            ->with('uniteReglementaire')
            ->with('devise')
            ->with('stock', function ($q) {
                $q->with('magasin');
            })->where('groupe_id', $id)
            ->where('active', 1)
            ->get();

        return response()->json([
            "error" => false,
            "produits" => $produits,

        ], 200);
    }


    public function stocks(Request $request)
    {
        # code...

        if ($request->ajax()) {

            $products = Produit::when($request->produit_id, function ($q) use ($request) {
                return $q->where('id', $request->produit_id);
            })->when($request->categorie_id, function ($q) use ($request) {
                return $q->where('categorie_id', $request->categorie_id);
            })->when($request->sous_categorie_id, function ($q) use ($request) {
                return $q->where('sous_categorie_id', $request->sous_categorie_id);
            })->when($request->marque_id, function ($q) use ($request) {
                return $q->where('marque_id', $request->marque_id);
            })
            // ->where(function($query) use ($request) {

            //     if (Auth::user()->hasRole('master')) {
            //         $query->when($request->magasin_id, function ($q) use ($request) {
            //             return $q->where('magasin_id', $request->magasin_id);
            //         });
            //     }else{
            //         $query->where('magasin_id',Auth::user()->magasin_id);
            //     }

            // })
            ->select('*', DB::raw("COALESCE((SELECT SUM(bs_details.qte_donnee) FROM bs_details,bs
            WHERE bs_details.produit_id = produits.id
            AND bs_details.bs_id = bs.id
            AND bs.sortie = 'preparation'
            AND bs_details.magasin_id = ".((Auth::user()->hasRole('master') ) ? $request->magasin_id : Auth::user()->magasin_id)."
            GROUP BY bs_details.produit_id),0) as product_stock"),
            DB::raw("COALESCE((select qte from stocks
                    where  stocks.produit_id = produits.id
                    AND stocks.magasin_id = ".((Auth::user()->hasRole('master') ) ? $request->magasin_id : Auth::user()->magasin_id)."),0) as stock"))->where('active', 1)->get();

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('categorie', function (Produit $p) {
                    return $p->categorie->nom;
                })
                ->addColumn('sousCategorie', function (Produit $p) {
                    return $p->sousCategorie->nom;
                })
                ->addColumn('marque', function (Produit $p) {
                    return ($p->marque) ? $p->marque->nom : '';
                })
                // ->addColumn('stock', function (Produit $p) {

                //     $qte_stock = 0;

                //     foreach ($p->stocks as $s) {
                //         if ($s->magasin_id  == Auth::user()->magasin_id) {
                //             $qte_stock = $s->qte;
                //         }
                //     }

                //     return $qte_stock;
                // })
                ->addColumn('ur', function (Produit $p) {
                    return $p->uniteReglementaire->code;
                })->setRowClass(function (Produit $p) {


                    return $p->stock < $p->stock_min ? 'red-bg' : '';
                })

                ->toJson();
        }

        $categories = Categorie::all();
        $marques = Marque::all();
        $sousCategories = SousCategorie::all();
        $produits = Produit::all();
        $magasins = Magasin::all();

        return view('dashboard.consultations.stocks', compact(['produits',
         'categories', 'sousCategories', 'marques','magasins']));
    }

    public function stocksPrint(Request $request)
    {

        # code...
        // dd($request->all());
        $produits = Produit::when($request->produit_id, function ($q) use ($request) {
            return $q->where('id', $request->produit_id);
        })->when($request->categorie_id, function ($q) use ($request) {
            return $q->where('categorie_id', $request->categorie_id);
        })->when($request->sous_categorie_id, function ($q) use ($request) {
            return $q->where('sous_categorie_id', $request->sous_categorie_id);
        })->when($request->marque_id, function ($q) use ($request) {
            return $q->where('marque_id', $request->marque_id);
        })

        ->select('*', DB::raw("COALESCE((SELECT SUM(bs_details.qte_donnee) FROM bs_details,bs
        WHERE bs_details.produit_id = produits.id
        AND bs_details.bs_id = bs.id
        AND bs.sortie = 'preparation'
        AND bs_details.magasin_id = ".((Auth::user()->hasRole('master') ) ? $request->magasin_id : Auth::user()->magasin_id)."
        GROUP BY bs_details.produit_id),0) as product_stock"),
        DB::raw("COALESCE((select qte from stocks
                where  stocks.produit_id = produits.id
                AND stocks.magasin_id = ".((Auth::user()->hasRole('master') ) ? $request->magasin_id : Auth::user()->magasin_id)."),0) as stock"))->where('active', 1)->get();


        // dd($produits);


        $categories_id =  $produits->map(function ($p) {
            return $p->categorie_id;
        })->unique()->toArray();

        $sousCategories_id = $produits->map(function ($p) {
            return $p->sous_categorie_id;
        })->unique()->toArray();

        $categories = Categorie::whereIn('id', $categories_id)->get();
        $scategories = SousCategorie::whereIn('id', $sousCategories_id)->get();



        $data = [

            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'details'     => $produits,
            'categories' => $categories,
            'souscategories' => $scategories,


        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/stocks', array('data' => $data))->setPaper('a4', 'portrait');

        return $pdf->download('etat de stock '.Carbon::now()->format('d/m/Y  H:i').'.pdf');
    }
}
