<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //
    public function index()
    {
        # code...
        return view('pages.checkout');
    }

    public function store(Request $request)
    {
        if (!$request->product_id) {
            # code...
            return [
                'message' => 'Cart updated',
                'items' => Cart::where('user_id',Auth::user()->id)
                ->sum('quantity')
                ];
        }
        # code...
        $id = $request->product_id;
        $product = Product::find($id);

        $productFoundInCart = Cart::where('product_id',$id)
                                ->pluck('id');


         if($productFoundInCart->isEmpty()){
            $cart = Cart::create([
                'product_id' => $id,
                'quantity' => 1,
                'price' => $product->sale_price,
                'user_id' => Auth::user()->id
            ]);
         }else{
            $cart = Cart::where('product_id',$id)
                        ->increment('quantity');
         }

         $userItems = Cart::where('user_id',Auth::user()->id)
                        ->sum('quantity');
         if ($cart) {
             return [
                 'message' => 'Cart updated',
                 'items' => $userItems
                ];
         }

    }


}
