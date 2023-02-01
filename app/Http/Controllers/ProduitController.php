<?php

namespace App\Http\Controllers;

use App\Models\BlDetail;
use App\Models\BsDetail;
use App\Models\Categorie;
use App\Models\CommandeDetail;
use App\Models\ConventionDetail;
use App\Models\DemandeDetail;
use App\Models\Devise;
use App\Models\Groupe;
use App\Models\HistoriqueProduit;
use App\Models\InventaireDetail;
use App\Models\MarcheDetail;
use App\Models\Marque;
use App\Models\Produit;
use App\Models\SousCategorie;
use App\Models\UniteReglementaire;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

use function GuzzleHttp\Promise\all;

class ProduitController extends Controller
{

    public function index(Request $request)
    {
        //

        if ($request->ajax()) {
            # code...
            //dd($request->active);
            $products = Produit::when($request->produit_id, function ($q) use ($request) {
                return $q->where('id',$request->produit_id);
            })->when($request->categorie_id, function ($q) use ($request) {
                return $q->orWhere('categorie_id', $request->categorie_id);
            })->when($request->sous_categorie_id, function ($q) use ($request) {
                return $q->orWhere('sous_categorie_id', $request->sous_categorie_id);
            })->when($request->marque_id, function ($q) use ($request) {
                return $q->orWhere('marque_id', $request->marque_id);
            })->get();

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
                ->addColumn('actions', 'dashboard.produits.data_table.actions')
                ->rawColumns(['actions'])
                ->toJson();
        }

        $categories = Categorie::all();
        $sousCategories =SousCategorie::all();
        $marques = Marque::all();
        $produits = Produit::all();

        return view('dashboard.produits.index',
        compact(['categories','sousCategories','marques','produits']));

    }

    public function data()
    {
        # code...
        $produits = Produit::with('user')
        ->with('historiquesProduit',function($q){
             $q->with('user')->latest();
        })->with('categorie')
        ->with('souscategorie')
        ->with('marque')
        ->with('uniteReglementaire')
        ->with('devise')
        ->with('stock')
        ->where('active',1)
        ->get();

        //dd($produits);

        return response()->json([
            "error" => false,
            "produits" => $produits ,

            ],200);

    }

    public function data2()
    {
        # code...
        $produits = Produit::join('categories','produits.categorie_id','=','categories.id')
                            ->join('categorie_user','categories.id','=','categorie_user.categorie_id')
                            ->where('categorie_user.user_id',Auth::id())
                            ->where('produits.active',1)
                            ->with('souscategorie')
                            ->with('marque')
                            ->with('uniteReglementaire')
                            ->select('*','produits.*','categories.nom',
                            DB::raw("COALESCE((select qte from stocks
                            where  stocks.produit_id = produits.id
                            AND stocks.magasin_id = ". Auth::user()->magasin_id."),0) as stock"))



                            ->get();
        //dd($produits);

        return response()->json([
            "error" => false,
            "produits" => $produits ,

            ],200);

    }

    public function create()
    {
        //

        $categories = Categorie::all();
        $sousCategories = SousCategorie::all();
        $marques = Marque::all();
        $uniteReglementaires = UniteReglementaire::all();
        $devises = Devise::all();
        $groupes = Groupe::all();



        $produits = Produit::with('categorie')->with('souscategorie')->get();

        return view('dashboard.produits.create',
        compact(['categories','sousCategories',
        'marques','uniteReglementaires','devises','groupes']));
    }


    public function store(Request $request)
    {
        //
        $request->validate([
        'categorie_id'  => 'required',
        'sous_categorie_id'  => 'required',
        'marque_id'  => 'required',
        'designation'  => 'required',
        'prix_unitaire'  => 'required|numeric|min:0',
        'devise_id'  => 'required',
        'stock_min'  => 'required',
        'unite_reglementaire_id'  => 'required',
        'active'  => 'required',
        ]);
        $produit_data = $request->all();
        $produit_data['user_id'] = Auth::id();
        Produit::create($produit_data);
        return redirect()->route('produits.index');
    }

    public function addMultiProducts(Request $request)
    {
        # code...
        foreach ($request->all() as $produit) {
            # code...
            $produit_data = $produit;
            $produit_data['user_id'] = Auth::id();
            Produit::create($produit_data);
        }
        return response()->json([
            "error" => false,
            "produits" => 'added successefly' ,
            ],200);
    }


    public function show($id)
    {
        //
    }

    public function multi()
    {
        # code...

        $categories = Categorie::all();
        $sousCategories =SousCategorie::all();
        $marques = Marque::all();
        $uniteReglementaires = UniteReglementaire::all();
        $devises = Devise::all();
        $groupes = Groupe::all();

        //dd($sousCategories);
        return view('dashboard.produits.add_multi_product',
        compact(['categories','sousCategories','marques',
        'uniteReglementaires','devises','groupes']));

    }

    public function edit($id)
    {
        //
        $produit = Produit::findOrfail($id);
        $categories = Categorie::all();
        $categories = Categorie::all();
        $sousCategories =SousCategorie::all();
        $marques = Marque::all();
        $uniteReglementaires = UniteReglementaire::all();
        $devises = Devise::all();
        $groupes = Groupe::all();


        return view('dashboard.produits.edit',compact('produit','categories','sousCategories','marques','uniteReglementaires','devises','groupes'));
    }


    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'categorie_id'  => 'required',
            'sous_categorie_id'  => 'required',
            'marque_id'  => 'required',
            'designation'  => 'required',
            'prix_unitaire'  => 'required|numeric|min:0',
            'devise_id'  => 'required',
            'stock_min'  => 'required',
            'unite_reglementaire_id'  => 'required',
            'active'  => 'required',
            ]);
        $produit = Produit::findOrfail($id);
        $produit->update($request->all());

        Session::flash('success','Modifier avec succés');

        return redirect()->back();


    }

    public function active(Request $request , $id)
    {
        # code...
        $produit = Produit::findOrfail($id);
        $produit->update($request->all());

        Session::flash('success','Modifier avec succés');

        return redirect()->back();
    }

    public function updateProduit(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);
        $produit->update($request->all());
        HistoriqueProduit::create([
            'produit_id' => $produit->id,
            'user_id' => Auth::id()
        ]);
        return response()->json([
            "error" => false,
            "produit" => $produit ,

            ],200);
    }


    public function destroy($id)
    {
        //
        $p = Produit::findOrFail($id);
        $ind =InventaireDetail::where('produit_id',$p->id)->count();
        $bs =BsDetail::where('produit_id',$p->id)->count();
        $bl =BlDetail::where('produit_id',$p->id)->count();
        $dd =DemandeDetail::where('produit_id',$p->id)->count();
        $md =MarcheDetail::where('produit_id',$p->id)->count();
        $convd =ConventionDetail::where('produit_id',$p->id)->count();
        $cd =CommandeDetail::where('produit_id',$p->id)->count();

        if ($ind > 0 || $bs > 0 || $bl > 0 || $dd  > 0|| $md > 0 || $convd > 0 || $cd > 0) {
            # code...
            return response(__(' Non Supprimé'));
        }else{
            $p->delete();
            session()->flash('success',__('Supprimé avec succès'));
            return response(__('Supprimé avec succès'));
        }

    }


    // Historiques

    public function historiques()
    {
        //
        $produits = Produit::all();
        $users = User::all();
        return view('dashboard.produits.historique',compact('produits','users'));
    }

    public function historiquesData()
    {
        //
        $hitoriques = HistoriqueProduit::whenUserId(request()->user_id)
                                        ->whenProduitId(request()->produit_id);

        //dd(request()->pays);
        return DataTables::of($hitoriques)

            ->addColumn('produit', function (HistoriqueProduit $hitorique) {
                return view('dashboard.produits.data_table.historiques.produits', compact('hitorique'));
            })
            ->addColumn('user', function (HistoriqueProduit $hitorique) {
                return view('dashboard.produits.data_table.historiques.users', compact('hitorique'));
            })
              ->editColumn('created_at',function(HistoriqueProduit $hitoriques){
                return $hitoriques->created_at->format('Y-m-d H:m');
              })
              ->addColumn('actions','dashboard.parametrage.villes.data_table.actions')
              ->rawColumns(['record_select', 'actions'])
              ->toJson();
    }

    public function rapport(Request $request)
    {
        # code...
        $produits = Produit::when($request->produit_id, function ($q) use ($request) {
                     return $q->where('id',$request->produit_id);
                })->when($request->categorie_id, function ($q) use ($request) {
                    return $q->where('categorie_id', $request->categorie_id);
                })->when($request->sous_categorie_id, function ($q) use ($request) {
                    return $q->where('sous_categorie_id', $request->sous_categorie_id);
                })->when($request->marque_id, function ($q) use ($request) {
                    return $q->where('marque_id', $request->marque_id);
                })->when($request->active, function ($q) use ($request) {
                    return $q->where('active', $request->active);
                })->with('categorie')
                ->with('souscategorie')
                ->with('marque')
                ->with('uniteReglementaire')
                ->get();

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
            'active' => '',
            'details'     => $produits,
            'categories' => $categories,
            'souscategories' => $scategories,

        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/produits', array('data' => $data))->setPaper('a4', 'portrait');

        return $pdf->download('la liste des articles.pdf');
    }
}
