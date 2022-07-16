@extends('dashboard.layouts.app')


@section('styles')

<link href="{{asset('assets/css/vendor/dataTables.bootstrap5.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/vendor/responsive.bootstrap5.css')}}" rel="stylesheet" type="text/css">



@endsection

@section('content')

  <!-- start page title -->
  <div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Order Details</li>
                </ol>
            </div>
            <h4 class="page-title">Order Details</h4>
        </div>
    </div>
</div>
<!-- end page title -->





<!-- row -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                <div class="row">
                     <!-- end col -->
                    <div class="col-lg-12">
                        <form class="ps-lg-3">
                            <!-- car title -->

                            <p class="mb-1">Added Date : {{ date('d/m/Y', strtotime($order->created_at))}}</p>



                            <!-- car information -->
                            <div class="mt-9">
                                <div class="row">
                                    <h4 class="font-14">Owner of the order:</h4>
                                    <div class="col-md-3">
                                       <img src="{{$order->user->image_path}}"  class="img-fluid img-thumbnail p-2" style="max-width: 200px;height:100px;" alt="{{$order->user->first_name.' '.$order->user->last_name}}" srcset="">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                 <h6 class="font-14">Name:</h6>
                                                 <p class="text-sm lh-150">{{$order->user->first_name.' '.$order->user->last_name}}</p>

                                            </div>

                                            <div class="col-sm-6">
                                                <h6 class="font-14">Email:</h6>
                                                <p class="text-sm lh-150">{{$order->user->email}}</p>
                                            </div>

                                            <div class="col-sm-6">
                                                <h6 class="font-14">Phone Number:</h6>
                                                <p class="text-sm lh-150">{{$order->user->email}}</p>
                                            </div>

                                            <div class="col-sm-6">
                                                <h6 class="font-14">Adress:</h6>
                                               <p class="text-sm lh-150">{{$order->user->adress}}</p>

                                            </div>
                                        </div>

                                    </div>
                                    </div>

                                </div>

                                @php
                                $quantity = 0;
                                $price = 0;
                                @endphp

                                @foreach (json_decode($order->cart) as $item)


                                {{-- {{dd($item)}} --}}
                                @php
                                $quantity += $item->quantity;
                                $price += $item->price * $item->quantity;
                                @endphp

                                @endforeach


                                <div class="row mt-2">
                                    <h4 class="font-18">The order information :</h2>
                                    <div class="col-md-4">
                                        <h6 class="font-14">Quantity:</h6>
                                        <p class="text-lg ml-5 lh-150">{{$quantity}}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="font-14">Amount:</h6>
                                        <p class="text-lg ml-5 lh-150">{{$price}} MAD</p>
                                    </div>

                                </div>




                            </div>

                        </form>
                    </div> <!-- end col -->
                </div> <!-- end row-->


                <div class="table-responsive mt-1">
                    <table class="table table-bordered table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Title</th>
                                <th scope="col">Unit price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $amount = 0
                        @endphp
                        @foreach(json_decode($order->cart) as $item)
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
                </div> <!-- end table-responsive-->



            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<!-- end row -->



@endsection



@section('scripts')

@endsection
