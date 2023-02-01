<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\BlDetail;
use App\Models\BsDetail;
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

class MarqueController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_marques')->only(['index']);
        $this->middleware('permission:create_marques')->only(['create', 'store']);
        $this->middleware('permission:update_marques')->only(['edit', 'update']);
        $this->middleware('permission:delete_marques')->only(['delete', 'bulk_delete']);

    }// en of __construct

    public function index()
    {
        //
        $categories = SousCategorie::all();

        return view('dashboard.parametrage.marques.index',compact('categories'));
    }

    public function data()
    {
        $marques = Marque::whenSousCategorieId(request()->categorie_id);

        return DataTables::of($marques)
            ->addColumn('record_select', 'dashboard.parametrage.marques.data_table.record_select')
            ->addColumn('categorie', function (Marque $marque) {
                return view('dashboard.parametrage.marques.data_table.categorie', compact('marque'));
            })
            ->editColumn('created_at', function (Marque $marque) {
                return ($marque->created_at) ? $marque->created_at->format('Y-m-d') : '';
            })
            ->addColumn('actions', 'dashboard.parametrage.marques.data_table.actions')
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
        $categories = SousCategorie::all();
        return view('dashboard.parametrage.marques.create',compact('categories'));
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
            'sous_categorie_id' => 'required'
        ]);

        Marque::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('marques.index');
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
        $marque = Marque::findOrFail($id);
        $categories = SousCategorie::all();
        return view('dashboard.parametrage.marques.edit',compact('marque','categories'));
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
        $marque = Marque::findOrFail($id);
        $request->validate([
            'nom' => 'required',
            'sous_categorie_id' => 'required'
        ]);

        $marque->update($request->all());
        session()->flash('success',__('site.updated_successfully'));

        return redirect()->route('marques.index');
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
        $marque = Marque::findOrFail($id);

        $produits = Produit::where('marque_id',$marque->id)->get();


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
        $marque->delete();
        session()->flash('success',__('Supprimé avec succès'));

        return response(__('Supprimé avec succès'));
    }


    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $marque = Marque::FindOrFail($recordId);
            $this->delete($marque);

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete($id)
    {
        $marque = Marque::findOrFail($id);
        $marque->delete();

    }// end of delete

    public function rapport()
    {
        # code...
        $marques = Marque::with('sousCategorie')->get();

        //dd($marques);
        $data = [

            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'details'     => $marques,

        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/marques', array('data' => $data))->setPaper('a4', 'landscape');

        return $pdf->stream();
    }


    public function getMarque(Request $request)
    {
        # code...
        $select = $request->get('select');
        $value = $request->get('value');

        $dependent = $request->get('dependent');
        $data = DB::table('marques')
          ->when($request->value != "",function($q) use ($request){
                    $q->where('sous_categorie_id',$request->value);
          })
          ->get();

        // Select '.ucfirst($dependent).
        $output = '<option value="">Marque</option>';
        foreach($data as $row)
        {
         $output .= '<option value="'.$row->id.'">'.$row->nom.'</option>';
        }
        echo $output;
    }
}
