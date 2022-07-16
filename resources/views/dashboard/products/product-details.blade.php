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
                    <li class="breadcrumb-item active">Product Details</li>
                </ol>
            </div>
            <h4 class="page-title">Product Details</h4>
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
                    <div class="col-lg-5">
                        <!-- Product image -->
                        <a href="javascript: void(0);" class="text-center d-block mb-4">
                            <img src="{{$product->image_path}}" class="img-fluid" style="max-width: 280px;" alt="Product-img">
                        </a>


                    </div> <!-- end col -->
                    <div class="col-lg-7">
                        <form class="ps-lg-4">
                            <!-- Product title -->
                            <h3 class="mt-0">{{$product->name}} <a href="javascript: void(0);" class="text-muted"><i class="mdi mdi-square-edit-outline ms-2"></i></a> </h3>
                            <p class="mb-1">Added Date: {{ date('d/m/Y', strtotime($product->created_at))}}</p>


                            <!-- Product stock -->
                            @if ($product->stock > 0 )
                                <div class="mt-3">
                                    <h4><span class="badge badge-success-lighten">Instock</span></h4>
                                </div>
                            @endif


                            <!-- Product description -->

                            <div class="mt-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h6 class="font-14">Purchase Price:</h6>
                                        <h3> {{$product->purchase_price}} MAD</h3>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="font-14">Sale Price:</h6>
                                        <h3> {{$product->sale_price}} MAD</h3>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="font-14">Profit:</h6>
                                        <h3> {{$product->profit_percent}}%</h3>
                                    </div>
                                </div>
                            </div>


                            <!-- Product information -->
                            <div class="mt-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h6 class="font-14">Available Stock:</h6>
                                        <p class="text-sm lh-150">{{$product->stock}}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="font-14">Number of Orders:</h6>
                                        <p class="text-sm lh-150">5,458</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="font-14">Revenue:</h6>
                                        <p class="text-sm lh-150">$8,57,014</p>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div> <!-- end col -->
                </div> <!-- end row-->

                <div class="row">
                    <div class="col-lg-12">
                          <!-- Product description -->
                          <div class="mt-4">
                            <h6 class="font-14">Description:</h6>
                                {!! $product->description !!}
                            </div>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Outlets</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ASOS Ridley Outlet - NYC</td>
                                <td>$139.58</td>
                                <td>
                                    <div class="progress-w-percent mb-0">
                                        <span class="progress-value">478 </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 56%;" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>$1,89,547</td>
                            </tr>
                            <tr>
                                <td>Marco Outlet - SRT</td>
                                <td>$149.99</td>
                                <td>
                                    <div class="progress-w-percent mb-0">
                                        <span class="progress-value">73 </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 16%;" aria-valuenow="16" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>$87,245</td>
                            </tr>
                            <tr>
                                <td>Chairtest Outlet - HY</td>
                                <td>$135.87</td>
                                <td>
                                    <div class="progress-w-percent mb-0">
                                        <span class="progress-value">781 </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>$5,87,478</td>
                            </tr>
                            <tr>
                                <td>Nworld Group - India</td>
                                <td>$159.89</td>
                                <td>
                                    <div class="progress-w-percent mb-0">
                                        <span class="progress-value">815 </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 89%;" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>$55,781</td>
                            </tr>
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
