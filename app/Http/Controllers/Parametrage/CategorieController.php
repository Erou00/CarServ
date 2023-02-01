<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\BlDetail;
use App\Models\BsDetail;
use App\Models\Categorie;
use App\Models\CommandeDetail;
use App\Models\ConventionDetail;
use App\Models\DemandeDetail;
use App\Models\InventaireDetail;
use App\Models\MarcheDetail;
use App\Models\Marque;
use App\Models\Produit;
use App\Models\SousCategorie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class CategorieController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_categories')->only(['index']);
        $this->middleware('permission:create_categories')->only(['create', 'store']);
        $this->middleware('permission:update_categories')->only(['edit', 'update']);
        $this->middleware('permission:delete_categories')->only(['delete', 'bulk_delete']);

    }// en of __construct

    public function index()
    {
        //

        return view('dashboard.parametrage.categories.index');
    }

    public function data()
    {
        $categories = Categorie::select('*');

        //dd($categories);
        return DataTables::of($categories)
            ->addColumn('record_select', 'dashboard.parametrage.categories.data_table.record_select')
            ->editColumn('created_at', function (Categorie $categorie) {
                return $categorie->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'dashboard.parametrage.categories.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('dashboard.parametrage.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nom' => 'required',
        ]);

        Categorie::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Categorie $category)
    {
        //
        //dd($category);
        return view('dashboard.parametrage.categories.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categorie $category)
    {
        //
        $request->validate([
            'nom' => 'required',
        ]);

        $category->update($request->all());
        session()->flash('success',__('site.updated_successfully'));

        return redirect()->route('categories.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categorie $category)
    {
        //




        $produits = Produit::where('categorie_id',$category->id)->get();
        foreach ($produits as $key => $p) {
            # code...
            $ind =InventaireDetail::where('produit_id',$p->id)->count();
            $bs =BsDetail::where('produit_id',$p->id)->count();
            $bl =BlDetail::where('produit_id',$p->id)->count();
            $dd =DemandeDetail::where('produit_id',$p->id)->count();
            $md =MarcheDetail::where('produit_id',$p->id)->count();
            $convd =ConventionDetail::where('produit_id',$p->id)->count();
            $cd =CommandeDetail::where('produit_id',$p->id)->count();

            if ($ind > 0 || $bs > 0 || $bl > 0 || $dd  > 0|| $md > 0 || $convd > 0 || $cd > 0) {
                return response(__(' Non Supprimé'));
            }else{
                $p->delete();
            }
        }


       $sousCategories =  SousCategorie::where('categorie_id',$category->id)->get();

        foreach ($sousCategories as $key => $sc) {
            # code...
            Marque::where('sous_categorie_id',$sc->id)->delete();
            $sc->delete();
        }

        $category->delete();


        session()->flash('success',__('Supprimé avec succès'));

        return response(__('Supprimé avec succès'));
    }


    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $categorie = Categorie::FindOrFail($recordId);
            $this->delete($categorie);

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete(Categorie $category)
    {
        $category->delete();

    }// end of delete

    public function rapport()
    {
        # code...
        $categories= Categorie::all();

        $data = [

            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'details'     => $categories,

        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/categories', array('data' => $data))->setPaper('a4', 'landscape');

        return $pdf->stream();
    }

}


