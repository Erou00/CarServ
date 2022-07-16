<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function carForSale(Request $request)
    {
        # code...

        $marques = DB::table('marks')
        ->get();


        $cars = Car::when($request->mark_id, function($q)use ($request){
                        return $q->where('marque_id',  $request->mark_id);
                    })
                    ->when($request->model_id, function($q)use ($request){
                       return $q->where('model_id',  $request->model_id);
                    })
                    ->when($request->minPrice, function($q)use ($request){
                        return $q->where('price','>=' , $request->minPrice);
                     })
                     ->when($request->maxPrice, function($q)use ($request){
                        return $q->where('price','<=' , $request->maxPrice);
                     })
                     ->where('for_sale',true)
                    ->paginate(6);

         return view('carForSale',[
            'marques' => $marques,
            'cars' => $cars
         ]);


    }


    public function carDetails($slug)
    {
        # code...
        $car = Car::where('slug','like',''.$slug.'%')
                    ->first();


        return view('car-details')->with('car',$car);
    }

    public function products(Request $request)
    {
        # code...
        $products =  Product::when($request->search, function($q)use ($request){
                            return $q->where('name','like',  '%'.$request->search.'%');
                        })
                        ->orderBy('created_at', 'desc')
                        ->paginate(6);

        return view('products.index',['products'=>$products]);
    }

    public function productDetails($slug)
    {
        # code...
        $product = Product::where('slug','like',''.$slug.'%')
                    ->first();


        return view('products.details')->with('product',$product);
    }

    public function models(Request $request)
    {
        //
        $select = $request->get('select');
        $value = $request->get('value');
        $dependent = $request->get('dependent');
        $data = DB::table('cmodels')
          ->where('MarqueId',$value)
          ->get();

        // Select '.ucfirst($dependent).
        $output = '<option value="">Choose</option>';
        foreach($data as $row)
        {
         $output .= '<option value="'.$row->id.'">'.$row->$dependent.'</option>';
        }
        echo $output;
    }
}
