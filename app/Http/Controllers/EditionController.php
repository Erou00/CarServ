<?php

namespace App\Http\Controllers;

use App\Models\Bs;
use App\Models\Categorie;
use App\Models\Magasin;
use App\Models\Produit;
use App\Models\SousCategorie;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditionController extends Controller
{
    //

    public function index()
    {
        # code...
        $magasins = Magasin::all();
        return view('dashboard.editions.index',compact('magasins'));
    }

    public function approvisionnement(Request $request){

        // dd($request->all());

        $m = Magasin::select('nom')->where('id',$request->app_magasin_id)->first();
        $data = [
            'etat' => $request->etat == 'soldes' ? 'SOLDES' : 'NON SOLDES',
            'magasin' => $m ? $m->nom : 'Tous' ,
            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'commandes'     => $this->commandes($request->etat,$request->startDate,$request->endDate,$request->app_magasin_id),
            'marches'     => $this->marches($request->etat,$request->startDate,$request->endDate,$request->app_magasin_id),
            'conventions'     => $this->conventions($request->etat,$request->startDate,$request->endDate,$request->app_magasin_id),

        ];

        //dd($data);
        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/editions/approvisionnement', array('data' => $data));

        return $pdf->stream();


    }

    public function marches($etat,$startDate,$endDate,$magasin_id)
    {
        # code...
        $marches = DB::table('marches')
        ->selectRaw("(SELECT SUM(marche_details.qte) FROM marche_details
        WHERE  marche_details.marche_id = marches.id
        GROUP BY marche_details.marche_id) as qte_demandee,
        (select nom from fournisseurs f where f.id = marches.fournisseur_id) as fnom ,
        (SELECT SUM(bl_details.qte_livree) FROM bl_details,bls
        WHERE bl_details.bl_id = bls.id
        AND bls.marche_id = marches.id
        GROUP BY bls.marche_id) as qte_livree,
        marches.*")
        ->when($startDate || $endDate, function ($q) use ($startDate,$endDate) {
            $q->where(function ($query) use ($startDate,$endDate) {
                $startDate = $startDate;
                $endDate = $endDate;
                if ($startDate != '' && $endDate == '') {
                    $query->where('created_at', '>=', $startDate);
                } elseif ($startDate == '' && $endDate != '') {
                    $query->where('created_at', '<=', $endDate);
                } else {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            });
        })->when($magasin_id,function($q) use ($magasin_id) {
            $q->where('magasin_id',$magasin_id);
        })
        ->get();

        $collection = [];
        $data = [];
        if ($etat == '') {
            $collection =  $marches->filter(function ($item, $key){
                return $item->qte_demandee > $item->qte_livree ;
            });

        }

        if ($etat == 'soldes') {
            $collection =  $marches->filter(function ($item, $key){
                return $item->qte_demandee == $item->qte_livree ;
            });
        }


        return  $collection ;



    }

    public function commandes($etat,$startDate,$endDate,$magasin_id)
    {
        # code...

        $commandes = DB::table('commandes')
            ->selectRaw("(SELECT SUM(commande_details.qte) FROM commande_details
                WHERE  commande_details.commande_id = commandes.id
                GROUP BY commande_details.commande_id) as qte_demandee,
                (select nom from fournisseurs f where f.id = commandes.fournisseur_id) as fnom ,
                (SELECT SUM(bl_details.qte_livree) FROM bl_details,bls
                WHERE bl_details.bl_id = bls.id
                AND bls.commande_id = commandes.id
                GROUP BY bls.commande_id) as qte_livree,commandes.id,
                CONCAT(commandes.no_commande, '/', commandes.annee) AS no_commande")
                ->when($startDate || $endDate, function ($q) use ($startDate,$endDate) {
                    $q->where(function ($query) use ($startDate,$endDate) {
                        $startDate = $startDate;
                        $endDate = $endDate;
                        if ($startDate != '' && $endDate == '') {
                            $query->where('created_at', '>=', $startDate);
                        } elseif ($startDate == '' && $endDate != '') {
                            $query->where('created_at', '<=', $endDate);
                        } else {
                            $query->whereBetween('created_at', [$startDate, $endDate]);
                        }
                    });
                })->when($magasin_id,function($q) use ($magasin_id) {
                    $q->where('magasin_id',$magasin_id);
                })
                ->get();


                $collection = [];
                if ($etat == '') {
                    $collection =  $commandes->filter(function ($item, $key){
                        return $item->qte_demandee > $item->qte_livree ;
                    });

                }

                if ($etat == 'soldes') {
                    $collection =  $commandes->filter(function ($item, $key){
                        return $item->qte_demandee == $item->qte_livree ;
                    });
                }

                return $collection ;



    }

    public function conventions($etat,$startDate,$endDate,$magasin_id)
    {
        # code...
        $conventions = DB::table('conventions')
            ->selectRaw("(SELECT SUM(convention_details.qte) FROM convention_details
            WHERE  convention_details.convention_id = conventions.id
            GROUP BY convention_details.convention_id) as qte_demandee,
            (select nom from fournisseurs f where f.id = conventions.fournisseur_id) as fnom ,
            (SELECT SUM(bl_details.qte_livree) FROM bl_details,bls
            WHERE bl_details.bl_id = bls.id
            AND bls.convention_id = conventions.id
            GROUP BY bls.convention_id) as qte_livree,
            conventions.*")
            ->when($startDate || $endDate, function ($q) use ($startDate,$endDate) {
                $q->where(function ($query) use ($startDate,$endDate) {
                    $startDate = $startDate;
                    $endDate = $endDate;
                    if ($startDate != '' && $endDate == '') {
                        $query->where('created_at', '>=', $startDate);
                    } elseif ($startDate == '' && $endDate != '') {
                        $query->where('created_at', '<=', $endDate);
                    } else {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    }
                });
            })->when($magasin_id,function($q) use ($magasin_id) {
                $q->where('magasin_id',$magasin_id);
            })
            ->get();

        $collection = [];
        $data = [];
        if ($etat == '') {
            $collection =  $conventions->filter(function ($item, $key){
                return $item->qte_demandee > $item->qte_livree ;
            });

        }

        if ($etat == 'soldes') {
            $collection =  $conventions->filter(function ($item, $key){
                return $item->qte_demandee == $item->qte_livree ;
            });
        }

        return $collection;
    }

    public function statistiqueUtlistateurs(Request $request)
    {
        # code...
        $startDate = ($request->statistique_startDate) ?  Carbon::parse($request->statistique_startDate )->format('Y-m-d') : Carbon::now()->format('Y').'-01-01';
        $endDate = ($request->statistique_endDate) ?  Carbon::parse($request->$request->statistique_endDate)->format('Y-m-d')  : Carbon::now()->format('Y').'-12-31';
        $users = User::select('nom','prenom',
            DB::raw("(select count(*) from marches where user_id = users.id and created_at between '".$startDate."' and '".$endDate."'  ) as nbrMarches"),
            DB::raw("(select count(*) from commandes where user_id = users.id and date_commande between '".$startDate."' and '".$endDate."' ) as nbrCommandes"),
            DB::raw("(select count(*) from conventions where user_id = users.id and created_at between '".$startDate."' and '".$endDate."' ) as nbrConvention"),
            DB::raw("(select count(*) from entrers where user_id = users.id and created_at between '".$startDate."' and '".$endDate."' ) as nbrEntrers"),
            DB::raw("(select count(*) from bls where user_id = users.id and created_at between '".$startDate."' and '".$endDate."' ) as nbrBls"),
            DB::raw("(select count(*) from demandes where user_id = users.id and date_commande between '".$startDate."' and '".$endDate."' ) as nbrDemandes"),
            DB::raw("(select count(*) from bs where user_id = users.id and bs.date between '".$startDate."' and '".$endDate."') as nbrBs"),

            )
            ->when($request->statistique_magasin_id,function($q) use ($request) {
                            $q->where('magasin_id',$request->statistique_magasin_id);
                        })
        ->get();

        $m = "";
        if ($request->statistique_magasin_id) {
            $m = Magasin::select('nom')->where('id',$request->statistique_magasin_id)->first();
        }

       // dd($m);

        $data = [
            'magasin' => $m ? $m->nom : 'Tous' ,
            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'details' => $users,
        ];
        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/editions/statistique_d_utlisateurs', array('data' => $data));

        return $pdf->stream();

    }

    public function blPreparation (Request $request){
                $bs = Bs::select(
                    "*",
                    DB::raw("CONCAT(no_bl, '/',annee) AS name"),
                    DB::raw('DATE_FORMAT(bs.date, "%d/%m/%Y") as date')
                )->when($request->bl_magasin_id,function($q) use ($request) {
                    $q->where('magasin_id',$request->bl_magasin_id);
                })->with('entite')
                ->where('sortie','preparation')
                ->orderBy('annee', 'DESC')
                ->orderBy('no_bl', 'DESC')
                ->get();

                $m = Magasin::select('nom')->where('id',$request->bl_magasin_id)->first();


                // dd($bs);
                $data = [
                    'magasin' => $m ? $m->nom : 'Tous' ,
                    'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
                    'details' => $bs,
                ];
                ini_set('max_execution_time', 300);
                $pdf = \PDF::loadView('dashboard/rapports/editions/bl_preparation', array('data' => $data));

                return $pdf->stream();
    }
    public function produitsNonActive()
    {
        # code...
        $products = Produit::where('active',0)
                            ->with('categorie')
                            ->with('souscategorie')
                            ->with('marque')
                            ->with('uniteReglementaire')
                            ->get();

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
            'active' => 'NON ACTIVE',
            'details'     => $products,
            'categories' => $categories,
            'souscategories' => $scategories,

        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/produits', array('data' => $data))->setPaper('a4', 'portrait');

        return $pdf->download('la liste des articles non active.pdf');
    }
}
