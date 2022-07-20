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
                    <li class="breadcrumb-item active">Orders</li>
                </ol>
            </div>
            <h4 class="page-title">Orders</h4>
        </div>
    </div>
</div>
<!-- end page title -->





<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">



                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Total Price</th>
                            <th>Quantity</th>
                            <th>For</th>

                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($orders as $key=>$order)
                            <tr>

                                <td>{{$key + 1}}</td>
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



                                <td>
                                    {{ $price }} MAD
                                </td>


                                <td>
                                    {{ $quantity }}
                                </td>

                                <td>{{$order->user->last_name.' '.$order->user->first_name}}</td>
                                <td>

                                    <a href="{{route('dashboard.order.details',$order->id)}}" class="action-icon"> <i class="mdi mdi-eye"></i></a>

                                    <form action="{{ route('dashboard.order.delete', $order->id) }}" method="POST"
                                        style="display: inline-block">
                                       @csrf
                                       @method('DELETE')
                                      <button type="submit" class="action-icon" style="border: none;background: none"><i class="mdi mdi-delete"></i></button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach


                    </tbody>
                </table>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<!-- end row -->


@endsection

@section('scripts')


     <script src="{{asset('assets/js/vendor/jquery.dataTables.min.js')}}"></script>
     <script src="{{asset('assets/js/vendor/dataTables.bootstrap5.js')}}"></script>
     <script src="{{asset('assets/js/vendor/dataTables.responsive.min.js')}}"></script>
     <script src="{{asset('assets/js/vendor/responsive.bootstrap5.min.js')}}"></script>

     <!-- third party js ends -->

     <!-- demo app -->
     <script src="{{asset('assets/js/pages/demo.datatable-init.js')}}"></script>




@endsection
