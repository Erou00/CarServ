<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    //
    public function index()
    {
        # code...
        $orders = Order::all();
        //dd($orders);
        return view('dashboard.orders.index')->with('orders',$orders);
    }

    public function details($id)
    {
        # code...
        $order = Order::find($id);
        return view('dashboard.orders.details')->with('order',$order);
    }

    public function delete($id)
    {
        # code...
        $order = Order::find($id);
        $order->delete();
        return redirect()->route('dashboard.order.index');
    }
}
