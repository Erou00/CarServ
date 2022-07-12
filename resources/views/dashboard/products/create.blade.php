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
            <h4 class="page-title">ADD Products</h4>
        </div>
    </div>
</div>
<!-- end page title -->





<!-- row -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                <form action="{{ isset($product) ? route('dashboard.products.update',$product) :route('dashboard.products.store') }}" method="post" enctype="multipart/form-data">

                    @csrf
                    @if (isset($product))
                    @method('PUT')
                    @endif
                        <div class="form-group mt-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ isset($product) ? $product->name:""}}">

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <label>description</label>
                            <textarea name="description" class="form-control ckeditor @error('description') is-invalid @enderror">
                                {{ isset($product) ? $product->description:""}}
                            </textarea>

                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>



                    <div class="form-group mt-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control image @error('image') is-invalid @enderror">
                        @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group mt-3">
                        <img src="{{ isset($product) ? asset('uploads/product_images/'.$product->image) : asset('uploads/product_images/default.png') }}" style="width: 100px" class="img-thumbnail image-preview" alt="">
                    </div>

                    <div class="form-group mt-3">
                        <label>Add Others</label>
                        <input type="file" name="images[]" multiple class="form-control images @error('images') is-invalid @enderror">
                        @error('images')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>



                    <div class="form-group mt-3">
                        <label>Purchase price</label>
                        <input type="number" name="purchase_price" step="0.01" class="form-control @error('purchase_price') is-invalid @enderror"
                        value="{{ isset($product) ? $product->purchase_price : old('purchase_price') }}">
                        @error('purchase_price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group mt-3">
                        <label>Sale price</label>
                        <input type="number" name="sale_price" step="0.01" class="form-control @error('sale_price') is-invalid @enderror"
                        value="{{  isset($product) ? $product->sale_price : old('sale_price') }}">
                        @error('sale_price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group mt-3 mb-2">
                        <label>Stock</label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                        value="{{ isset($product) ? $product->stock : old('stock') }}">
                        @error('stock')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
                            {{ isset($product)? "Edit":"Add"}}</button>
                    </div>

                </form><!-- end of form -->



            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<!-- end row -->


@endsection



@section('scripts')
{{-- CKEditor CDN --}}
<script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>

<script>
        $(document).ready(function () {

            ClassicEditor
.create( document.querySelector( '.ckeditor' ) )
.catch( error => {
console.error( error );
} );


$(".image").change(function () {

if (this.files && this.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
        $('.image-preview').attr('src', e.target.result);
    }

    reader.readAsDataURL(this.files[0]);
}

});


});//end of ready

</script>

@endsection
