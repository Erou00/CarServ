@extends('layouts.app')
@section('styles')
<script src="https://kit.fontawesome.com/39e20eb3b4.js" crossorigin="anonymous"></script>
<style>


</style>
@endsection
@section('content')
<div class="container mt-5 car" >
    <div class="main-body">



        <div class="container">
            <div class="row">
                <h3>Orders</h3>
                <div class="col-md-9">
                    @foreach($carts as $cart)
                    <div class="card mb-3">
                        <div class="card-body">

                            <table class="table table-striped mt-2 mb-2">
                                <thead>
                                    <tr>

                                        <th scope="col"></th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                    $amount = 0
                                @endphp
                                @foreach($cart as $item)
                                 {{-- {{ dd($item)}} --}}
                                    <tr>
                                        <td>
                                       @foreach ($item->products as $p)
                                            <img src="  {{ asset('uploads/products_images/'.$p->image) }}" alt="" srcset="" class="img-thumbnail"
                                            style="width: 100px">
                                        </td>
                                        @endforeach
                                        <td>
                                            @foreach ($item->products as $p)
                                            {{$p->name}}
                                            @endforeach



                                        </td>
                                        <td>{{$item->price}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td></td>

                                        @php
                                            $amount += $item->price * $item->quantity;
                                        @endphp
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <p class="badge badge-pill bg-primary mb-3 p-3 text-white">Total Price : {{$amount}} MAD</p>
                    @endforeach
                </div>
            </div>
        </div>



    </div>
</div>
@endsection

@section('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

@endsection
