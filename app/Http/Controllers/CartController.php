<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cartalyst\Stripe\Stripe;



class CartController extends Controller
{
    //
    public function index()
    {
        # code...
        return view('products.cart');
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
                'product' => json_encode($product),
                'product_id' => $id,
                'quantity' => 1,
                'price' => $product->sale_price,
                'user_id' => Auth::user()->id
            ]);
            $product->stock = $product->stock - 1;
            $product->update();
         }else{
            $cart = Cart::where('product_id',$id)
                        ->increment('quantity');
            $product->stock = $product->stock - 1;
            $product->update();
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

    public function getItemsFromCart()
    {
        # code...
        $cartItems = Cart::with('products')->where('user_id',Auth::user()->id)
                ->get();

        $amount = 0;

        $finalData = [];

        if (isset($cartItems)) {
            # code...

            foreach($cartItems as $cartItem)
                if ($cartItem->products) {
                    # code...
                    {

                        foreach ($cartItem->products as $cartProduct) {

                            if ( $cartProduct->id == $cartItem->product_id) {
                                # code...
                                $finalData[$cartItem->id]['cart_id']=$cartItem->id;
                                $finalData[$cartItem->id]['product_id']=$cartProduct->id;
                                $finalData[$cartItem->id]['name']=$cartProduct->name;
                                $finalData[$cartItem->id]['price']=$cartItem->price;
                                $finalData[$cartItem->id]['quantity']=$cartItem->quantity;
                                $finalData[$cartItem->id]['total']=$cartItem->price*$cartItem->quantity;
                                $finalData[$cartItem->id]['image']=$cartProduct->image;
                                $amount += $cartItem->price*$cartItem->quantity;

                            }

                        }



                    }


                }


        }


            return response()->json([
                "finalData" => $finalData,
                "amount" => $amount
            ]);


    }


    public function placeOrder(Request $request)
    {
        # code...
       foreach ($request->items as $value) {
        # code...

         $cart =  Cart::where('id', $value['cart_id'])
                 ->where('product_id' ,$value['product_id'])
                 ->first();

          $product = Product::find($value['product_id']);

           if ($cart->quantity != $value['quantity']) {
            # code...
                if ($cart->quantity < $value['quantity'] ) {
                    # code...
                    $stock =  $value['quantity'] - $cart->quantity;
                    $product->stock = $product->stock - $stock;

                    $product->update();

                }
                elseif ($cart->quantity > $value['quantity']) {
                    # code...
                    $stock =    $cart->quantity - $value['quantity'];
                    $product->stock = $product->stock - $stock;

                    $product->update();
                }
                $cart->quantity = $value['quantity'];
                $cart->update();
           }

       }



    }


    public function checkout()
    {
        $cartItems = Cart::with('products')->where('user_id',Auth::user()->id)
        ->get();

        $amount = 0;
        $finalData = [];

        if (isset($cartItems)) {
            # code...
            foreach($cartItems as $cartItem)
                if ($cartItem->products) {
                    # code...
                    {
                        foreach ($cartItem->products as $cartProduct) {

                            if ( $cartProduct->id == $cartItem->product_id) {
                                # code...
                                $finalData[$cartItem->id]['cart_id']=$cartItem->id;
                                $finalData[$cartItem->id]['product_id']=$cartProduct->id;
                                $finalData[$cartItem->id]['name']=$cartProduct->name;
                                $finalData[$cartItem->id]['price']=$cartItem->price;
                                $finalData[$cartItem->id]['quantity']=$cartItem->quantity;
                                $finalData[$cartItem->id]['total']=$cartItem->price*$cartItem->quantity;
                                $finalData[$cartItem->id]['image']=$cartProduct->image;
                                $amount += $cartItem->price*$cartItem->quantity;

                            }

                        }

                    }
                }
        }


        return view('products.checkout')->with('finalData',$finalData)->with('amount' , $amount);
    }

    public function processPayment(Request $request)
    {
        $this->validate($request,[
            'firstName' => 'required',
            'lastName' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipCode' => 'required',
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => 'required',

        ]);

        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        $address = $request->get('address');
        $city = $request->get('city');
        $state = $request->get('state');
        $zipCode = $request->get('zipCode');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $country = $request->get('country');

        $expirationMonth = $request->get('expirationMonth');
        $expirationYear = $request->get('expirationYear');
        $cvv = $request->get('cvv');
        $cardNumber = str_replace(' ', '', $request->get('cardNumber'));

        //dd($cardNumber);

        $amount = $request->get('amount');

        $cartItems = Cart::with('products')->where('user_id',Auth::user()->id)
        ->get();

        $stripe = Stripe::make(env('STRIPE_SECRET'));

        try {
            //code...
            $token = $stripe->tokens()->create([
                'card'=> [
                    "number" => (int)  $cardNumber,
                    "currency" => "MAD",
                    "exp_month" => (int) $expirationMonth,
                    "exp_year" => (int) $expirationYear
                ]

            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['stripeErrors' => $e->getMessage()]);
        }


        if (!$token['id']) {
            # code...
            session()->flush('error','erreur');
            return redirect()->back();
        }

        $customer = $stripe->customers()->create([

            'name' => $firstName.' '.$lastName,
            'email' => $email ,
            'phone' => $phone ,
            'address' => [
                            'line1' => $address ,
                            'postal_code' =>$zipCode ,
                            'city' => $city,
                            'state' => $state ,
                            'country' => 'morocco',
            ],
            'shipping' => [
                'name' => $firstName.' '.$lastName,
                'address' => [
                    'line1' => 'temara' ,
                    'postal_code' =>$zipCode,
                    'city' => $city,
                    'state' => $state ,
                    'country' => 'morocco',
                ],
            ],

            'source' => $token['id']

            ]);
            // Code for charge

          $charge = $stripe->charges()->create([
              'customer' => $customer['id'],
              'currency' => 'MAD',
              'amount' => $amount,
              'description' => 'payment for order'
          ]);

          if ($charge['status'] == 'succeeded') {
            # code...
            // capture the details from stripe

            $customerIdStripe = $charge['id'];
            $amountRec = $charge['amount'];

            $processingDetails =  auth()->user()->orders()->create([
               'cart' => json_encode($cartItems)

            ]);


               if ($processingDetails) {
                   # code...
                   Cart::where('user_id',Auth::user()->id)->delete();

                   return redirect()->route('clientOrders');
               }


        }



    }

    public function orders()
    {
        # code...
        $orders = auth()->user()->orders;

        $carts = $orders->map( function( $cart, $key) {
            return json_decode($cart->cart) ;
        });
        //dd($carts);
        return view('client.orders.index')->with('carts', $carts);

    }
}
