<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\Magasin;
use App\Models\SousMagasin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SousMagasinController extends Controller
{
    public function index()
    {
        //
        $magasins = Magasin::all();

        return view('dashboard.parametrage.sous_magasins.index',compact('magasins'));
    }

    public function data()
    {

        return response()->json([
            "error" => false,
            "SousMagasins" => SousMagasin::all(),

            ],200);

    }// end of data

    public function allSousMagasins(Request $request)
    {
        # code...

         $magasins = SousMagasin::when($request->magasin_id,function($q) use($request){
                        $q->where('magasin_id',$request->magasin_id);
                    })->get();

        return DataTables::of($magasins)
            ->addColumn('record_select', 'dashboard.parametrage.sous_magasins.data_table.record_select')
            ->addColumn('magasin_mere', function (SousMagasin $sc) {
                return ($sc->magasin()) ? $sc->magasin->nom : '';
            })
            ->editColumn('created_at', function (SousMagasin $magasin) {
                return $magasin->created_at->format('d/m/Y');
            })
            ->addColumn('actions', 'dashboard.parametrage.sous_magasins.data_table.actions')
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
        $magasins = Magasin::all();
        return view('dashboard.parametrage.sous_magasins.create',compact('magasins'));
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
            'magasin_id' => 'required'
        ]);

        SousMagasin::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('sous_magasins.index');
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
        $magasin = SousMagasin::findOrFail($id);
        $mereMagasins = Magasin::all();
        return view('dashboard.parametrage.sous_magasins.edit',compact('magasin','mereMagasins'));
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
        $category = SousMagasin::findOrFail($id);
        $request->validate([
            'nom' => 'required',
            'magasin_id' => 'required'
        ]);

        $category->update($request->all());
        session()->flash('success',__('site.updated_successfully'));

        return redirect()->route('sous_magasins.index');
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
        $category = SousMagasin::findOrFail($id);
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
        $category = SousMagasin::findOrFail($id);
        $category->delete();

    }// end of delete

    public function rapport()
    {
        # code...
        $magasins= SousMagasin::with('magasin')->get();

        $data = [

            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'details'     => $magasins,

        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/sous_magasins', array('data' => $data))->setPaper('a4', 'landscape');

        return $pdf->stream();
    }

    public function getSousMagasin(Request $request)
    {
        # code...
        $select = $request->get('select');
        $value = $request->get('value');

        $dependent = $request->get('dependent');
        $data = DB::table('sous_magasins')
                 ->when($request->value != "",function($q) use ($request){
                     $q->where('magasin_id',$request->value);
                })->get();

        // Select '.ucfirst($dependent).
        $output = '<option value=""></option>';
        foreach($data as $row)
        {
         $output .= '<option value="'.$row->id.'">'.$row->nom.'</option>';
        }
        echo $output;
    }

    public function getUserSousMagasin()
    {
        # code...
        $sMagasins = SousMagasin::join('sous_magasin_user','sous_magasins.id','=','sous_magasin_user.sous_magasin_id')
        ->where('sous_magasin_user.user_id',Auth::id())
        ->select('sous_magasins.id','sous_magasins.nom')
        ->get();


        return response()->json([
            "error" => false,
            "SousMagasins" => $sMagasins ,

            ],200);

    }
}
