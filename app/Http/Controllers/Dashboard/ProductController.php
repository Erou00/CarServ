<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = Product::all();

        return view('dashboard.products.index',compact(['products']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('dashboard.products.create');
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
            'name' => 'required',
            'description' => 'required',
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'stock' => 'required',
            'images' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->slug = Str::of($request->name)->slug('-');
        $product->description = $request->description;
        $product->purchase_price = $request->purchase_price;
        $product->sale_price = $request->sale_price;
        $product->stock= $request->stock;


        if ($request->image) {

            Image::make($request->image)
                ->resize(1020, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/products_images/' . $request->image->hashName()));

                $product->image = $request->image->hashName();

        }

        $request_data = [];
        if($request->hasfile('images')) {

            foreach ($request->images as $image) {
               # code...
               Image::make($image)->resize(1020, null, function ($constraint) {
                   $constraint->aspectRatio();
               })->save(public_path('uploads/products_images/'.$image->hashName()));

               array_push($request_data,$image->hashName());
            }

            $product->images =  json_encode($request_data) ;
       }

       //dd($request_data);
        $product->save();
        toastr()->success('Product added Successfully');


        return redirect()->route('dashboard.products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
        //dd($product);
        return view('dashboard.products.product-details',['product'=>$product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
        return view('dashboard.products.create',compact(['product']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
        $request->validate([
                    'name' => 'required',
                    'description' => 'required',
                    'purchase_price' => 'required',
                    'sale_price' => 'required',
                    'stock' => 'required',
            ]);

            $product->name = $request->name;
            $product->slug = Str::of($request->name)->slug('-');
            $product->description = $request->description;
            $product->purchase_price = $request->purchase_price;
            $product->sale_price = $request->sale_price;
            $product->stock= $request->stock;

                if ($request->image) {

                    if ($product->image != 'default.png') {

                        Storage::disk('public_uploads')->delete('/products_images/' . $product->image);

                    }

                    Image::make($request->image)
                    ->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('uploads/products_images/' . $request->image->hashName()));

                    $product->image = $request->image->hashName();


                }

                $request_data = [];

                if ($request->hasFile('images')) {
                    foreach ( json_decode($product->images) as $image) {
                    Storage::disk('public_uploads')->delete('products_images/'.$image);
                    }

                    foreach ($request->images as $image) {
                        # code...
                        Image::make($image)->resize(1020, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save(public_path('uploads/products_images/'.$image->hashName()));

                        array_push($request_data,$image->hashName());
                     }

                     $product->images =  json_encode($request_data) ;

                }


                $product->update();
                toastr()->success('Product updated Successfully');

                return redirect()->route('dashboard.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $product = Product::withTrashed()->where('id',$id)->first();
        if($product->trashed()){
        if ($product->image != 'default.png') {
            Storage::disk('public_uploads')->delete('/products_images/' . $product->image);
        }

        if ($product->images) {
            # code...
            foreach ( json_decode($product->images) as $image) {
                Storage::disk('public_uploads')->delete('products_images/'.$image);
                }
         }
         $product->forceDelete();
         session()->flash('success','product deleted successfully');
        }else{
            $product->delete();
            session()->flash('success','product trashed successfully');
            return redirect(route('dashboard.products.index'));
        }

        $product->delete();



        return redirect()->route('dashboard.products.index');
    }



    public function trashed(){
        $trashed = Product::onlyTrashed()->get();
        return view('dashboard.products.outOfStock')->with('products',$trashed);
    }

    public function restore($id){

        Product::withTrashed()->where('id',$id)->restore();
        session()->flash('success','formation resotred successfully');
            return redirect()->back();
    }
}
