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



            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<!-- end row -->



@endsection



@section('scripts')

@endsection
