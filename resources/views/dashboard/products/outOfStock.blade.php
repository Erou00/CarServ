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
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Car service</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </div>
            <h4 class="page-title">Products</h4>
        </div>
    </div>
</div>
<!-- end page title -->





<!-- row -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <a href="{{route('dashboard.products.create')}}" class="btn btn-danger mb-2">
                            <i class="mdi mdi-plus-circle me-2"></i> Add Products</a>
                    </div>
                    <div class="col-sm-8">

                    </div><!-- end col-->
                </div>


                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>name</th>
                            <th>description</th>
                            <th>image</th>
                            <th>purchase_price</th>
                            <th>sale price</th>
                            <th>profit percent </th>
                            <th>stock</th>
                            <th>action</th>

                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($products as $index=>$product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ substr($product->name,0,30).'...'  }}</td>
                            <td>{{ substr(strip_tags($product->description),0,30).'...' }}</td>
                            <td><img src="{{ asset($product->image_path) }}" style="width: 100px"  class="img-thumbnail" alt=""></td>
                            <td>{{ $product->purchase_price }}</td>
                            <td>{{ $product->sale_price }}</td>
                            <td>{{ $product->profit_percent }} %</td>
                            <td>{{ $product->stock }}</td>
                            <td>


                                <a href="{{route('dashboard.products.restore',$product->id)}}" class="action-icon"> <i class="mdi mdi-refresh-circle"></i></a>
                                <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="POST"
                                     style="display: inline-block" onsubmit="return confirm('Are You Sure?')">
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
<!-- plugin js -->
<script src="{{asset('assets/js/vendor/dropzone.min.js')}}"></script>
<!-- init js -->
<script src="{{asset('assets/js/ui/component.fileupload.js')}}"></script>

     <script src="{{asset('assets/js/vendor/jquery.dataTables.min.js')}}"></script>
     <script src="{{asset('assets/js/vendor/dataTables.bootstrap5.js')}}"></script>
     <script src="{{asset('assets/js/vendor/dataTables.responsive.min.js')}}"></script>
     <script src="{{asset('assets/js/vendor/responsive.bootstrap5.min.js')}}"></script>

     <!-- third party js ends -->

     <!-- demo app -->
     <script src="{{asset('assets/js/pages/demo.datatable-init.js')}}"></script>


@endsection
