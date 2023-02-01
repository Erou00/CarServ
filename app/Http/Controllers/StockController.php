<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Convention;
use App\Models\Marche;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    //

    public function allCommandeDetails($id)
    {
        # code...
        $commande = Commande::where('id',$id)->with('fournisseur')->with('bls',function($q) use($id){
            $q->orderBy('created_at','desc')
                ->with('blDetails',function($q) use ($id){
                    $q->join('produits','bl_details.produit_id','=','produits.id')
                      ->select('bl_details.*',
                      DB::raw("COALESCE((SELECT SUM(bl_details.qte_livree)
                      FROM bl_details,bls
                      WHERE bl_details.produit_id = produits.id
                      AND bl_details.bl_id = bls.id
                      AND bls.commande_id = ".$id."
                      GROUP BY bl_details.produit_id),0) as qte_deja_livree"),
                      DB::raw("COALESCE((SELECT  bl_details.qte - SUM(bl_details.qte_livree)
                      FROM bl_details,bls
                      WHERE bl_details.produit_id = produits.id
                      AND bl_details.bl_id = bls.id
                      AND bls.commande_id = ".$id."
                      GROUP BY bl_details.produit_id),0) as qte_reste_a_livree"))
                      ->with('produit',function($q){
                              $q->with('uniteReglementaire');
                      });
                });
        })->get();


        $stocks = DB::table('produits')
        ->join('commande_details', 'produits.id', '=', 'commande_details.produit_id')
        ->join('unite_reglementaires', 'unite_reglementaires.id', '=', 'produits.unite_reglementaire_id')
        ->select('produits.id','produits.designation','commande_details.*',
        'unite_reglementaires.code',
        DB::raw("COALESCE((SELECT SUM(bl_details.qte_livree) FROM bl_details,bls
        WHERE bl_details.produit_id = produits.id
        AND bl_details.bl_id = bls.id
        AND bls.commande_id = ".$id."
        GROUP BY bl_details.produit_id),0) as qte_livree"))
        ->where('commande_details.commande_id',$id)
        ->get();

        return response()->json([
            "error" => false,
            'commande'=>$commande,
            "stocks" => $stocks ,

            ],200);

    }

    public function allConventionDetails($id)
    {
        # code...
        $convention = Convention::where('id',$id)->with('fournisseur')->with('bls',function($q) use($id){
            $q->orderBy('created_at','desc')
                ->with('blDetails',function($q) use ($id){
                    $q->join('produits','bl_details.produit_id','=','produits.id')
                      ->select('bl_details.*',
                      DB::raw("COALESCE((SELECT SUM(bl_details.qte_livree)
                      FROM bl_details,bls
                      WHERE bl_details.produit_id = produits.id
                      AND bl_details.bl_id = bls.id
                      AND bls.convention_id = ".$id."
                      GROUP BY bl_details.produit_id),0) as qte_deja_livree"),
                      DB::raw("COALESCE((SELECT  bl_details.qte - SUM(bl_details.qte_livree)
                      FROM bl_details,bls
                      WHERE bl_details.produit_id = produits.id
                      AND bl_details.bl_id = bls.id
                      AND bls.convention_id = ".$id."
                      GROUP BY bl_details.produit_id),0) as qte_reste_a_livree"))
                      ->with('produit',function($q){
                              $q->with('uniteReglementaire');
                      });
                });
        })->get();

        $stocks = DB::table('produits')
        ->join('convention_details', 'produits.id', '=', 'convention_details.produit_id')
        ->join('unite_reglementaires', 'unite_reglementaires.id', '=', 'produits.unite_reglementaire_id')
        ->select('produits.id','produits.designation','convention_details.*',
        'unite_reglementaires.code',
        DB::raw("(SELECT SUM(bl_details.qte_livree) FROM bl_details,bls
        WHERE bl_details.produit_id = produits.id
        AND bl_details.bl_id = bls.id
        AND bls.convention_id = ".$id."
        GROUP BY bl_details.produit_id) as qte_livree"))
        ->where('convention_details.convention_id',$id)
        ->get();

        return response()->json([
            "error" => false,
            "convention" => $convention,
            "stocks" => $stocks ,

            ],200);

    }

    public function allMarcheDetails($id)
    {
        # code...
        $marche = Marche::where('id',$id)->with('fournisseur')->with('bls',function($q) use($id){
            $q->orderBy('created_at','desc')
                ->with('blDetails',function($q) use ($id){
                    $q->join('produits','bl_details.produit_id','=','produits.id')
                      ->select('bl_details.*',
                      DB::raw("COALESCE((SELECT SUM(bl_details.qte_livree)
                    FROM bl_details,bls
                    WHERE bl_details.produit_id = produits.id
                    AND bl_details.bl_id = bls.id
                    AND bls.marche_id = ".$id."
                    GROUP BY bl_details.produit_id),0) as qte_deja_livree"),

                      DB::raw("COALESCE((SELECT  bl_details.qte - SUM(bl_details.qte_livree)
                    FROM bl_details,bls
                    WHERE bl_details.produit_id = produits.id
                    AND bl_details.bl_id = bls.id
                    AND bls.marche_id = ".$id."
                    GROUP BY bl_details.produit_id),0) as qte_reste_a_livree"))
                    ->with('produit',function($q){
                            $q->with('uniteReglementaire');
                    });
                });
        })->get();




        $stocks = DB::table('produits')
        ->join('marche_details', 'produits.id', '=', 'marche_details.produit_id')
        ->join('unite_reglementaires', 'unite_reglementaires.id', '=', 'produits.unite_reglementaire_id')
        ->select('produits.id','produits.designation','marche_details.*',
        'unite_reglementaires.code',
        DB::raw("(SELECT SUM(bl_details.qte_livree) FROM bl_details,bls
        WHERE bl_details.produit_id = produits.id
        AND bl_details.bl_id = bls.id
        AND bls.marche_id = ".$id."
        GROUP BY bl_details.produit_id) as qte_livree"))
        ->where('marche_details.marche_id',$id)
        ->get();

        return response()->json([
            "error" => false,
            "stocks" => $stocks ,
            "marche" => $marche

            ],200);

    }
}
