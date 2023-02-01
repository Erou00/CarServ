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
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SousCategorieController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_sous_categories')->only(['index']);
        $this->middleware('permission:create_sous_categories')->only(['create', 'store']);
        $this->middleware('permission:update_sous_categories')->only(['edit', 'update']);
        $this->middleware('permission:delete_sous_categories')->only(['delete', 'bulk_delete']);

    }// end of __construct
    public function index()
    {
        //
        $categories = Categorie::all();

        return view('dashboard.parametrage.sous_categories.index',compact('categories'));
    }

    public function data()
    {

        return response()->json([
            "error" => false,
            "sousCategories" => SousCategorie::all(),

            ],200);

    }// end of data

    public function allSousCategories()
    {
        # code...

         $categories = SousCategorie::whenCategorieId(request()->categorie_id);

        return DataTables::of($categories)
            ->addColumn('record_select', 'dashboard.parametrage.sous_categories.data_table.record_select')
            ->addColumn('categorie', function (SousCategorie $sc) {
                return view('dashboard.parametrage.sous_categories.data_table.categorie', compact('sc'));
            })
            ->editColumn('created_at', function (SousCategorie $categorie) {
                return $categorie->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'dashboard.parametrage.sous_categories.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Categorie::all();
        return view('dashboard.parametrage.sous_categories.create',compact('categories'));
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
            'categorie_id' => 'required'
        ]);

        SousCategorie::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('sous_categories.index');
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
    public function edit( $id)
    {
        //
        $category = SousCategorie::findOrFail($id);
        $mereCategories = Categorie::all();
        return view('dashboard.parametrage.sous_categories.edit',compact('category','mereCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        //
        $category = SousCategorie::findOrFail($id);
        $request->validate([
            'nom' => 'required',
            'categorie_id' => 'required'
        ]);

        $category->update($request->all());
        session()->flash('success',__('site.updated_successfully'));

        return redirect()->route('sous_categories.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $category = SousCategorie::findOrFail($id);
        $category->delete();
        session()->flash('success',__('Supprimé avec succès'));

        return response(__('Supprimé avec succès'));
    }


    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $this->delete($recordId);

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete($id)
    {
        $category = SousCategorie::findOrFail($id);

        $produits = Produit::where('sous_categorie_id',$category->id)->get();
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
                # code...
                return response(__(' Non Supprimé'));
            }else{
                $p->delete();
            }
        }
                # code...
            Marque::where('sous_categorie_id',$category->id)->delete();




        $category->delete();

    }// end of delete

    public function rapport()
    {
        # code...
        $categories= SousCategorie::with('categorie')->get();

        $data = [

            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'details'     => $categories,

        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/sous_categories', array('data' => $data))->setPaper('a4', 'landscape');

        return $pdf->stream();
    }

    public function getSousCategorie(Request $request)
    {
        # code...
        $select = $request->get('select');
        $value = $request->get('value');

        $dependent = $request->get('dependent');
        $data = DB::table('sous_categories')
                 ->when($request->value != "",function($q) use ($request){
                     $q->where('categorie_id',$request->value);
                })->get();

        // Select '.ucfirst($dependent).
        $output = '<option value="">Sous Categorie</option>';
        foreach($data as $row)
        {
         $output .= '<option value="'.$row->id.'">'.$row->nom.'</option>';
        }
        echo $output;
    }
}
